<?php
declare(strict_types=1);
require_once __DIR__ . '/../classes/Sellers.php';


class SellersController
{
    private Seller $seller;

    public function __construct()
    {
        $this->seller = new Seller();
    }

    public function sanitizeData(array $data): array {
        $data['Name'] = filter_var($data['Name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $data['Address'] = filter_var($data['Address'], FILTER_SANITIZE_SPECIAL_CHARS);
        $data['PhoneNumber'] = filter_var($data['PhoneNumber'], FILTER_SANITIZE_SPECIAL_CHARS);
        

        return $data;
    }

    public function validateData(array $data): array {
        $errors = [];

        if (!isset($data['Name']) || strlen($data['Name']) < 1) {
            $errors[] = 'Name is required';
        }

        if (filter_var($data['Name'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-zåäö A-ZÅÄÖ]*$/']]) === false) {
            $errors[] = 'Name can only contain letters and spaces';
        }

        if (!isset($data['Address']) || strlen($data['Address']) < 1) {
            $errors[] = 'Address is required';
        }

        if (filter_var($data['Address'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-zåäö A-ZÅÄÖ0-9\-.]*$/']]) === false) {
            $errors[] = 'Address can only contain letters, numbers, spaces and dashes';
        }

        if (!isset($data['PhoneNumber']) || strlen($data['PhoneNumber']) < 1) {
            $errors[] = 'PhoneNumber is required';
        }

        if (filter_var($data['PhoneNumber'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[ 0-9\-]*$/']]) === false) {
            $errors[] = 'PhoneNumber can only numbers, spaces and dashes';
        }

        

        
        return $errors;
    }

    public function processRequest(?int $ID = null): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $data = $this->seller->getData($ID);
                echo json_encode($data);
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $data = $this->sanitizeData($data);
                $errors = $this->validateData($data);
                

                if (count($errors) > 0) {
                    http_response_code(405);
                    echo json_encode(['message' => 'Seller creation failed', 'errors' => $errors]);
                    return;
                }

                $this->seller->Name = $data['Name'];
                $this->seller->PhoneNumber = $data['PhoneNumber'];
                $this->seller->Address = $data['Address'];

                $this->seller->create();
                echo json_encode(['message' => 'Seller created successfully']);

                
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method not allowed']);
                break;
        }
    }
}