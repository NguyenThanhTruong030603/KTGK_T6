<?php
session_start();
require_once '../../config/Database.php';
require_once '../../models/SinhVienModel.php';
require_once '../shares/header.php';
$database = new Database();
$db = $database->getConnection();
$sinhVienModel = new SinhVien($db);

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maSV = trim($_POST['maSV']);

    if (!empty($maSV)) {
        $sinhVien = $sinhVienModel->getById($maSV);
        if ($sinhVien) {
            $_SESSION['sinhvien'] = $sinhVien['MaSV'];
            header("Location: profile.php");
            exit;
        } else {
            $error = "Mã sinh viên không tồn tại!";
        }
    } else {
        $error = "Vui lòng nhập Mã Sinh Viên!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-header text-center">
            <h3>Đăng Nhập Sinh Viên</h3>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Mã Sinh Viên:</label>
                    <input type="text" class="form-control" name="maSV" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
