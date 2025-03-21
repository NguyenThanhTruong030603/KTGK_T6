<?php
require_once '../../config/Database.php';
require_once '../../models/SinhVienModel.php';
require_once '../shares/header.php';
$database = new Database();
$db = $database->getConnection();
$sinhVienModel = new SinhVien($db);
$sinhViens = $sinhVienModel->getAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách sinh viên</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        .container {
            margin-top: 30px;
        }
        .table img {
            border-radius: 5px;
        }
        .btn-action {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-primary">Danh sách sinh viên</h2>
    
    <a href="add.php" class="btn btn-success mb-3">
        <i class="fa fa-plus"></i> Thêm sinh viên
    </a>

    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>Mã SV</th>
                <th>Họ Tên</th>
                <th>Giới Tính</th>
                <th>Ngày Sinh</th>
                <th>Hình</th>
                <th>Mã Ngành</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sinhViens as $sv): ?>
                <tr>
                    <td><?= $sv['MaSV'] ?></td>
                    <td><?= $sv['HoTen'] ?></td>
                    <td><?= $sv['GioiTinh'] ?></td>
                    <td><?= $sv['NgaySinh'] ?></td>
                    <td>
                        <img src="../../<?= $sv['Hinh'] ?>" width="50" height="50">
                    </td>
                    <td><?= $sv['MaNganh'] ?></td>
                    <td class="btn-action">
                        <a href="detail.php?id=<?= $sv['MaSV'] ?>" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> Xem
                        </a>
                        <a href="edit.php?maSV=<?= $sv['MaSV'] ?>" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Sửa
                        </a>
                        <a href="../../controllers/SinhVienController.php?action=delete&maSV=<?= $sv['MaSV'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">
                            <i class="fa fa-trash"></i> Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
