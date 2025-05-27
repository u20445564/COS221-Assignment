<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');


require_once __DIR__ . '/config.php';

//helperfunctions
function respondJSON($data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function respondError(string $message, int $code): void
{
    respondJSON(['status' => 'error', 'message' => $message], $code);
}


class API 
{
    private static $instance = null;
    private $conn;

    private function __construct($conn) {
        $this->conn = $conn;
    }

    public static function getInstance($conn) {
        if (self::$instance === null) 
        {
            self::$instance = new API($conn);
        }
        return self::$instance;
    }

    public function handleRequest(){

        // 1) Decode the incoming JSON
        $raw  = file_get_contents('php://input');
        $data = json_decode($raw, true);

        // 2) Determine the requested action
        $type = $data['type'] ?? '';

        // 3) Route to the right method
        switch ($type) {
            case 'Login':
                $this->handleLogin($data);
                break;
            case 'Logout':
                $this->handleLogout($data);
                break;
            case 'Register':
                $this->handleRegister($data);
                break;
            case 'GetAllProducts':
                $this->handleGetAllProducts($data);
                break;
            case 'GetAllRetailerProducts':
                $this->handleGetAllRetailerProducts($data);
                break;
            case 'GetAllReviewsAndResponses':
                $this->handleGetAllReviewsAndResponses($data);
                break;
            case 'GetAllUserReviews':
                $this->handleGetAllUserReviews($data);
                break;
            case 'GetProductReviews':
                $this->handleGetProductReviews($data);
                break;
            case 'AddReview':
                $this->handleAddReview($data);
                break;
            case 'UpdateReview':
                $this->handleUpdateReview($data);
                break;
            case 'DeleteReview':   
                $this->handleDeleteReview($data);
                break;
            case 'RespondToReview': 
                $this->handleRespondToReview($data);
                break;
            case 'DeleteResponse':
                $this->handleDeleteResponse($data);
                break;
            case 'GetAllRequests':
                $this->handleGetAllRequests();
                break;
            case 'ApproveAddRequest':
                $this->handleApproveAddRequest($data);
                break;
            case 'ApproveDeleteRequest':
                $this->handleApproveDeleteRequest($data);
                break;
            case 'ApproveUpdateRequest':
                $this->handleApproveUpdateRequest($data);
                break;
            case 'DeleteRequest':
                $this->handleDeleteRequest($data);
                break;
            case 'EditRequest':
                $this->handleEditRequest($data);
                break;
            case 'AddProductRequest':
                $this->handleAddProductRequest($data);
                break;
            case 'GetAllRetailerRequests':
                $this->handleGetAllRetailerRequests($data);
                break;
            case 'DeleteProductRequest':
                $this->handleDeleteProductRequest($data);
                break;
            case 'UpdateProductRequest':
                $this->handleUpdateProductRequest($data);
                break;
            case 'DeclineRequest':
                $this->handleDeclineRequest($data);
                break;
            default:
                respondError("Unknown type: {$type}", 400);
        }   
    }

    private function handleLogin(array $data){

        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($email === '' || $password === '') {
            respondError("Missing email or password", 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            respondError("Invalid email format", 400);
        }

        //Get user from userbase
        $stmt = $this->conn->prepare("SELECT userID, password, apiKey FROM userbase WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['password'] !== $password) {
            respondError("Invalid email or password", 401);
        }

        $userID = $user['userID'];
        $apiKey = $user['apiKey'];
        $userType = 'unknown';
        $userInfo = [];

        // Try normal user
        $stmt = $this->conn->prepare("SELECT username, userFName, userSName FROM user WHERE userID = ?");
        $stmt->execute([$userID]);
        $userResult = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($userResult) {
            $userType = 'user';
            $userInfo = $userResult;
        } else {
            // Try retailer
            $stmt = $this->conn->prepare("
            SELECT ru.retailerCode, r.retailerName 
            FROM retailerusers ru
            JOIN retailers r ON ru.retailerID = r.retailerID 
            WHERE ru.userID = ?
            ");
            
            $stmt->execute([$userID]);
            $retailerResult = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($retailerResult) {
                $userType = 'retailers';
                $userInfo = $retailerResult;
            }else {
                
                // Try admin
                $stmt = $this->conn->prepare("SELECT adminID, adminName FROM admin WHERE userID = ?");
                $stmt->execute([$userID]);
                $adminResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($adminResult) {
                    $userType = 'admin';
                    $userInfo = $adminResult;
                }
            }
        }

        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => [
                "userID" => $userID,
                "email" => $email,
                "apiKey" => $apiKey,
                "user_type" => $userType,
                "info" => $userInfo
            ]
        ]);
    }

    private function handleLogout(array $data){
        $email = trim($data['email'] ?? '');

        if ($email === '') 
        {
            respondError("Missing email", 400);
        }

        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => [
                "email" => $email,
                "message" => "Logout successful"
            ]
        ]);
    }

    private function handleRegister(array $data){
        //Email is login username 
        $userType = trim($data['userType'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $phoneNumber = trim($data['phoneNumber'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            respondError("Invalid email format", 400);
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password)) 
        {
            respondError("Password must be at least 8 characters and include uppercase, lowercase, number, and symbol", 400);
        }

        if ($phoneNumber === '') 
        {
            respondError("Missing phone number", 400);
        }   

        //Check if email already exists in userbase
        $stmt = $this->conn->prepare("SELECT userID FROM userbase WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            respondError("Email already exists", 409);
        }

        //hashed password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        //apikey generator 
        $apiKey = bin2hex(random_bytes(24));

        if ($userType === 'user') 
        {
            $username = trim($data['username'] ?? '');
            $name = trim($data['name'] ?? '');
            $surname = trim($data['surname'] ?? '');

            if ($username === '' || $name === '' || $surname === '') {
                respondError("Missing user fields", 400);
            }

            // Insert into userbase
            $stmt = $this->conn->prepare("INSERT INTO userbase (password, email, apiKey, phoneNumber) VALUES (?, ?, ?, ?)");
            $stmt->execute([$hashedPassword, $email, $apiKey, $phoneNumber]);
            $userID = $this->conn->lastInsertId();

            // Insert into user table
            $stmt = $this->conn->prepare("INSERT INTO user (userID, username, userFName, userSName) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userID, $username, $name, $surname]);

            respondJSON([
                "status" => "success",
                "message" => "User registered successfully",
                "data" => [
                    "userID" => $userID,
                    "email" => $email,
                    "apiKey" => $apiKey
                ]
            ]);
        } 
        elseif ($userType === 'retailer') 
        {
            $retailerName = trim($data['retailerName'] ?? '');
            $phoneNumber = trim($data['phoneNumber'] ?? '');

            if ($retailerName === '' || $phoneNumber === '') {
                respondError("Missing retailer fields", 400);
            }

            // Insert into userbase
            $stmt = $this->conn->prepare("INSERT INTO userbase (password, email, apiKey, phoneNumber) VALUES (?, ?, ?, ?)");
            $stmt->execute([$hashedPassword, $email, $apiKey, $phoneNumber]);
            $userID = $this->conn->lastInsertId();

            // Insert into retailer
            $stmt = $this->conn->prepare("INSERT INTO retailers (retailerName) VALUES (?)");
            $stmt->execute([$retailerName]);
            $retailerID = $this->conn->lastInsertId();

            // Generate retailerCode
            $retailerCode = 'R' . $retailerID;

            // Insert into retaileruser
            $stmt = $this->conn->prepare("INSERT INTO retailerusers (userID, retailerID, retailerCode) VALUES (?, ?, ?)");
            $stmt->execute([$userID, $retailerID, $retailerCode]);

            respondJSON([
                "status" => "success",
                "message" => "Retailer registered successfully",
                "data" => [
                    "userID" => $userID,
                    "retailerID" => $retailerID,
                    "retailerCode" => $retailerCode,
                    "email" => $email,
                    "apiKey" => $apiKey
                ]
            ]);

        }
        else if($userType === 'admin') 
        {
            $adminName = trim($data['adminName'] ?? '');

            if ($adminName === '') {
                respondError("Missing admin name", 400);
            }

            // Insert into userbase
            $stmt = $this->conn->prepare("INSERT INTO userbase (password, email, apiKey, phoneNumber) VALUES (?, ?, ?, ?)");
            $stmt->execute([$hashedPassword, $email, $apiKey, $phoneNumber]);
            $userID = $this->conn->lastInsertId();

            // Insert into admin
            $stmt = $this->conn->prepare("INSERT INTO admins (userID, adminName) VALUES (?, ?)");
            $stmt->execute([$userID, $adminName]);

            respondJSON([
                "status" => "success",
                "message" => "Admin registered successfully",
                "data" => [
                    "userID" => $userID,
                    "email" => $email,
                    "apiKey" => $apiKey
                ]
            ]);
        }
        else 
        {
            respondError("Invalid user type", 400);
        }

    }

    private function handleGetAllProducts($data){
        $params = [];

        $returnFields = isset($data['return']) && is_array($data['return']) && count($data['return']) > 0
            ? implode(", ", array_map(function($f) { return "p." . $f; }, $data['return']))
            : "p.productID, p.productName, p.description, p.imageURL, p.specifications, c.categoryName, b.brandName, MIN(pr.prices) AS lowestPrice";

        $filters = $this->buildProductFiltersFromSearch($data['search'] ?? [], $params);

        $whereClause = count($filters) > 0 ? "WHERE " . implode(" AND ", $filters) : "";

        $sortClause = $this->buildSortClause([
            'sortBy' => $data['sort'] ?? '',
            'sortOrder' => $data['order'] ?? '',
        ]);

        $limitClause = (isset($data['limit']) && is_numeric($data['limit'])) ? "LIMIT " . intval($data['limit']) : "";

        $sql = "
            SELECT $returnFields
            FROM products p
            LEFT JOIN categories c ON p.categoryID = c.categoryID
            LEFT JOIN brands b ON p.brandID = b.brandID
            LEFT JOIN prices pr ON p.productID = pr.productID
            $whereClause
            GROUP BY p.productID
            $sortClause
            $limitClause
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$products) 
        {
            respondError("No products found", 404);
        }

        foreach ($products as &$product) 
        {
            $pricesStmt = $this->conn->prepare("
                SELECT r.retailerName, pr.prices
                FROM prices pr
                JOIN retailers r ON pr.retailerID = r.retailerID
                WHERE pr.productID = ?
            ");
            $pricesStmt->execute([$product['productID']]);
            $product['prices'] = $pricesStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $products
        ]);
    }

    private function handleGetAllRetailerProducts(array $data){
        $params = [];

        // Ensure retailerID is present
        if (empty($data['retailerID'])) 
        {
            respondError("Missing retailerID", 400);
        }
        $retailerID = intval($data['retailerID']);

        // Handle which columns to return
        $returnFields = isset($data['return']) && is_array($data['return']) && count($data['return']) > 0
            ? implode(", ", array_map(function ($f) 
            {
                return "p." . $f;
            }, $data['return']))
            : "p.productID, p.productName, p.description, p.imageURL, p.specifications, c.categorName, b.brandName, pr.prices";

        // Filters from 'search'
        $filters = $this->buildProductFiltersFromSearch($data['search'] ?? [], $params);

        // Always filter by retailer_id
        $filters[] = "cp.retailerID = ?";
        $params[] = $retailerID;

        $whereClause = "WHERE " . implode(" AND ", $filters);

        // Sorting and limiting
        $sortClause = $this->buildSortClause([
            'sortBy' => $data['sort'] ?? '',
            'sortOrder' => $data['order'] ?? '',
        ]);
        $limitClause = (isset($data['limit']) && is_numeric($data['limit'])) ? "LIMIT " . intval($data['limit']) : "";

        // Final SQL
        $sql = "
            SELECT $returnFields
            FROM products p
            LEFT JOIN categories c ON p.categoryID = c.categoryID
            LEFT JOIN brands b ON p.brandID = b.brandID
            JOIN prices ON p.productID = pr.productID
            $whereClause
            $sortClause
            $limitClause
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$products) 
        {
            respondError("No products found for this retailer", 404);
        }

        // No retailer price breakdown here since retailer only sees their own prices
        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $products
        ]);
    }
    //Helper functions for get all products
    // Sort products
    private function buildSortClause($data){
        $allowedSortFields = ['productName', 'lowestPrice_to_highestPrice', 'highestPrice_to_lowestPrice'];
        $allowedSortOrders = ['ASC', 'DESC'];

        $sortBy = isset($data['sortBy']) ? trim($data['sortBy']) : '';
        $sortOrder = isset($data['sortOrder']) ? strtoupper(trim($data['sortOrder'])) : 'ASC';
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'productName';
        $sortOrder = in_array($sortOrder, $allowedSortOrders) ? $sortOrder : 'ASC';
        $sortClause = "";

        if ($sortBy === 'productName') 
        {
            $sortClause = "ORDER BY p.productName $sortOrder";
        }
        elseif ($sortBy === 'lowestPrice_to_highestPrice') 
        {
            $sortClause = "ORDER BY MIN(pr.prices) $sortOrder";
        }
        elseif ($sortBy === 'highestPrice_to_lowestPrice') 
        {
            $sortClause = "ORDER BY MIN(pr.prices) $sortOrder";
        }
        return $sortClause ? " $sortClause" : "";
    }

    // Filter products
    private function buildProductFiltersFromSearch($search, &$params){
        $filters = [];

        foreach ($search as $key => $value) {
            if ($value === "") continue;

            switch ($key) {
                case 'category':
                    $filters[] = "c.categoryName = ?";
                    break;
                case 'brand':
                    $filters[] = "b.brandName = ?";
                    break;
                case 'min_price':
                    $filters[] = "pr.price >= ?";
                    break;
                case 'max_price':
                    $filters[] = "pr.price <= ?";
                    break;
                default:
                    $filters[] = "p.$key LIKE ?";
                    $value = "%$value%";
                    break;
            }

            $params[] = $value;
        }

        return $filters;
    }

    // Search clause
    private function buildSearchClause($data, &$params)
    {
        if (isset($data['search']) && trim($data['search']) !== '') 
        {
            $params[] = '%' . trim($data['search']) . '%';
            return "p.product_name LIKE ?";
        }

        return '';
    }

    //reviews
    private function handleGetAllReviewsAndResponses($data){
        $stmt = $this->conn->prepare("SELECT * FROM reviews");
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$reviews) 
        {
            respondError("No products found", 404);
        }

        //JSON Success Response
        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $reviews
        ]);
    }

    private function handleGetAllUserReviews($data)
    {
        if (!isset($data['userID'])) 
        {
            respondError("Missing userID", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE userID = ?");
        $stmt->execute([$data['userID']]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$reviews) 
        {
            respondError("No reviews found for this user", 404);
        }

        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $reviews
        ]);
    }

    private function handleAddReview($data)
    {
        if (!isset($data['productID'], $data['userID'], $data['rating'], $data['comment'])) {
            respondError("Missing required fields", 400);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO reviews (productID, userID, rating, comment, createdAt)
            VALUES (?, ?, ?, ?, NOW())
        ");

        $success = $stmt->execute([
            $data['productID'],
            $data['userID'],
            $data['rating'],
            $data['comment']
        ]);

        if (!$success) {
            respondError("Failed to add review", 500);
        }

        respondJSON([
            "status" => "success",
            "message" => "Review added successfully",
            "review_id" => $this->conn->lastInsertId()
        ]);
    }    

    private function handleUpdateReview($data)
    {
        if (!isset($data['reviewID'], $data['rating'], $data['comment'])) {
            respondError("Missing fields", 400);
        }

        $stmt = $this->conn->prepare("
            UPDATE reviews
            SET rating = ?, comment = ?
            WHERE reviewID = ?
        ");

        $stmt->execute([
            $data['rating'],
            $data['comment'],
            $data['reviewID']
        ]);

        if ($stmt->rowCount() === 0) {
            respondError("Review not found or not updated", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Review updated"
        ]);
    }

    private function handleGetProductReviews($data)
    {
        if (!isset($data['productID'])) {
            respondError("Missing productID", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE productID = ?");
        $stmt->execute([$data['productID']]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$reviews) {
            respondError("No reviews found for this product", 404);
        }

        respondJSON([
            "status" => "success",
            "data" => $reviews
        ]);
    }   

    private function handleDeleteReview($data)
    {
        if (!isset($data['reviewID'])) {
            respondError("Missing reviewID", 400);
        }

        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE reviewID = ?");
        $stmt->execute([$data['reviewID']]);

        if ($stmt->rowCount() === 0) {
            respondError("Review not found", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Review deleted"
        ]);
    }

    private function handleRespondToReview($data)
    {
        if (!isset($data['reviewID'], $data['response'], $data['retailerID'])) {
            respondError("Missing fields", 400);
        }

        $stmt = $this->conn->prepare("
            UPDATE reviews
            SET response = ?, responseDate = NOW(), retailerID = ?
            WHERE reviewID = ?
        ");

        $stmt->execute([
            $data['response'],
            $data['retailerID'],
            $data['reviewID']
        ]);

        if ($stmt->rowCount() === 0) {
            respondError("Review not found or response not updated", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Response added/updated"
        ]);
    }

    private function handleDeleteResponse($data)
    {
        if (!isset($data['reviewID'], $data['retailerID'])) {
            respondError("Missing reviewID or retailerID", 400);
        }

        // Only allow deletion if retailer_id matches the one in review
        $stmt = $this->conn->prepare("
            UPDATE reviews
            SET response = NULL, responseDate = NULL, retailerID = NULL
            WHERE reviewID = ? AND retailerID = ?
        ");
        $stmt->execute([
            $data['reviewID'],
            $data['retailerID']
        ]);

        if ($stmt->rowCount() === 0) {
            respondError("No matching response found or already deleted", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Retailer response deleted"
        ]);
    }

    //requests
    private function handleGetAllRequests()
    {
        $stmt = $this->conn->prepare("SELECT * FROM requests ORDER BY createdAt DESC");
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Decode JSON payloads
        foreach ($requests as &$request) {
            if (!empty($request['payload'])) {
                $decoded = json_decode($request['payload'], true);
                $request['payload'] = is_array($decoded) ? $decoded : null;
            } else {
                $request['payload'] = null;
            }
        }

        respondJSON([
            "status" => "success",
            "requests" => $requests
        ]);
    }

    private function handleApproveAddRequest(array $data)
    {
        // 1) Validate inputs
        if (!isset($data['requestID'])) {
            respondError("Missing 'requestID'", 400);
        }

        // 2) Fetch the pending ADD request’s payload
        $stmt = $this->conn->prepare("
            SELECT payload
            FROM requests
            WHERE requestID   = ?
            AND requestCode LIKE 'ADD%'
            AND status       = 'pending'
        ");
        $stmt->execute([$data['requestID']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            respondError("Add request not found", 404);
        }

        // 3) Decode payload and validate structure
        $payload = json_decode($row['payload'], true);
        if (!is_array($payload)) {
            respondError("Invalid payload format", 400);
        }

        // 4) Ensure required fields are present
        $required = [
            'retailerID',
            'brandID',
            'categoryID',
            'productName',
            'description',
            'imageURL',
            'specifications',
            'price'
        ];
        foreach ($required as $f) {
            if (!isset($payload[$f]) || $payload[$f] === '' || $payload[$f] === null) {
                respondError("Missing or empty field in payload: $f", 400);
            }
        }

        // 5) Begin transaction
        $this->conn->beginTransaction();
        try {
            // 6) Insert into products
            $sql    = "INSERT INTO products 
                        (productName, description, brandID, categoryID, imageURL, specifications) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $params = [
                $payload['productName'],
                $payload['description'],
                $payload['brandID'],
                $payload['categoryID'],
                $payload['imageURL'],
                $payload['specifications'],
            ];
            error_log("SQL: $sql | PARAMS: " . json_encode($params));
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $newProductID = (int)$this->conn->lastInsertId();

            // 7) Insert into prices
            $sql    = "INSERT INTO prices (retailerID, productID, prices) VALUES (?, ?, ?)";
            $params = [
                $payload['retailerID'],
                $newProductID,
                $payload['price'],
            ];
            error_log("SQL: $sql | PARAMS: " . json_encode($params));
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);

                // 10) Mark the request approved
                $sql    = "UPDATE requests SET status = 'approved', modifiedAt = NOW() WHERE requestID = ?";
                $params = [$data['requestID']];
                error_log("SQL: $sql | PARAMS: " . json_encode($params));
                $stmt = $this->conn->prepare($sql);
                $stmt->execute($params);

                // 11) Commit and respond
                $this->conn->commit();
                respondJSON([
                    "status"  => "success",
                    "message" => "Add request approved and product inserted"
                ]);

        } catch (Exception $e) {
            $this->conn->rollBack();
            respondError("Failed to approve add request: " . $e->getMessage(), 500);
        }
    }


    private function handleApproveDeleteRequest(array $data)
    {
        // 1) Validate inputs
        if (!isset($data['requestID'])) {
            respondError("Missing 'requestID'", 400);
        }

        // 2) Fetch the pending DELETE request
        $stmt = $this->conn->prepare("
            SELECT payload
            FROM requests
            WHERE requestID   = ?
            AND requestCode LIKE 'DEL%'
            AND status       = 'pending'
        ");
        $stmt->execute([$data['requestID']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            respondError("Delete request not found", 404);
        }

        // 3) Decode payload and validate structure
        $payload = json_decode($row['payload'], true);
        if (!is_array($payload)
            || empty($payload['productID'])
            || empty($payload['retailerID'])
        ) {
            respondError("Missing productID or retailerID in payload", 400);
        }
        $productID  = (int)$payload['productID'];

        // 4) Begin transaction
        $this->conn->beginTransaction();
        try {
            // 5) DELETE from prices
            $sql    = "DELETE FROM prices WHERE productID = ?";
            $params = [$productID];
            error_log("SQL: $sql | PARAMS: " . json_encode($params));
            $this->conn->prepare($sql)->execute($params);

            // 8) DELETE from products
            $sql    = "DELETE FROM products WHERE productID = ?";
            $params = [$productID];
            error_log("SQL: $sql | PARAMS: " . json_encode($params));
            $delete = $this->conn->prepare($sql);
            $delete->execute($params);

            if ($delete->rowCount() === 0) {
                // nothing deleted → rollback
                $this->conn->rollBack();
                respondError("Product not found or already deleted", 404);
            }

            // 9) Mark the request approved
            $sql    = "UPDATE requests SET status = 'approved', modifiedAt = NOW() WHERE requestID = ?";
            $params = [$data['requestID']];
            error_log("SQL: $sql | PARAMS: " . json_encode($params));
            $this->conn->prepare($sql)->execute($params);

            // 10) Commit & respond
            $this->conn->commit();
            respondJSON([
                "status"  => "success",
                "message" => "Delete request approved and product deleted"
            ]);

        } catch (Exception $e) {
            $this->conn->rollBack();
            respondError("Failed to approve delete request: " . $e->getMessage(), 500);
        }
    }

    private function handleApproveUpdateRequest(array $data)
    {
        // 1) Validate inputs
        if (!isset($data['requestID'])) {
            respondError("Missing 'requestID'", 400);
        }

        // 2) Fetch the pending UPDATE request
        $stmt = $this->conn->prepare("
            SELECT payload
            FROM requests
            WHERE requestID   = ?
            AND requestCode LIKE 'UPD%'
            AND status       = 'pending'
        ");
        $stmt->execute([$data['requestID']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            respondError("Update request not found", 404);
        }

        // 3) Decode payload and validate structure
        $payload = json_decode($row['payload'], true);
        if (!is_array($payload)) {
            respondError("Invalid payload format", 400);
        }

        // 4) Ensure all required fields are present
        $required = [
            'productID',
            'retailerID',
            'brandID',
            'categoryID',
            'productName',
            'description',
            'imageURL',
            'specifications',
            'price'
        ];
        foreach ($required as $f) {
            if (!isset($payload[$f]) || $payload[$f] === '' || $payload[$f] === null) {
                respondError("Missing or empty field in payload: $f", 400);
            }
        }

        // 5) Begin transaction
        $this->conn->beginTransaction();
        try {
            // 6a) Update products
            $sql    = "
                UPDATE products
                SET productName   = ?,
                    description   = ?,
                    imageURL      = ?,
                    specifications= ?
                WHERE productID     = ?
            ";
            $params = [
                $payload['productName'],
                $payload['description'],
                $payload['brandID'],
                $payload['categoryID'],
                $payload['imageURL'],
                $payload['specifications'],
                $payload['productID']
            ];
            error_log("SQL: $sql | PARAMS: " . json_encode($params));
            $this->conn->prepare($sql)->execute($params);

            // 6b) Update prices
            $sql    = "
                UPDATE prices
                SET prices    = ?
                WHERE productID = ?
                AND retailerID= ?
            ";
            $params = [
                $payload['price'],
                $payload['productID'],
                $payload['retailerID']
            ];
            error_log("SQL: $sql | PARAMS: " . json_encode($params));
            $this->conn->prepare($sql)->execute($params);

            // 7) Mark the request approved
            $sql    = "
                UPDATE requests
                SET status     = 'approved',
                    modifiedAt = NOW()
                WHERE requestID = ?
            ";
            $params = [$data['requestID']];
            error_log("SQL: $sql | PARAMS: " . json_encode($params));
            $this->conn->prepare($sql)->execute($params);

            // 8) Commit & respond
            $this->conn->commit();
            respondJSON([
                "status"  => "success",
                "message" => "Update request approved and product updated"
            ]);

        } catch (Exception $e) {
            $this->conn->rollBack();
            respondError("Failed to approve update request: " . $e->getMessage(), 500);
        }
    }

    
    private function handleDeclineRequest($data)
    {
        if (!isset($data['requestID'])) {
            respondError("Missing 'requestID'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE requestID = ?");
        $stmt->execute([$data['requestID']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            respondError("Request not found", 404);
        }

        $stmt = $this->conn->prepare("
            UPDATE requests SET status = 'declined', modifiedAt = NOW()
            WHERE requestID = ?
        ");
        $stmt->execute([$data['requestID']]);

        respondJSON([
            "status" => "success",
            "message" => "Request declined successfully"
        ]);
    }

    private function handleDeleteRequest($data)
    {
        // 1) You only need requestID from the client
        if (!isset($data['requestID'])) {
            respondError("Missing 'requestID'", 400);
        }

        // 2) Load that request row
        $stmt = $this->conn->prepare("SELECT payload FROM requests WHERE requestID = ?");
        $stmt->execute([$data['requestID']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            respondError("Request not found", 404);
        }

        // 3) Decode its JSON and pull retailerID
        $payload = json_decode($row['payload'], true);
        $retailerID = $payload['retailerID'] ?? null;
        if ($retailerID === null) {
            respondError("No retailerID in request payload", 400);
        }

        // 4) Now perform the DELETE
        $stmt = $this->conn->prepare("
        DELETE FROM requests
        WHERE requestID = ?
            AND JSON_UNQUOTE(JSON_EXTRACT(payload, '$.retailerID')) = ?
            AND status = 'pending'
        ");
        $stmt->execute([$data['requestID'], $retailerID]);

        if ($stmt->rowCount() === 0) {
            respondError("Request not found or cannot be deleted", 404);
        }

        respondJSON([
            "status"  => "success",
            "message" => "Request deleted successfully"
        ]);
    }

    private function handleEditRequest($data)
    {
        // 1) Only requestID is required
        if (!isset($data['requestID'])) {
            respondError("Missing 'requestID'", 400);
        }

        // 2) Load the existing row’s payload
        $stmt = $this->conn->prepare("
            SELECT payload, status
            FROM requests
            WHERE requestID = ?
        ");
        $stmt->execute([$data['requestID']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            respondError("Request not found", 404);
        }

        // 3) Decode its JSON and pull retailerID
        $existingPayload = json_decode($row['payload'], true);
        $retailerID = $existingPayload['retailerID'] ?? null;
        if ($retailerID === null) {
            respondError("No retailerID in request payload", 400);
        }

        // 4) Ensure it's still pending
        if ($row['status'] !== 'pending') {
            respondError("Request is not editable", 400);
        }

        // 5) Ensure valid new payload 
        if (empty($data['payload']) || !is_array($data['payload'])) {
            respondError("Missing or invalid 'payload'", 400);
        }

        // 6) Re-encode the new payload (merging in the retailerID to preserve it)
        $newPayload = $data['payload'];
        $newPayload['retailerID'] = $retailerID;
        $payloadJson     = json_encode($newPayload);

        // 7) Perform the UPDATE
        $update = $this->conn->prepare("
            UPDATE requests
            SET payload    = ?,
                modifiedAt = NOW()
            WHERE requestID = ?
            AND JSON_UNQUOTE(JSON_EXTRACT(payload, '$.retailerID')) = ?
            AND status = 'pending' 
        "); //AND requestCode LIKE 'UPD%'
        $update->execute([
            $payloadJson,
            $data['requestID'],
            $retailerID
        ]);

        if ($update->rowCount() === 0) {
            respondError("Request not found or cannot be updated", 404);
        }

        respondJSON([
            "status"  => "success",
            "message" => "Request updated successfully"
        ]);
    }

    private function handleAddProductRequest(array $data)
    {
        // 1) Validate inputs
        $required = ['productName','description','categoryName','brandName','price','imageURL','retailerID'];
        foreach ($required as $f) {
            if (!isset($data[$f]) || $data[$f] === "") {
                respondError("Missing field: $f", 400);
            }
        }

        // 2) Lookup brandID
        $stmt = $this->conn->prepare("SELECT brandID FROM brands WHERE brandName = ?");
        $stmt->execute([$data['brandName']]);
        $brandID = $stmt->fetchColumn();
        if (!$brandID) {
            respondError("Unknown brandName: {$data['brandName']}", 400);
        }

        // 3) Lookup categoryID
        $stmt = $this->conn->prepare("SELECT categoryID FROM categories WHERE categoryName = ?");
        $stmt->execute([$data['categoryName']]);
        $categoryID = $stmt->fetchColumn();
        if (!$categoryID) {
            respondError("Unknown categoryName: {$data['categoryName']}", 400);
        }

        // 4) Lookup (or generate) productID
        $stmt = $this->conn->prepare("SELECT productID FROM products WHERE productName = ?");
        $stmt->execute([$data['productName']]);
        $productID = $stmt->fetchColumn();
        if (!$productID) {
            // Let MySQL auto‐generate a new productID later,
            // or you could generate one here if your schema requires it.
            $productID = null;
        }

        // 5) Determine next requestID and code
        $nextId = (int)$this->conn
            ->query("SELECT COALESCE(MAX(requestID),0)+1 FROM requests")
            ->fetchColumn();
        $requestCode = sprintf("ADD_%03d", $nextId);

        // 6) Build the JSON payload (including retailerID)
        $payloadArr = [
            'retailerID'   => (int)$data['retailerID'],
            'productName'  => $data['productName'],
            'description'  => $data['description'],
            'brandID'      => (int)$brandID,
            'categoryID'   => (int)$categoryID,
            'price'        => (float)$data['price'],
            'imageURL'     => $data['imageURL'],
            'productID'    => $productID !== null ? (int)$productID : null,
        ];
        $payloadJson = json_encode($payloadArr);

        // 7) Insert the request (no 'retailerID' column in requests!)
        $stmt = $this->conn->prepare("
            INSERT INTO requests 
                (requestID, requestCode, payload, status, createdAt)
            VALUES 
                (?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([
            $nextId,
            $requestCode,
            $payloadJson
        ]);

        respondJSON([
            'status'  => 'success',
            'message' => "Add product request #{$nextId} submitted"
        ]);
    }

    private function handleGetAllRetailerRequests($data)
    {
        if (!isset($data['retailerID'])) {
            respondError("Missing 'retailerID'", 400);
        }

        // Pull retailerID out of the JSON payload
        $stmt = $this->conn->prepare("
            SELECT * 
            FROM requests 
            WHERE JSON_UNQUOTE(JSON_EXTRACT(payload, '$.retailerID')) = ?
        ORDER BY createdAt DESC
        ");
        $stmt->execute([$data['retailerID']]);
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Decode JSON payloads
        foreach ($requests as &$request) {
            $request['payload'] = !empty($request['payload'])
                ? (json_decode($request['payload'], true) ?: null)
                : null;
        }

        respondJSON([
            "status"   => "success",
            "requests" => $requests
        ]);
    }

    private function handleDeleteProductRequest(array $data)
    {
        // 1) Validate inputs
        if (!isset($data['productID'], $data['retailerID'])) {
            respondError("Missing productID or retailerID", 400);
        }

        // 2) Build the JSON payload
        $payload = json_encode([
            'productID'  => (int)$data['productID'],
            'retailerID' => (int)$data['retailerID']
        ]);

        // 3) Compute next requestID and the requestCode (e.g. DEL_001, DEL_002, etc.)
        $nextId = (int)$this->conn
            ->query("SELECT COALESCE(MAX(requestID),0)+1 FROM requests")
            ->fetchColumn();
        $requestCode = sprintf("DEL_%03d", $nextId);

        // 4) Insert the delete request
        $stmt = $this->conn->prepare("
            INSERT INTO requests
            (requestID, requestCode, payload, status, createdAt)
            VALUES
            (?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([
            $nextId,
            $requestCode,
            $payload
        ]);

        respondJSON([
            "status"  => "success",
            "message" => "Delete product request submitted to admin",
            "requestID"   => $nextId,
            "requestCode" => $requestCode
        ]);
    }

    private function handleUpdateProductRequest(array $data)
    {
        // 1) Required parameters
        foreach (['productID','retailerID'] as $f) {
            if (!isset($data[$f])) {
                respondError("Missing field: $f", 400);
            }
        }

        // 2) Build the payload with only the fields that were sent
        $payload = ['productID' => (int)$data['productID']];
        foreach (['productName','description','brandID','categoryID','price','imageURL'] as $f) {
            if (isset($data[$f]) && $data[$f] !== '') {
                $payload[$f] = $data[$f];
            }
        }
        if (count($payload) === 1) {
            respondError("No fields to update", 400);
        }

        // 3) Determine next requestID & requestCode
        $nextId = (int)$this->conn
            ->query("SELECT COALESCE(MAX(requestID),0)+1 FROM requests")
            ->fetchColumn();
        $requestCode = sprintf("UPD_%03d", $nextId);

        // 4) Insert the update request
        $stmt = $this->conn->prepare("
            INSERT INTO requests
                (requestID, requestCode, payload, status, createdAt)
            VALUES
                (?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([
            $nextId,
            $requestCode,
            json_encode($payload),
        ]);


        respondJSON([
            "status"      => "success",
            "message"     => "Update product request submitted to admin",
            "requestID"   => $nextId,
            "requestCode" => $requestCode,
        ]);
    }


}

    // Instantiate and run the API
    $api = API::getInstance($conn);
    $api->handleRequest();

?>