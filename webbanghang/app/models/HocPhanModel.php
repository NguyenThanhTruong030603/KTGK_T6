<?php
class HocPhanModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllHocPhan() {
        $query = "SELECT * FROM HocPhan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
