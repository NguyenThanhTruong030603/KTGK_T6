<?php
require_once '../../config/Database.php';
require_once '../../models/SinhVienModel.php';
require_once '../shares/header.php';
$database = new Database();
$db = $database->getConnection();

// Lấy danh sách mã ngành từ database
$query = "SELECT MaNganh, TenNganh FROM NganhHoc";
$stmt = $db->prepare($query);
$stmt->execute();
$nganhs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm sinh viên</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2 class="text-center text-primary">Thêm Sinh Viên</h2>

        <form action="../../controllers/SinhVienController.php?action=add" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Mã SV:</label>
                <input type="text" name="maSV" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Họ Tên:</label>
                <input type="text" name="hoTen" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giới Tính:</label>
                <select name="gioiTinh" class="form-select">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày Sinh:</label>
                <input type="date" name="ngaySinh" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh đại diện:</label>
                <input type="file" name="hinh" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Mã Ngành:</label>
                <select name="maNganh" class="form-select" required>
                    <option value="">-- Chọn Mã Ngành --</option>
                    <?php foreach ($nganhs as $nganh): ?>
                        <option value="<?= $nganh['MaNganh'] ?>">
                            <?= $nganh['MaNganh'] ?> - <?= $nganh['TenNganh'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success btn-custom">
                <i class="fa fa-plus"></i> Thêm Sinh Viên
            </button>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
