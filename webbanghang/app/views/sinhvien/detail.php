<?php
require_once '../../config/Database.php';
require_once '../../models/SinhVienModel.php';
require_once '../shares/header.php';
// Kết nối CSDL
$database = new Database();
$db = $database->getConnection();
$sinhVienModel = new SinhVien($db);

// Lấy mã sinh viên từ URL
if (isset($_GET['id'])) {
    $maSV = $_GET['id'];
    $sinhVien = $sinhVienModel->getById($maSV);

    if (!$sinhVien) {
        die("Không tìm thấy sinh viên với mã này!");
    }
} else {
    die("Thiếu mã sinh viên!");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết sinh viên</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .avatar {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
            margin-bottom: 15px;
        }
        .btn-back {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card text-center">
        <h2 class="text-primary">Chi Tiết Sinh Viên</h2>

        <img src="../../<?= $sinhVien['Hinh'] ?>" class="avatar" alt="Ảnh sinh viên">
        
        <p><strong>Mã SV:</strong> <?= $sinhVien['MaSV'] ?></p>
        <p><strong>Họ Tên:</strong> <?= $sinhVien['HoTen'] ?></p>
        <p><strong>Giới Tính:</strong> <?= $sinhVien['GioiTinh'] ?></p>
        <p><strong>Ngày Sinh:</strong> <?= $sinhVien['NgaySinh'] ?></p>
        <p><strong>Mã Ngành:</strong> <?= $sinhVien['MaNganh'] ?></p>

        <a href="list.php" class="btn btn-secondary btn-back">
            <i class="fa fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
