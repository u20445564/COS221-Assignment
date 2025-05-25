<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once("config.php");

class API 
{
    private static $instance = null;
    private $conn;

    private function __construct($conn) 
    {
        $this->conn = $conn;
    }

    public static function getInstance($conn) 
    {
        if (self::$instance === null) 
        {
            self::$instance = new API($conn);
        }
        return self::$instance;
    }

    public function handleRequest() 
    {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (!is_array($data) || !isset($data['type'])) 
        {
            respondError("Missing or invalid request type", 400);
        }

        switch ($data['type']) 
        {
            //REGISTER, LOGIN AND LOGOUT
            case 'Register':
                $this->handleRegister($data);
                break;
            case 'Login':
                $this->handleLogin($data);
                break;
            case 'Logout':
                $this->handleLogout($data);
                break;

            //PRODUCTS 
            //User and Admin
            case 'GetAllProducts':
                $this->handleGetAllProducts($data);
                break;

            //Retailer 
            case 'GetAllRetailerProducts':
                $this->handleGetAllRetailerProducts($data);
                break;

            // REVIEWS 
            case 'GetAllReviewsAndResponses':
                $this->handleGetAllReviewsAndResponses($data);
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
            case 'ResponseToReview':
                $this->handleRespondToReview($data);
                break;
            case 'DeleteResponse':
                $this->handleDeleteRetailerResponse($data);
                break; 
            case 'ProductReview':
                $this->handleGetProductReviews($data);
                break;

            //REQUESTS
            //Admin
            case 'GetAllRequests':
                $this->handleGetAllRequests($data);
                break;
            case 'ApproveAddRequest':
                $this->handleApproveAddRequest($data);
                break;
            case 'AllowUpdateRequest':
                $this->handleAllowUpdateRequest($data);
                break;
            case 'ApproveDeleteRequest':
                $this->handleApproveDeleteRequest($data);
                break;
            case 'DeclineRequest':
                $this->handleDeclineRequest($data);
                break;

            //Retailer
            case 'GetRetailerRequests':
                $this->handleGetAllRetailerRequests($data);
                break;

            //CRUD Operations for Products. However these are just requests 
            case 'AddProduct':
                $this->handleAddProductRequest($data);
                break;
            case 'UpdateProduct':
                $this->handleUpdateProductRequest($data);
                break;
            case 'DeleteProduct':
                $this->handleDeleteProductRequest($data);
                break;

            //CRUD Operations for Requests
            case 'EditReqiest':
                $this->handleEditRequest($data);
                break;
            case 'DeleteRequest':
                $this->handleDeleteRequest($data);
                break;
            
            default:
                respondError("Unsupported request type", 400);
        }
    }

