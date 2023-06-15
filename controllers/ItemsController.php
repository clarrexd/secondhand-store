<?php
declare(strict_types=1);
require_once __DIR__ . '/../classes/Items.php';


class ClothingController
{
    private Clothing $clothing;

    public function __construct()
    {
        $this->clothing = new Clothing();
    }

    public function sanitizeData(array $data): array {
        $data['Name'] = filter_var($data['Name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $data['TypeOfItem'] = filter_var($data['TypeOfItem'], FILTER_SANITIZE_SPECIAL_CHARS);
        $data['Size'] = filter_var($data['Size'], FILTER_SANITIZE_SPECIAL_CHARS);
        $data['Color'] = filter_var($data['Color'], FILTER_SANITIZE_SPECIAL_CHARS);
        $data['Price'] = (int) filter_var($data['Price'], FILTER_SANITIZE_NUMBER_INT);
        $data["SellerID"] = (int) filter_var($data['SellerID'], FILTER_SANITIZE_NUMBER_INT);
        $data["Sold"] = filter_var($data['Sold'], FILTER_VALIDATE_BOOLEAN);


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

        if (!isset($data['TypeOfItem']) || strlen($data['TypeOfItem']) < 1) {
            $errors[] = 'Type of item is required';
        }

        if (filter_var($data['TypeOfItem'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-zåäö A-ZÅÄÖ0-9\-]*$/']]) === false) {
            $errors[] = 'TypeOfItem can only contain letters, numbers, spaces and dashes';
        }

        if (!isset($data['Size']) || strlen($data['Size']) < 1) {
            $errors[] = 'Size is required';
        }

        if (filter_var($data['Size'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-zåäö A-ZÅÄÖ\-]*$/']]) === false) {
            $errors[] = 'Size can only contain letters and dashes';
        }

        if (!isset($data['Color']) || strlen($data['Color']) < 1) {
            $errors[] = 'Color is required';
        }

        if (filter_var($data['Color'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-zåäö A-ZÅÄÖ]*$/']]) === false) {
            $errors[] = 'Color can only contain letters and spaces';
        }

        if (!isset($data['Price'])) {
            $errors[] = 'Price is required';
        }

        if (filter_var($data['Price'], FILTER_VALIDATE_INT) === false) {
            $errors[] = 'Price must be a number';
        }

        if (!isset($data['SellerID'])) {
            $errors[] = 'Seller ID is required';
        }

        if (filter_var($data['SellerID'], FILTER_VALIDATE_INT) === false) {
            $errors[] = 'Seller ID must be a number';
        }

        if (isset($data['Sold'])) {
            if (filter_var($data['Sold'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === null) {
                $errors[] = 'Sold must be a boolean';
            }
        }
        
        return $errors;
    }

    public function processRequest(?int $ID = null): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $data = $this->clothing->getData($ID);
                echo json_encode($data);
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $data = $this->sanitizeData($data);
                $errors = $this->validateData($data);

                if (count($errors) > 0) {
                    http_response_code(405);
                    echo json_encode(['message' => 'Clothing creation failed', 'errors' => $errors]);
                    return;
                }

                $this->clothing->Name = $data['Name'];
                $this->clothing->TypeOfItem = $data['TypeOfItem'];
                $this->clothing->Size = $data['Size'];
                $this->clothing->Color = $data['Color'];
                $this->clothing->Price = $data['Price'];
                $this->clothing->SellerID = $data['SellerID'];
                $this->clothing->Sold = $data['Sold'] ?? false;
                $this->clothing->create();
                echo json_encode(['message' => 'Clothing created successfully']);

              /*   if ($this->clothing->create()) {
                    echo json_encode(['message' => 'Clothing created successfully']);
                } else {
                    echo json_encode(['message' => 'Clothing creation failed']);
                } */
                break;
            case 'PUT':
                $data = json_decode(file_get_contents("php://input"));

                if ($ID == null) {
                    http_response_code(405);
                    echo json_encode(['message' => 'Please input an ID.']);
                    return;
                }

                if (!property_exists($data, 'Sold')){
                    http_response_code(405);
                    echo json_encode(['message' => 'What are you even trying to change?']);
                    return;
                }
                
                if ($data->Sold == '1') {
                    $Sold = true;
                }
                else if ($data->Sold == '0'){
                    $Sold = false;
                } else {
                    http_response_code(405);
                    echo json_encode(['message' => 'What are you even trying to do?']);
                    return;
                }

                $this->clothing->markSold($ID, $Sold);
                echo json_encode(['message' => 'Sold status updated successfully']);

                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method not allowed']);
                break;
        }
    }
}