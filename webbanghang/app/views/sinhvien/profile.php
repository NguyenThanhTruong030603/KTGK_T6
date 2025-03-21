<?php
session_start();
require_once '../../config/Database.php';
require_once '../../models/SinhVienModel.php';
require_once '../shares/header.php';
if (!isset($_SESSION['sinhvien'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$sinhVienModel = new SinhVien($db);

$maSV = $_SESSION['sinhvien'];
$sinhVien = $sinhVienModel->getById($maSV);

if (!$sinhVien) {
    echo "Lỗi: Không tìm thấy sinh viên!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h3>Thông Tin Sinh Viên</h3>
        </div>
        <div class="card-body">
            <p><strong>Mã SV:</strong> <?= $sinhVien['MaSV'] ?></p>
            <p><strong>Họ Tên:</strong> <?= $sinhVien['HoTen'] ?></p>
            <p><strong>Giới Tính:</strong> <?= $sinhVien['GioiTinh'] ?></p>
            <p><strong>Ngày Sinh:</strong> <?= $sinhVien['NgaySinh'] ?></p>
            <p><strong>Hình:</strong> <br><img src="../../<?= $sinhVien['Hinh'] ?>" width="120"></p>
            <p><strong>Mã Ngành:</strong> <?= $sinhVien['MaNganh'] ?></p>
            <a href="logout.php" class="btn btn-danger">Đăng xuất</a>
        </div>
    </div>
</div>

</body>
</html>
