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
            case 'GetAllProducts':
                $this->handleGetAllProducts($data);
                break;

            //Retailer
            // product specific 
            case 'AddProduct':
                $this->handleAddProduct($data);
                break;
            case 'UpdateProduct':
                $this->handleUpdateProduct($data);
                break;
            case 'DeleteProduct':
                $this->handleDeleteProduct($data);
                break;

            // REVIEWS 
            //Admin
            case 'GetAllReviewsAndResponses':
                $this->handleGetAllReviewsAndResponses($data);
                break;
            case 'DeleteReviewOrResponse': 
                $this->handleDeleteReviewOrResponse($data);
                break;

            //Customer 
            case 'AddReview':
                $this->handleAddReview($data);
                break;
            case 'UpdateReview':
                $this->handleUpdateReview($data);
                break;
            //case 'DeleteReview': -> may be redundant 
            case 'DeleteReview':
                $this->handleDeleteReview($data);
                break;

            //Retailer 
            case 'GetRetailerResponse':
                $this->handleGetRetailerResponses($data);
                break;
            case 'AddResponse':
                $this->handleAddResponse($data);
                break;
            case 'UpdateResponse':
                $this->handleUpdateResponse($data);
                break;
            //Again may be redundant
            case 'DeleteResponse':
                $this->handleDeleteResponse($data);
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

        // Build filters
        $filters = $this->buildProductFilters($data, $params);

        // Add search clause
        $searchClause = $this->buildSearchClause($data, $params);
        if ($searchClause !== '') 
        {
            $filters[] = $searchClause;
        }

        $whereClause = count($filters) > 0 ? "WHERE " . implode(" AND ", $filters) : "";

        // Sort clause
        $sortClause = $this->buildSortClause($data);

        // Final SQL
        $sql = "SELECT * FROM products $whereClause $sortClause";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$products) 
        {
            respondError("No products found", 404);
        }

        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $products
        ]);
    }

    //Helper functions for get all products

    //Sort products
    private function buildSortClause($data)
    {
        $allowedSortFields = ['product_name', 'price', 'brand', 'category'];
        $allowedSortOrders = ['ASC', 'DESC'];

        $sortBy = in_array($data['sort_by'] ?? '', $allowedSortFields) ? $data['sort_by'] : 'product_name';
        $sortOrder = in_array(strtoupper($data['sort_order'] ?? ''), $allowedSortOrders) ? strtoupper($data['sort_order']) : 'ASC';

        return "ORDER BY $sortBy $sortOrder";
    }

    //Filter products
    private function buildProductFilters($data, &$params)
    {
        $filters = [];

        $filterableFields = ['retailer_id', 'brand', 'category'];

        foreach ($filterableFields as $field) {
            if (isset($data[$field]) && $data[$field] !== "") {
                $filters[] = "$field = ?";
                $params[] = $data[$field];
            }
        }

        return $filters;
    }

    //Search products
    private function buildSearchClause($data, &$params)
    {
        if (isset($data['search']) && trim($data['search']) !== '') {
            $params[] = '%' . $data['search'] . '%';
            return "product_name LIKE ?";
        }
        return '';
    }

    // CRUD OPERATIONS
    //Add (Create) Product
    private function handleAddProduct($data)
    {
        $requiredFields = ['product_name', 'description', 'brand', 'category', 'price', 'image_url', 'retailer_id'];
        foreach ($requiredFields as $field) 
        {
            if (!isset($data[$field]) || $data[$field] === "") 
            {
                respondError("Missing field: $field", 400);
            }
        }

        $payload = json_encode([
            'name' => $data['product_name'],
            'description' => $data['description'],
            'brand' => $data['brand'],
            'category' => $data['category'],
            'price' => $data['price'],
            'image_url' => $data['image_url']
        ]);

        $stmt = $this->conn->prepare("
            INSERT INTO requests (product_name, description, brand, category, price, image_url, retailer_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['product_name'],
            $data['description'],
            $data['brand'],
            $data['category'],
            $data['price'],
            $data['image_url'],
            $data['retailer_id']
        ]);

        respondJSON([
            "status" => "success",
            "message" => "Product added successfully",
            "product_id" => $this->conn->lastInsertId()
        ]);
    }

    //Update Product
    private function handleUpdateProduct($data)
    {
        $requiredFields = ['product_id', 'retailer_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                respondError("Missing field: $field", 400);
            }
        }

        $payload = [
            'product_id' => $data['product_id']
        ];

        foreach (['product_name', 'description', 'brand', 'category', 'price', 'image_url'] as $field) {
            if (isset($data[$field])) {
                $payload[$field] = $data[$field];
            }
        }

        if (count($payload) === 1) { // Only has product_id
            respondError("No fields to update", 400);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO requests (retailer_id, type, payload, status, created_at)
            VALUES (?, 'update', ?, 'pending', NOW())
        ");
        $stmt->execute([$data['retailer_id'], json_encode($payload)]);

        respondJSON([
            "status" => "success",
            "message" => "Update product request submitted to admin"
        ]);
    }

    //Delete Product 
    private function handleDeleteProduct($data)
    {
        if (!isset($data['product_id'], $data['retailer_id'])) {
            respondError("Missing product_id or retailer_id", 400);
        }

        $payload = json_encode(['product_id' => $data['product_id']]);

        $stmt = $this->conn->prepare("
            INSERT INTO requests (retailer_id, type, payload, status, created_at)
            VALUES (?, 'delete', ?, 'pending', NOW())
        ");
        $stmt->execute([$data['retailer_id'], $payload]);

        respondJSON([
            "status" => "success",
            "message" => "Delete product request submitted to admin"
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

    private function handleDeleteReviewOrResponse($data)
    {
        if (!isset($data['type'], $data['id'])) 
        {
            respondError("Missing 'type' or 'id' field", 400);
        }

        $type = strtolower($data['type']);
        $id = intval($data['id']);

        if ($type === 'review') 
        {
            $stmt = $this->conn->prepare("DELETE FROM reviews WHERE review_id = ?");
        } elseif ($type === 'response') 
        {
            $stmt = $this->conn->prepare("DELETE FROM responses WHERE response_id = ?");
        } else 
        {
            respondError("Invalid type: must be 'review' or 'response'", 400);
        }

        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) 
        {
            respondError(ucfirst($type) . " not found", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => ucfirst($type) . " deleted successfully"
        ]);
    }

    //CRUD OPERATIONS REVIEWS
    //CUSTOMER 
    //Get all customer reviews 

    //Add review
    private function handleAddReview($data)
    {
         if (!isset($data['product_id'], $data['user_id'], $data['rating'], $data['comment'])) 
         {
            respondError("Missing required fields", 400);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO reviews (product_id, user_id, rating, comment, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        $success = $stmt->execute([
            $data['product_id'],
            $data['user_id'],
            $data['rating'],
            $data['comment']
        ]);

        if (!$success) 
        {
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
        if (!isset($data['review_id'], $data['rating'], $data['comment'])) 
        {
            respondError("Missing required fields", 400);
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

        if ($stmt->rowCount() === 0) 
        {
            respondError("Review not found or no changes made", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Review updated successfully"
        ]);
    }

    //Delete review (Again might be redundant)
    private function handleDeleteReview($data)
    {
        if (!isset($data['review_id'])) 
        {
            respondError("Missing 'review_id'", 400);
        }

        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE review_id = ?");
        $stmt->execute([$data['review_id']]);

        if ($stmt->rowCount() === 0) 
        {
            respondError("Review not found", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Review deleted successfully"
        ]);
    }


    //RETAILER
    //Get all retailer reviews responses 
    private function handleGetRetailerResponses($data)
    {
        if (!isset($data['retailer_id'])) {
            respondError("Missing 'retailer_id'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM responses WHERE retailer_id = ?");
        $stmt->execute([$data['retailer_id']]);
        $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$responses) {
            respondError("No responses found for this retailer", 404);
        }

        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $responses
        ]);
    }

    //Add response
    private function handleAddResponse($data)
    {
        if (!isset($data['review_id'], $data['retailer_id'], $data['response_text'])) {
            respondError("Missing required fields", 400);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO responses (review_id, retailer_id, response_text, created_at)
            VALUES (?, ?, ?, NOW())
        ");

        $success = $stmt->execute([
            $data['review_id'],
            $data['retailer_id'],
            $data['response_text']
        ]);

        if (!$success) {
            respondError("Failed to add response", 500);
        }

        respondJSON([
            "status" => "success",
            "message" => "Response added successfully",
            "response_id" => $this->conn->lastInsertId()
        ]);
    }

    //Update response
    private function handleUpdateResponse($data)
    {
        if (!isset($data['response_id'], $data['response_text'])) 
        {
            respondError("Missing required fields", 400);
        }

        $stmt = $this->conn->prepare("
            UPDATE responses
            SET response_text = ?
            WHERE response_id = ?
        ");

        $stmt->execute([
            $data['response_text'],
            $data['response_id']
        ]);

        if ($stmt->rowCount() === 0) 
        {
            respondError("Response not found or no changes made", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Response updated successfully"
        ]);
    }

    // Also may be redundant
    private function handleDeleteResponse($data)
    {
        if (!isset($data['response_id'])) 
        {
            respondError("Missing 'response_id'", 400);
        }

        $stmt = $this->conn->prepare("DELETE FROM responses WHERE response_id = ?");
        $stmt->execute([$data['response_id']]);

        if ($stmt->rowCount() === 0) 
        {
            respondError("Response not found", 404);
        }

        respondJSON([
            "status" => "success",
            "message" => "Response deleted successfully"
        ]);
    }

    //======== REQUESTS ========//
    //Admin
    //Get all requests
    private function handleGetAllRequests($data)
    {
        $stmt = $this->conn->prepare("SELECT * FROM requests");
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$requests) 
        {
            respondError("No requests found", 404);
        }

        //JSON Success Response
        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $requests
        ]);
    }

    //Add (Insert) requests
    private function handleApproveAddRequest($data)
    {
        if (!isset($data['request_id'])) 
        {
            respondError("Missing 'request_id'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE request_id = ? AND type = 'add'");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) 
        {
            respondError("Add request not found", 404);
        }

        $payload = json_decode($request['payload'], true);
        $requiredFields = ['name', 'description', 'price', 'category', 'image_url'];
        foreach ($requiredFields as $field) 
        {
            if (!isset($payload[$field])) 
            {
                respondError("Missing field in payload: $field", 400);
            }
        }

        $insert = $this->conn->prepare("
            INSERT INTO products (name, description, price, category, retailer_id, image_url, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        $insert->execute([
            $payload['name'],
            $payload['description'],
            $payload['price'],
            $payload['category'],
            $request['retailer_id'],
            $payload['image_url']
        ]);

        $this->conn->prepare("UPDATE requests SET status = 'approved', updated_at = NOW() WHERE request_id = ?")
            ->execute([$data['request_id']]);

        respondJSON([
            "status" => "success",
            "message" => "Add request approved and product inserted"
        ]);
    }

    //Update (Accept changes) requests
    private function handleAllowUpdateRequest($data)
    {
        if (!isset($data['request_id'])) 
        {
            respondError("Missing 'request_id'", 400);
        }

        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE request_id = ? AND type = 'update'");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) 
        {
            respondError("Update request not found", 404);
        }

        $payload = json_decode($request['payload'], true);
        $requiredFields = ['product_id', 'name', 'description', 'price', 'category', 'image_url'];
        foreach ($requiredFields as $field) 
        {
            if (!isset($payload[$field])) 
            {
                respondError("Missing field in payload: $field", 400);
            }
        }

        $updateProduct = $this->conn->prepare("
            UPDATE products
            SET name = ?, description = ?, price = ?, category = ?, image_url = ?
            WHERE product_id = ? AND retailer_id = ?
        ");

        $updateProduct->execute([
            $payload['name'],
            $payload['description'],
            $payload['price'],
            $payload['category'],
            $payload['image_url'],
            $payload['product_id'],
            $request['retailer_id']
        ]);

        if ($updateProduct->rowCount() === 0) 
        {
            respondError("Product update failed or no changes made", 400);
        }

        $this->conn->prepare("UPDATE requests SET status = 'approved', updated_at = NOW() WHERE request_id = ?")
            ->execute([$data['request_id']]);

        respondJSON([
            "status" => "success",
            "message" => "Update request approved and product updated"
        ]);
    }


    //Delete Products 
    private function handleApproveDeleteRequest($data)
    {
        if (!isset($data['request_id'])) 
        {
            respondError("Missing 'request_id'", 400);
        }

        // Fetch the delete request
        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE request_id = ? AND type = 'delete'");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) 
        {
            respondError("Delete request not found", 404);
        }

        $payload = json_decode($request['payload'], true);
        if (!isset($payload['product_id'])) 
        {
            respondError("Payload must include 'product_id'", 400);
        }

        // Attempt to delete the product
        $delete = $this->conn->prepare("DELETE FROM products WHERE product_id = ? AND retailer_id = ?");
        $delete->execute([$payload['product_id'], $request['retailer_id']]);

        if ($delete->rowCount() === 0) 
        {
            respondError("Product not found or you don't have permission to delete it", 404);
        }

        // Update request status to approved
        $update = $this->conn->prepare("UPDATE requests SET status = 'approved', updated_at = NOW() WHERE request_id = ?");
        $update->execute([$data['request_id']]);

        respondJSON([
            "status" => "success",
            "message" => "Delete request approved and product deleted"
        ]);
    }

    //(Decline) requests 
    private function handleDeclineRequest($data)
    {
        if (!isset($data['request_id'])) 
        {
            respondError("Missing 'request_id'", 400);
        }

        // Check that the request exists
        $stmt = $this->conn->prepare("SELECT * FROM requests WHERE request_id = ?");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) 
        {
            respondError("Request not found", 404);
        }

        // Update request status to declined
        $stmt = $this->conn->prepare("UPDATE requests SET status = 'declined', updated_at = NOW() WHERE request_id = ?");
        $stmt->execute([$data['request_id']]);

        respondJSON([
            "status" => "success",
            "message" => "Request declined successfully"
        ]);
    }

    //Retailer
    private function handleGetAllRetailerRequests($data)
    {
        $filter = isset($data['filter']) ? strtolower($data['filter']) : null;
        $requests = [];

        if ($filter === 'pending')
        {
            $requests = $this->pendingRequestsFilter($data);
        } 
        else if ($filter === 'approved') 
        {
            $requests = $this->approvedRequestsFilter($data);
        } 
        else 
        {
            // No filter or unknown filter — return all
            $stmt = $this->conn->prepare("SELECT * FROM requests");
            $stmt->execute();
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (!$requests) 
        {
            respondError("No requests found", 404);
        }

        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $requests
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