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
            case 'ApproveInsertRequest':
                $this->handleApproveInsertRequest($data);
                break;
            case 'AllowUpdateRequest':
                $this->handleAllowUpdateRequest($data);
                break;
            case 'DeclineRequest':
                $this->handleDeclineRequest($data);
                break;

            //Retailer
            case 'GetRetailerRequests':
                $this->handleGetAllRetailerRequests($data);
                break;
            case 'AddRequest':
                $this->handleAddRequests($data);
                break;

            case 'AddRequest':
                $this->handleUpdateRequest($data);
                break;

            case 'AddRequest':
                $this->handleDeleteRequest($data);
                break;

            default:
                respondError("Unsupported request type", 400);
        }
    }

    //Register
    private function handleRegister($data)
    {
        $username = trim($data['username'] ?? '');
        $name = trim($data['name'] ?? '');
        $surname = trim($data['surname'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $phoneNumber = trim($data['phone_number'] ?? '');
        // $userType = trim($data['user_type'] ?? '');

        if ($username === '' || $name === '' || $surname === '' || $email === '' || $password === '' || $phoneNumber) 
        {
            respondError("Missing fields", 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            respondError("Invalid email format", 400);
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password)) 
        {
            respondError("Password must be at least 8 characters and include uppercase, lowercase, number, and symbol", 400);
        }

        //UserType?????
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE usernmae = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) 
        {
            respondError("Email already exists", 409);
        }

        $stmt = $this->conn->prepare("INSERT INTO users (name, surname, email, password, phone_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $surname, $email, password_hash($password, PASSWORD_BCRYPT), $phoneNumber);
        $stmt->execute();

            
        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => ["username" => $username]
        ]);

    }

    //Login 
    private function handleLogin($data)
    {
        $username = trim($data['username'] ?? '');
        $name = trim($data['name'] ?? '');

        //Ensure username and password are provided
        foreach (['username', 'password'] as $field) 
        {
            if (empty($data[$field])) 
            {
                respondError("Missing field: $field", 400);
            }
        }

        //Fetch user from database with username
        $stmt = $this->conn->prepare("SELECT id, username, name, password FROM users WHERE username = :username AND password = :password");
        $stmt->execute(['username' => $data['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) 
        {
            respondError("Invalid username or password", 401);
        }

        //JSON Success Response
        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => [
                "username" => $username,
                "name" => $name,
                // "user_type" => $user['user_type'],
                // "user_id" => $user['id'],
                //'phoneNumber' => $user['phone_number'],
                // "email" => $user['email'],
            ]
        ]);
    }

    //Logout 
    private function handleLogout($data)
    {
        $username = trim($data['username'] ?? '');

        //Ensure username is provided
        if (empty($username)) 
        {
            respondError("Missing field: username", 400);
        }

        //JSON Success Response
        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => [
                "username" => $username,
                "message" => "Logout successful"
            ]
        ]);
    }

    //======== PRODUCTS ========//
    //Get all products 
    private function handleGetAllProducts($data)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$products) 
        {
            respondError("No products found", 404);
        }

        //JSON Success Response
        respondJSON([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => $products
        ]);
    }

    //Helper functions for get all products

    //Sort products

    //Filter products

    //Search products

    // CRUD OPERATIONS
    //Add (Create) Product
    private function handleAddProduct($data)
    {

    }

    //Update Product
    private function handleUpdateProduct($data)
    {

    }

    //Delete Product 
    private function handleDeleteProduct($data)
    {

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

    }

    //CRUD OPERATIONS REVIEWS
    //CUSTOMER 
    //Get all customer reviews 

    //Add review
    private function handleAddReview($data)
    {

    }

    //Update review
    private function handleUpdateReview($data)
    {

    }

    //Delete review (Again might be redundant)
    private function handleDeleteReview($data)
    {

    }


    //RETAILER
    //Get all retailer reviews responses 

    //Add response
    private function handleAddResponse($data)
    {

    }

    //Update response
    private function handleUpdateResponse($data)
    {

    }

    // Also may be redundant
    private function handleDeleteResponse($data)
    {

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
    private function handleApproveInsertRequest($data)
    {

    }

    //Update (Accept changes) requests
    private function handleAllowUpdateRequest($data)
    {

    }

    //Delete (Decline) requests 
    private function handleDeclineRequest($data)
    {

    }

    //Retailer
    private function handleGetAllRetailerRequests($data)
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

    //Add retailer request
    private function handleAddRequests($data)
    {

    }

    //Update retailer request
    private function handleUpdateRequest($data)
    {

    }
    
    //Delete retailer request 
    private function handleDeleteRequest($data)
    {

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