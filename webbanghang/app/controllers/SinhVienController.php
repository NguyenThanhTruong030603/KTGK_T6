<?php
require_once '../config/Database.php';
require_once '../models/SinhVienModel.php';

$database = new Database();
$db = $database->getConnection();
$sinhVien = new SinhVien($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $maSV = $_POST['maSV'];
        $hoTen = $_POST['hoTen'];
        $gioiTinh = $_POST['gioiTinh'];
        $ngaySinh = $_POST['ngaySinh'];
        $maNganh = $_POST['maNganh'];

        // Xử lý upload ảnh
        $hinh = null;
        if (!empty($_FILES['hinh']['name'])) {
            $target_dir = "../uploads/";
            $image_name = time() . "_" . basename($_FILES['hinh']['name']);
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES['hinh']['tmp_name'], $target_file)) {
                $hinh = "uploads/" . $image_name;
            }
        }

        if ($sinhVien->create($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh)) {
            header("Location: ../views/sinhvien/list.php");
        }
        break;

    case 'edit':
        $maSV = $_POST['maSV'];
        $hoTen = $_POST['hoTen'];
        $gioiTinh = $_POST['gioiTinh'];
        $ngaySinh = $_POST['ngaySinh'];
        $maNganh = $_POST['maNganh'];
        $old_hinh = $_POST['old_hinh'];

        if (!empty($_FILES['hinh']['name'])) {
            $target_dir = "../uploads/";
            $image_name = time() . "_" . basename($_FILES['hinh']['name']);
            $target_file = $target_dir . $image_name;

            if (move_uploaded_file($_FILES['hinh']['tmp_name'], $target_file)) {
                $hinh = "uploads/" . $image_name;
            } else {
                $hinh = $old_hinh;
            }
        } else {
            $hinh = $old_hinh;
        }

        if ($sinhVien->update($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh)) {
            header("Location: ../views/sinhvien/list.php");
        }
        break;

    case 'delete':
        if ($sinhVien->delete($_GET['maSV'])) {
            header("Location: ../views/sinhvien/list.php");
        }
        break;

    
    default:
        echo "Hành động không hợp lệ!";

}
?>
