<?php
declare(strict_types=1);
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once 'Database.php';


class ItemsTable extends Table
{
    protected PDO $connection;
    protected string $tableName = 'items';

    public int $ID;
    public string $Name;
    public string $TypeOfItem;
    public string $Size;
    public string $Color;
    public int $Price;
    public int $SellerID;
    public bool $Sold = False;
}

class Clothing extends ItemsTable
{

    public function create(): void
    {
        $query = 'INSERT INTO ' . $this->tableName . ' SET Name = :Name, TypeOfItem = :TypeOfItem, Size = :Size, Color = :Color, Price = :Price, SellerID = :SellerID, Sold = :Sold';

        $stmt = $this->connection->prepare($query);


        $stmt->bindParam(':Name', $this->Name);
        $stmt->bindParam(':TypeOfItem', $this->TypeOfItem);
        $stmt->bindParam(':Size', $this->Size);
        $stmt->bindParam(':Color', $this->Color);
        $stmt->bindParam(':Price', $this->Price);
        $stmt->bindParam(':SellerID', $this->SellerID);
        $stmt->bindParam(':Sold', $this->Sold);


        $stmt->execute();
    }

    public function getData(?int $ID = null): array{

        if ($ID != null){
            return $this->getById($ID);
        }

        return $this->getAll();
    }

    public function markSold(int $ID, bool $Sold): void
    {
        $query = 'UPDATE ' . $this->tableName . ' SET Sold = :Sold WHERE ID = :ID';

        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(':ID', $ID);
        $stmt->bindParam(':Sold', $Sold);

        $stmt->execute();

        
    }

    public function readSold(): PDOStatement
    {
        $query = 'SELECT * FROM ' . $this->tableName . ' WHERE Sold = 1';

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt;
    }

}
?>