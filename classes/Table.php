<?php
declare(strict_types=1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'Database.php';

class Table extends Database
{
    protected string $tableName;
    protected ?string $sortBy = null;

    protected function getAll(): array
    {
        $query = 'SELECT * FROM ' . $this->tableName;

        if ($this->sortBy != null) {
            $query .= ' ORDER BY ' . $this->sortBy;
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $tableContent = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tableContent;
    }

    protected function getById(int $ID): array
    {


        $query = 'SELECT * FROM ' . $this->tableName . ' WHERE ID = :ID';

        

        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(':ID', $ID);

        $stmt->execute();

        $tableContent = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $tableContent;

    }

}

?>