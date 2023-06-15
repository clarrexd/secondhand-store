<?php
declare(strict_types=1);
require_once __DIR__ . '/controllers/SellersController.php';
require_once __DIR__ . '/controllers/ItemsController.php';


header('Content-Type: application/json');


$URI = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];


$URI = trim($URI, '/');



try {
    $parts = explode('/', $URI);

    if ($parts[0] == "secondhand_store") {
        $ID = null;

        switch($parts[1]) {
            case "items":
                $controller = new ClothingController();
                break;
            case "sellers":
                $controller = new SellersController();
                break;
            default:
                throw new Exception("Invalid path");
        }

        if(count($parts) > 2){
            if (is_numeric($parts[2])) {
                $ID = (int) $parts[2];
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Invalid path input. Please provide a number corresponding to a specific ID.']);
                return;
            }
        }

        $controller->processRequest($ID);
        return;
    }

} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'message' => 'The server is crashing and burning. Good job',
        'Error' => $exception->getMessage()
    ]);
    return;
}



http_response_code(404);
echo json_encode(['message' => 'Not Found, I am deeply sorry.']);

// TODO: For reference only. Remove when done.
// Regular expression pattern: /<expr>/
// Expression symbols:
//  ^  - Start of string
//  \  - Escape following symbol (i.e. `\/` means literal `/`, not picked up by regex)
//  () - Capture group. Can be used to reference contained string using $matches.
//  \d - Digit
//  +  - Match one or more of preceding symbol
//  ?  - Match one or none of preceding symbol
//  $  - End of string
// In the below expression, there are 2 capture groups:
//  1.  (\/(\d+))   - Literal `/` followed by one or more digits. (contains an inner group, see below)
//  2.  (\d+)       - One or more digits
//  With these defined, $matches will contain 2 groups:
//      $matches[1] - Group 1 above, for example `/15` in /secondhand_store/sellers/15
//      $matches[2] - Group 2 above, for example `7` in /secondhand_store/sellers/7

/* $matches = array();
    if (preg_match('/^\/secondhand_store\/sellers(\/(\d+))?$/', $URI, $matches) === 1) {

        $sellerController = new SellersController();
        $sellerController->processRequest();
        return;
    } else if (preg_match('/^\/secondhand_store\/items(\/(\d+))?$/', $URI, $matches) === 1) {
        $clothingController = new ClothingController();
        $clothingController->processRequest();
        return;
    } */