    //Register
    private function handleRegister($data)
    {
        //Email is login username 
        $userType = trim($data['user_type'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            respondError("Invalid email format", 400);
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password)) 
        {
            respondError("Password must be at least 8 characters and include uppercase, lowercase, number, and symbol", 400);
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
            $stmt = $this->conn->prepare("INSERT INTO userbase (password, email, api_key) VALUES (?, ?, ?)");
            $stmt->execute([$email, $hashedPassword, $apiKey]);
            $userID = $this->conn->lastInsertId();

            // Insert into user table
            $stmt = $this->conn->prepare("INSERT INTO user (userID, username, userFname, userSname) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userID, $username, $name, $surname]);

            respondJSON([
                "status" => "success",
                "message" => "User registered successfully",
                "data" => [
                    "userID" => $userID,
                    "email" => $email,
                    "api_key" => $apiKey
                ]
            ]);
        } 
        elseif ($userType === 'retailer') 
        {
            $retailerName = trim($data['retailer_name'] ?? '');
            $phoneNumber = trim($data['phone_number'] ?? '');

            if ($retailerName === '' || $phoneNumber === '') {
                respondError("Missing retailer fields", 400);
            }

            // Insert into userbase
            $stmt = $this->conn->prepare("INSERT INTO userbase (email, password, api_key, phone_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([$email, $hashedPassword, $apiKey, $phoneNumber]);
            $userID = $this->conn->lastInsertId();

            // Insert into retailer
            $stmt = $this->conn->prepare("INSERT INTO retailer (retailerName) VALUES (?)");
            $stmt->execute([$retailerName]);
            $retailerID = $this->conn->lastInsertId();

            // Generate retailerCode
            $retailerCode = 'R' . $retailerID;

            // Insert into retaileruser
            $stmt = $this->conn->prepare("INSERT INTO retaileruser (userID, retailerID, retailerCode) VALUES (?, ?, ?)");
            $stmt->execute([$userID, $retailerID, $retailerCode]);

            respondJSON([
                "status" => "success",
                "message" => "Retailer registered successfully",
                "data" => [
                    "userID" => $userID,
                    "retailerID" => $retailerID,
                    "retailerCode" => $retailerCode,
                    "email" => $email,
                    "api_key" => $apiKey
                ]
            ]);

        } 
        else 
        {
            respondError("Invalid user type", 400);
        }

    }

    //Login 
    private function handleLogin($data)
    {
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($email === '' || $password === '') 
        {
            respondError("Missing email or password", 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            respondError("Invalid email format", 400);
        }

        //Get user from userbase
        $stmt = $this->conn->prepare("SELECT userID, password, api_key FROM userbase WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) 
        {
            respondError("Invalid email or password", 401);
        }

        $userID = $user['userID'];
        $apiKey = $user['api_key'];
        $userType = 'unknown';
        $userInfo = [];

        // Try normal user
        $stmt = $this->conn->prepare("SELECT username, userFname, userSname FROM user WHERE userID = ?");
        $stmt->execute([$userID]);
        $userResult = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userResult) 
        {
            $userType = 'user';
            $userInfo = $userResult;
        } 
        else 
        {
            // Try retailer
            $stmt = $this->conn->prepare("
                SELECT ru.retailerCode, r.retailerName 
                FROM retaileruser ru
                JOIN retailer r ON ru.retailerID = r.retailerID 
                WHERE ru.userID = ?
            ");
            $stmt->execute([$userID]);
            $retailerResult = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($retailerResult) 
            {
                $userType = 'retailer';
                $userInfo = $retailerResult;
            } 
            else 
            {
                // Try admin
                $stmt = $this->conn->prepare("SELECT adminID, admin_name FROM admin WHERE userID = ?");
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
                "api_key" => $apiKey,
                "user_type" => $userType,
                "info" => $userInfo
            ]
        ]);
    }

    //Logout 
    private function handleLogout($data)
    {
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

    //======== PRODUCTS ========//
    //Get all products 
        private function handleGetAllProducts($data)
    {
        $params = [];

        $returnFields = isset($data['return']) && is_array($data['return']) && count($data['return']) > 0
            ? implode(", ", array_map(function($f) { return "p." . $f; }, $data['return']))
            : "p.product_id, p.product_name, p.description, p.imageURL, p.specification, c.category_name, b.brand_name, MIN(cp.price) AS lowest_price";

        $filters = $this->buildProductFiltersFromSearch($data['search'] ?? [], $params);

        $whereClause = count($filters) > 0 ? "WHERE " . implode(" AND ", $filters) : "";

        $sortClause = $this->buildSortClause([
            'sort_by' => $data['sort'] ?? '',
            'sort_order' => $data['order'] ?? '',
        ]);

        $limitClause = (isset($data['limit']) && is_numeric($data['limit'])) ? "LIMIT " . intval($data['limit']) : "";

        $sql = "
            SELECT $returnFields
            FROM products p
            LEFT JOIN productsCategory pc ON p.product_id = pc.productID
            LEFT JOIN category c ON pc.categoryID = c.categoryID
            LEFT JOIN productsBrand pb ON p.product_id = pb.productID
            LEFT JOIN brand b ON pb.brandID = b.brandID
            LEFT JOIN comparisons cp ON p.product_id = cp.productID
            $whereClause
            GROUP BY p.product_id
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
                SELECT r.retailerName, cp.price 
                FROM comparisons cp
                JOIN retailer r ON cp.retailerID = r.retailerID
                WHERE cp.productID = ?
            ");
            $pricesStmt->execute([$product['product_id']]);
            $product['retailer_prices'] = $pricesStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $products
        ]);
    }

    //Helper functions for get all products

    // Sort products
    private function buildSortClause($data)
    {
        $allowedSortFields = ['product_name', 'lowest_price', 'category_name', 'brand_name', 'department', 'country_of_origin'];
        $allowedSortOrders = ['ASC', 'DESC'];

        $sortBy = in_array($data['sort_by'] ?? '', $allowedSortFields) ? $data['sort_by'] : 'product_name';
        $sortOrder = in_array(strtoupper($data['sort_order'] ?? ''), $allowedSortOrders) ? strtoupper($data['sort_order']) : 'ASC';

        return "ORDER BY $sortBy $sortOrder";
    }

    // Filter products
    private function buildProductFiltersFromSearch($search, &$params)
    {
        $filters = [];

        foreach ($search as $key => $value) {
            if ($value === "") continue;

            switch ($key) {
                case 'category':
                    $filters[] = "c.category_name = ?";
                    break;
                case 'brand':
                    $filters[] = "b.brand_name = ?";
                    break;
                case 'min_price':
                    $filters[] = "cp.price >= ?";
                    break;
                case 'max_price':
                    $filters[] = "cp.price <= ?";
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

    //RETAILER 
    //Get retailer products 
    private function handleGetAllRetailerProducts($data)
    {
        $params = [];

        // Ensure retailerID is present
        if (empty($data['retailer_id'])) 
        {
            respondError("Missing retailer_id", 400);
        }
        $retailerID = intval($data['retailer_id']);

        // Handle which columns to return
        $returnFields = isset($data['return']) && is_array($data['return']) && count($data['return']) > 0
            ? implode(", ", array_map(function ($f) 
            {
                return "p." . $f;
            }, $data['return']))
            : "p.product_id, p.product_name, p.description, p.imageURL, p.specification, c.category_name, b.brand_name, cp.price";

        // Filters from 'search'
        $filters = $this->buildProductFiltersFromSearch($data['search'] ?? [], $params);

        // Always filter by retailer_id
        $filters[] = "cp.retailerID = ?";
        $params[] = $retailerID;

        $whereClause = "WHERE " . implode(" AND ", $filters);

        // Sorting and limiting
        $sortClause = $this->buildSortClause([
            'sort_by' => $data['sort'] ?? '',
            'sort_order' => $data['order'] ?? '',
        ]);
        $limitClause = (isset($data['limit']) && is_numeric($data['limit'])) ? "LIMIT " . intval($data['limit']) : "";

        // Final SQL
        $sql = "
            SELECT $returnFields
            FROM products p
            LEFT JOIN productsCategory pc ON p.product_id = pc.productID
            LEFT JOIN category c ON pc.categoryID = c.categoryID
            LEFT JOIN productsBrand pb ON p.product_id = pb.productID
            LEFT JOIN brand b ON pb.brandID = b.brandID
            JOIN comparisons cp ON p.product_id = cp.productID
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

    //======== REVIEWS ========//
    //Get all reviews for admin to see both customer and user reviews 
    private function handleGetAllReviewsAndResponses($data)
    {
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

    //Add review
    private function handleAddReview($data)
    {
        if (!isset($data['product_id'], $data['user_id'], $data['rating'], $data['comment'])) {
            respondError("Missing required fields", 400);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO reviews (product_id, user_id, rating, comment, review_date)
            VALUES (?, ?, ?, ?, NOW())
        ");

        $success = $stmt->execute([
            $data['product_id'],
            $data['user_id'],
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

    //Update review
    private function handleUpdateReview($data)
    {
        if (!isset($data['review_id'], $data['rating'], $data['comment'])) {
            respondError("Missing fields", 400);
        }

        $stmt = $this->conn->prepare("
            UPDATE reviews
            SET rating = ?, comment = ?
            WHERE review_id = ?
        ");

        $stmt->execute([
            $data['rating'],
            $data['comment'],
            $data['review_id']
        ]);

        if ($stmt->rowCount() === 0) {
            respondError("Review not found or not updated", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Review updated"
        ]);
    }

    //Delete review (user and admin)
    private function handleDeleteReview($data)
    {
        if (!isset($data['review_id'])) {
            respondError("Missing review_id", 400);
        }

        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE review_id = ?");
        $stmt->execute([$data['review_id']]);

        if ($stmt->rowCount() === 0) {
            respondError("Review not found", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Review deleted"
        ]);
    }

    //RETAILER
    //Add and Update response
    private function handleRespondToReview($data)
    {
        if (!isset($data['review_id'], $data['response_text'], $data['retailer_id'])) {
            respondError("Missing fields", 400);
        }

        $stmt = $this->conn->prepare("
            UPDATE reviews
            SET response_text = ?, response_date = NOW(), retailer_id = ?
            WHERE review_id = ?
        ");

        $stmt->execute([
            $data['response_text'],
            $data['retailer_id'],
            $data['review_id']
        ]);

        if ($stmt->rowCount() === 0) {
            respondError("Review not found or response not updated", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Response added/updated"
        ]);
    }

    //Dekete response
    private function handleDeleteRetailerResponse($data)
    {
        if (!isset($data['review_id'], $data['retailer_id'])) {
            respondError("Missing review_id or retailer_id", 400);
        }

        // Only allow deletion if retailer_id matches the one in review
        $stmt = $this->conn->prepare("
            UPDATE reviews
            SET response_text = NULL, response_date = NULL, retailer_id = NULL
            WHERE review_id = ? AND retailer_id = ?
        ");
        $stmt->execute([
            $data['review_id'],
            $data['retailer_id']
        ]);

        if ($stmt->rowCount() === 0) {
            respondError("No matching response found or already deleted", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Retailer response deleted"
        ]);
    }

    private function handleGetProductReviews($data)
    {
        if (!isset($data['product_id'])) {
            respondError("Missing product_id", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE product_id = ?");
        $stmt->execute([$data['product_id']]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$reviews) {
            respondError("No reviews found for this product", 404);
        }

        respondJSON([
            "status" => "success",
            "data" => $reviews
        ]);
    }

    //======== REQUESTS ========//
    //Admin
    //Get all requests
    private function handleGetAllRequests()
    {
        $stmt = $this->conn->prepare("SELECT * FROM requests ORDER BY created_at DESC");
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

    //Add (Insert) requests
    private function handleApproveAddRequest($data)
    {
        if (!isset($data['request_id'])) {
            respondError("Missing 'request_id'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE requestID = ? AND requestCode LIKE 'ADD%' AND status = 'pending'");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            respondError("Add request not found", 404);
        }

        $payload = json_decode($request['payload'], true);

        $required = ['productID', 'retailerID', 'brandID', 'categoryID', 'product_name', 'description', 'imageURL', 'specifications', 'price'];
        foreach ($required as $field) {
            if (empty($payload[$field])) {
                respondError("Missing field in payload: $field", 400);
            }
        }

        $this->conn->beginTransaction();
        try {
            $this->conn->prepare("
                INSERT INTO products (productID, product_name, description, imageURL, specifications)
                VALUES (?, ?, ?, ?, ?)
            ")->execute([
                $payload['productID'], $payload['product_name'], $payload['description'],
                $payload['imageURL'], $payload['specifications']
            ]);

            $this->conn->prepare("
                INSERT INTO comparisons (retailerID, productID, price)
                VALUES (?, ?, ?)
            ")->execute([
                $payload['retailerID'], $payload['productID'], $payload['price']
            ]);

            $this->conn->prepare("
                INSERT INTO productcategory (productID, categoryID)
                VALUES (?, ?)
            ")->execute([
                $payload['productID'], $payload['categoryID']
            ]);

            $this->conn->prepare("
                INSERT INTO productbrand (productID, brandID)
                VALUES (?, ?)
            ")->execute([
                $payload['productID'], $payload['brandID']
            ]);

            $this->conn->prepare("
                UPDATE requests SET status = 'approved', modified_at = NOW()
                WHERE requestID = ?
            ")->execute([$data['request_id']]);

            $this->conn->commit();

            respondJSON([
                "status" => "success",
                "message" => "Add request approved and product inserted"
            ]);
        } catch (Exception $e) {
            $this->conn->rollBack();
            respondError("Failed to approve add request: " . $e->getMessage(), 500);
        }
    }

    //Update (Accept changes) requests
    private function handleAllowUpdateRequest($data)
    {
        if (!isset($data['request_id'])) {
            respondError("Missing 'request_id'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE requestID = ? AND requestCode LIKE 'UPD%' AND status = 'pending'");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            respondError("Update request not found", 404);
        }

        $payload = json_decode($request['payload'], true);

        $required = ['productID', 'retailerID', 'brandID', 'categoryID', 'product_name', 'description', 'imageURL', 'specifications', 'price'];
        foreach ($required as $field) {
            if (empty($payload[$field])) {
                respondError("Missing field in payload: $field", 400);
            }
        }

        $this->conn->beginTransaction();
        try {
            $this->conn->prepare("
                UPDATE products
                SET product_name = ?, description = ?, imageURL = ?, specifications = ?
                WHERE productID = ?
            ")->execute([
                $payload['product_name'], $payload['description'], $payload['imageURL'],
                $payload['specifications'], $payload['productID']
            ]);

            $this->conn->prepare("
                UPDATE comparisons
                SET price = ?
                WHERE productID = ? AND retailerID = ?
            ")->execute([
                $payload['price'], $payload['productID'], $payload['retailerID']
            ]);

            $this->conn->prepare("
                UPDATE productbrand
                SET brandID = ?
                WHERE productID = ?
            ")->execute([
                $payload['brandID'], $payload['productID']
            ]);

            $this->conn->prepare("
                UPDATE productcategory
                SET categoryID = ?
                WHERE productID = ?
            ")->execute([
                $payload['categoryID'], $payload['productID']
            ]);

            $this->conn->prepare("
                UPDATE requests SET status = 'approved', modified_at = NOW()
                WHERE requestID = ?
            ")->execute([$data['request_id']]);

            $this->conn->commit();

            respondJSON([
                "status" => "success",
                "message" => "Update request approved and product updated"
            ]);
        } catch (Exception $e) {
            $this->conn->rollBack();
            respondError("Failed to approve update request: " . $e->getMessage(), 500);
        }
    }

    //Delete Products 
    private function handleApproveDeleteRequest($data)
    {
        if (!isset($data['request_id'])) {
            respondError("Missing 'request_id'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE requestID = ? AND requestCode LIKE 'DEL%' AND status = 'pending'");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            respondError("Delete request not found", 404);
        }

        $payload = json_decode($request['payload'], true);

        if (empty($payload['productID']) || empty($payload['retailerID'])) {
            respondError("Missing productID or retailerID in payload", 400);
        }

        $productID = $payload['productID'];

        $this->conn->beginTransaction();
        try {
            $this->conn->prepare("DELETE FROM comparisons WHERE productID = ?")->execute([$productID]);
            $this->conn->prepare("DELETE FROM productbrand WHERE productID = ?")->execute([$productID]);
            $this->conn->prepare("DELETE FROM productcategory WHERE productID = ?")->execute([$productID]);
            $delete = $this->conn->prepare("DELETE FROM products WHERE productID = ?");
            $delete->execute([$productID]);

            if ($delete->rowCount() === 0) {
                $this->conn->rollBack();
                respondError("Product not found or already deleted", 404);
            }

            $this->conn->prepare("
                UPDATE requests SET status = 'approved', modified_at = NOW()
                WHERE requestID = ?
            ")->execute([$data['request_id']]);

            $this->conn->commit();

            respondJSON([
                "status" => "success",
                "message" => "Delete request approved and product deleted"
            ]);
        } catch (Exception $e) {
            $this->conn->rollBack();
            respondError("Failed to approve delete request: " . $e->getMessage(), 500);
        }
    }

    //(Decline) requests 
    private function handleDeclineRequest($data)
    {
        if (!isset($data['request_id'])) {
            respondError("Missing 'request_id'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE requestID = ?");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            respondError("Request not found", 404);
        }

        $stmt = $this->conn->prepare("
            UPDATE requests SET status = 'declined', modified_at = NOW()
            WHERE requestID = ?
        ");
        $stmt->execute([$data['request_id']]);

        respondJSON([
            "status" => "success",
            "message" => "Request declined successfully"
        ]);
    }

    private function handleGetAllRetailerRequests($data)
    {
        if (!isset($data['retailer_id'])) {
            respondError("Missing 'retailer_id'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE retailerID = ? ORDER BY created_at DESC");
        $stmt->execute([$data['retailer_id']]);
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Decode JSON payloads if available
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

     // CRUD OPERATIONS
    //Add Product Request
    private function handleAddProductRequest($data)
    {
        $requiredFields = ['product_name', 'description', 'brandID', 'categoryID', 'price', 'imageURL', 'retailerID'];
        foreach ($requiredFields as $field) 
        {
            if (!isset($data[$field]) || $data[$field] === "") 
            {
                respondError("Missing field: $field", 400);
            }
        }

        $payload = json_encode([
            'product_name' => $data['product_name'],
            'description' => $data['description'],
            'brandID' => (int)$data['brandID'],
            'categoryID' => (int)$data['categoryID'],
            'price' => (float)$data['price'],
            'imageURL' => $data['imageURL']
        ]);

        $stmt = $this->conn->prepare("
            INSERT INTO requests (retailerID, requestCode, payload, status, created_at)
            VALUES (?, 'ADD_PRODUCT', ?, 'pending', NOW())
        ");
        $stmt->execute([$data['retailerID'], $payload]);

        respondJSON([
            "status" => "success",
            "message" => "Add product request submitted to admin"
        ]);
    }

    //Update Product Request
    private function handleUpdateProductRequest($data)
    {
        $requiredFields = ['productID', 'retailerID'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                respondError("Missing field: $field", 400);
            }
        }

        $payload = [
            'productID' => (int)$data['productID']
        ];

        foreach (['product_name', 'description', 'brandID', 'categoryID', 'price', 'imageURL'] as $field) 
        {
            if (isset($data[$field]) && $data[$field] !== "") 
            {
                $payload[$field] = $data[$field];
            }
        }

        if (count($payload) === 1) 
        { // Only has productID
            respondError("No fields to update", 400);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO requests (retailerID, requestCode, payload, status, created_at)
            VALUES (?, 'UPD_PRODUCT', ?, 'pending', NOW())
        ");
        $stmt->execute([$data['retailerID'], json_encode($payload)]);

        respondJSON([
            "status" => "success",
            "message" => "Update product request submitted to admin"
        ]);
    }

    //Delete Product Request
    private function handleDeleteProductRequest($data)
    {
        if (!isset($data['productID'], $data['retailerID'])) {
            respondError("Missing productID or retailerID", 400);
        }

        $payload = json_encode([
            'productID' => (int)$data['productID']
        ]);

        $stmt = $this->conn->prepare("
            INSERT INTO requests (retailerID, requestCode, payload, status, created_at)
            VALUES (?, 'DEL_PRODUCT', ?, 'pending', NOW())
        ");
        $stmt->execute([$data['retailerID'], $payload]);

        respondJSON([
            "status" => "success",
            "message" => "Delete product request submitted to admin"
        ]);
    }

    private function handleEditRequest($data)
    {
        if (!isset($data['request_id'], $data['retailer_id'])) {
            respondError("Missing 'request_id' or 'retailer_id'", 400);
        }

        // Ensure request exists, is owned by retailer, and is still pending
        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE requestID = ? AND retailerID = ? AND status = 'pending'");
        $stmt->execute([$data['request_id'], $data['retailer_id']]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing) {
            respondError("Editable pending request not found", 404);
        }

        // Ensure valid payload
        if (!isset($data['payload']) || !is_array($data['payload'])) {
            respondError("Missing or invalid 'payload'", 400);
        }

        // JSON-encode payload
        $payload = json_encode($data['payload']);

        // Update payload
        $update = $this->conn->prepare("UPDATE requests SET payload = ?, modified_at = NOW() WHERE requestID = ?");
        $update->execute([$payload, $data['request_id']]);

        respondJSON([
            "status" => "success",
            "message" => "Request updated successfully"
        ]);
    }

    private function handleDeleteRequest($data)
    {
        if (!isset($data['request_id'], $data['retailer_id'])) {
            respondError("Missing 'request_id' or 'retailer_id'", 400);
        }

        $stmt = $this->conn->prepare("
            DELETE FROM requests 
            WHERE requestID = ? AND retailerID = ? AND status = 'pending'
        ");
        $stmt->execute([$data['request_id'], $data['retailer_id']]);

        if ($stmt->rowCount() === 0) {
            respondError("Request not found or cannot be deleted", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Request deleted successfully"
        ]);
    }

    private function pendingRequestsFilter($data)
    {
        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE status = 'pending'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function approvedRequestsFilter($data)
    {
        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE status = 'approved'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

 function respondJSON($arr, $code = 200) 
 {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

function respondError($message, $code = 400) 
{
    respondJSON([
        "status" => "error",
        "timestamp" => round(microtime(true) * 1000),
        "error" => $message
    ], $code);
}