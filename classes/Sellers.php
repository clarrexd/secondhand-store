<?php
declare(strict_types=1);
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/Table.php';

class SellersTable extends Table
{
    protected string $tableName = 'sellers';
    protected ?string $sortBy = 'Name';

    public int $ID;
    public string $Name;
    public string $PhoneNumber;
    public string $Address;
    
}

class Seller extends SellersTable
{
    public function create(): void
    {
        $query = 'INSERT INTO ' . $this->tableName . ' SET Name = :Name, Phonenumber = :PhoneNumber, Address = :Address ';

        $stmt = $this->connection->prepare($query);

        $this->Name = htmlspecialchars(strip_tags($this->Name));

        $stmt->bindParam(':Name', $this->Name);
        $stmt->bindParam(':PhoneNumber', $this->PhoneNumber);
        $stmt->bindParam(':Address', $this->Address);
        

        $stmt->execute();
    }

    public function getData(?int $ID = null): array{
        $data = [];

        if ($ID != null){
            $data = $this->getById($ID);
            
            // Add NumberOfSoldItems
            
            $data[0]["NumberOfSoldItems"] = $this->getNumberOfSoldItemsBySeller($ID);

            // Add ItemsSubmittedCount
            
            $data[0]["ItemsSubmittedCount"] = $this->getItemsSubmittedBySeller($ID);

            //Add getAllItems

            $data[0]["AllItemsSubmitted"] = $this->getAllItemsSubmitted($ID);

            //Add getTotalSales

            $data[0]["TotalSumSold"] = $this->getTotalSales($ID);

        } else {
            $data = $this->getAll();
          

            foreach($data as $index => $row) {
                // Add NumberOfSoldItems
                $data[$index]["NumberOfSoldItems"] = $this->getNumberOfSoldItemsBySeller($row["ID"]);
                
                // Add ItemsSubmittedCount
                $data[$index]["ItemsSubmittedCount"] = $this->getItemsSubmittedBySeller($row["ID"]);

                // Add getAllItems

                $data[$index]["AllItemsSubmitted"] = $this->getAllItemsSubmitted($row["ID"]);

                //Add getTotalSales

                $data[$index]["TotalSumSold"] = $this->getTotalSales($row["ID"]);
            }
        }

        return $data;
    }

    public function getTotalSales(int $ID): int {

        $query = ' SELECT SUM(items.Price) AS total_sum_sold FROM items
        JOIN sellers ON sellers.ID = items.sellerID
        WHERE sellers.ID = :ID
        GROUP BY sellers.ID;';

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        $array = $stmt->fetchAll();
        if (count($array)== 0) return 0;
        else {
            return (int) $array[0]['total_sum_sold'];
        }
    }



    public function getNumberOfSoldItemsBySeller(int $ID): int {

        $query = ' SELECT COUNT(items.SellerID) AS sold_items_count FROM items
        JOIN sellers ON sellers.ID = items.sellerID
        WHERE sellers.ID = :ID AND items.Sold=1
        GROUP BY sellers.ID;';

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        $array = $stmt->fetchAll();
        if (count($array)== 0) return 0;
        else {
            return $array[0]['sold_items_count'];
        }
    }

    public function getItemsSubmittedBySeller(int $ID): int {

        $query = ' SELECT COUNT(items.SellerID) AS submitted_items_count FROM items
        JOIN sellers ON sellers.ID = items.sellerID
        WHERE sellers.ID = :ID
        GROUP BY sellers.ID;';

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        $array = $stmt->fetchAll();
        if (count($array)== 0) return 0;
        else {
            return $array[0]['submitted_items_count'];
        }
    }

    public function getAllItemsSubmitted(int $ID): array {

        $query = 'SELECT Name FROM items WHERE items.SellerID = :ID;';

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $items = [];
        foreach($rows as $row) {
            array_push($items, $row['Name']);

        }
        return $items;
    }
}

?>