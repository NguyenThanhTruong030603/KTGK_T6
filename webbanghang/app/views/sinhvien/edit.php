<?php
require_once '../../config/Database.php';
require_once '../../models/SinhVienModel.php';
require_once '../shares/header.php';
$database = new Database();
$db = $database->getConnection();
$sinhVienModel = new SinhVien($db);

$maSV = $_GET['maSV'] ?? null;
$sinhVien = $sinhVienModel->getById($maSV);

if (!$sinhVien) {
    echo "Sinh viên không tồn tại!";
    exit;
}

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
    <title>Sửa sinh viên</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        .container {
            max-width: 600px;
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
        .btn-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2 class="text-center text-primary">Sửa thông tin sinh viên</h2>

        <form action="../../controllers/SinhVienController.php?action=edit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="maSV" value="<?= $sinhVien['MaSV'] ?>">

            <div class="mb-3">
                <label class="form-label">Họ Tên:</label>
                <input type="text" class="form-control" name="hoTen" value="<?= $sinhVien['HoTen'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giới Tính:</label>
                <select class="form-select" name="gioiTinh">
                    <option value="Nam" <?= $sinhVien['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= $sinhVien['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày Sinh:</label>
                <input type="date" class="form-control" name="ngaySinh" value="<?= $sinhVien['NgaySinh'] ?>" required>
            </div>

            <div class="mb-3 text-center">
                <label class="form-label">Ảnh đại diện:</label>
                <br>
                <img id="previewImage" src="../../<?= $sinhVien['Hinh'] ?>" class="avatar">
                <input type="hidden" name="old_hinh" value="<?= $sinhVien['Hinh'] ?>">
                <input type="file" class="form-control mt-2" name="hinh" accept="image/*" onchange="previewFile()">
            </div>

            <div class="mb-3">
                <label class="form-label">Mã Ngành:</label>
                <select class="form-select" name="maNganh" required>
                    <?php foreach ($nganhs as $nganh): ?>
                        <option value="<?= $nganh['MaNganh'] ?>" <?= $sinhVien['MaNganh'] == $nganh['MaNganh'] ? 'selected' : '' ?>>
                            <?= $nganh['MaNganh'] ?> - <?= $nganh['TenNganh'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Cập nhật
                </button>
                <a href="list.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function previewFile() {
        const file = document.querySelector('input[name="hinh"]').files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('previewImage').src = reader.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>
