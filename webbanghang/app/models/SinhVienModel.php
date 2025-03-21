<?php
class SinhVien {
    private $conn;
    private $table_name = "SinhVien";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách sinh viên
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một sinh viên theo mã
    public function getById($maSV) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaSV = :maSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maSV", $maSV);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm sinh viên
    public function create($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh) {
        $query = "INSERT INTO " . $this->table_name . " (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                  VALUES (:maSV, :hoTen, :gioiTinh, :ngaySinh, :hinh, :maNganh)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":maSV", $maSV);
        $stmt->bindParam(":hoTen", $hoTen);
        $stmt->bindParam(":gioiTinh", $gioiTinh);
        $stmt->bindParam(":ngaySinh", $ngaySinh);
        $stmt->bindParam(":hinh", $hinh);
        $stmt->bindParam(":maNganh", $maNganh);

        return $stmt->execute();
    }

    // Cập nhật thông tin sinh viên
    public function update($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh) {
        $query = "UPDATE " . $this->table_name . " 
                  SET HoTen=:hoTen, GioiTinh=:gioiTinh, NgaySinh=:ngaySinh, Hinh=:hinh, MaNganh=:maNganh 
                  WHERE MaSV=:maSV";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":maSV", $maSV);
        $stmt->bindParam(":hoTen", $hoTen);
        $stmt->bindParam(":gioiTinh", $gioiTinh);
        $stmt->bindParam(":ngaySinh", $ngaySinh);
        $stmt->bindParam(":hinh", $hinh);
        $stmt->bindParam(":maNganh", $maNganh);

        return $stmt->execute();
    }

    // Xóa sinh viên
    public function delete($maSV) {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaSV = :maSV";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maSV", $maSV);

        return $stmt->execute();
    }
    
}
?>
