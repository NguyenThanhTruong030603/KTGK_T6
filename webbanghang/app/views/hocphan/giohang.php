<?php
session_start();
require_once '../../config/Database.php';
require_once '../../models/HocPhanModel.php';
require_once '../../models/SinhVienModel.php';
require_once '../shares/header.php';
$database = new Database();
$db = $database->getConnection();
$hocPhanModel = new HocPhanModel($db);
$sinhVienModel = new SinhVien($db);

// Kiểm tra sinh viên đăng nhập
$sinhVienInfo = null;
if (isset($_SESSION['sinhvien'])) {
    $maSV = $_SESSION['sinhvien'];
    $sinhVienInfo = $sinhVienModel->getById($maSV);
}

// Lấy giỏ hàng
$gioHang = $_SESSION['gio_hang'] ?? [];
$hocPhanList = [];
$totalCredits = 0;

if (!empty($gioHang)) {
    $placeholders = implode(',', array_fill(0, count($gioHang), '?'));
    $query = "SELECT * FROM HocPhan WHERE MaHP IN ($placeholders)";
    $stmt = $db->prepare($query);
    $stmt->execute($gioHang);
    $hocPhanList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($hocPhanList as $hocPhan) {
        $totalCredits += $hocPhan['SoTinChi'];
    }
}

// Xóa một học phần khỏi giỏ hàng
if (isset($_POST['remove_maHP'])) {
    $_SESSION['gio_hang'] = array_diff($_SESSION['gio_hang'], [$_POST['remove_maHP']]);
    header("Location: giohang.php");
    exit;
}

// Xóa tất cả học phần
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['gio_hang']);
    header("Location: giohang.php");
    exit;
}

// Xác nhận đăng ký
if (isset($_POST['confirm_registration']) && !empty($hocPhanList)) {
    try {
        $db->beginTransaction();

        // Thêm đăng ký vào bảng DangKy
        $stmt = $db->prepare("INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), ?)");
        $stmt->execute([$maSV]);
        $maDK = $db->lastInsertId();

        // Thêm chi tiết đăng ký vào ChiTietDangKy
        $stmt = $db->prepare("INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)");
        foreach ($hocPhanList as $hocPhan) {
            $stmt->execute([$maDK, $hocPhan['MaHP']]);
        }

        $db->commit();

        // Xóa giỏ hàng sau khi đăng ký thành công
        unset($_SESSION['gio_hang']);
        session_write_close(); // Đảm bảo session được cập nhật

        // Chuyển hướng để làm mới trang và hiển thị thông báo
        header("Location: giohang.php?message=success");
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        $message = "Lỗi khi đăng ký: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng học phần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Giỏ hàng học phần</h2>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_GET['message']) && $_GET['message'] == 'success'): ?>
            <div class="alert alert-success">Đăng ký thành công!</div>
        <?php elseif (!empty($message)): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>

        <!-- Thông tin sinh viên -->
        <?php if ($sinhVienInfo): ?>
            <div class="card p-3 mb-3">
                <h4>Thông tin sinh viên</h4>
                <p><strong>Mã SV:</strong> <?= htmlspecialchars($sinhVienInfo['MaSV']) ?></p>
                <p><strong>Họ tên:</strong> <?= htmlspecialchars($sinhVienInfo['HoTen']) ?></p>
                <p><strong>Ngành học:</strong> <?= htmlspecialchars($sinhVienInfo['MaNganh']) ?></p>
            </div>
        <?php else: ?>
            <p class="text-danger">Bạn chưa đăng nhập! <a href="login.php">Đăng nhập ngay</a></p>
        <?php endif; ?>

        <!-- Hiển thị giỏ hàng -->
        <?php if (empty($hocPhanList)): ?>
            <p>Chưa có học phần nào trong giỏ hàng.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên học phần</th>
                        <th>Số tín chỉ</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hocPhanList as $hocPhan): ?>
                        <tr>
                            <td><?= htmlspecialchars($hocPhan['MaHP']) ?></td>
                            <td><?= htmlspecialchars($hocPhan['TenHP']) ?></td>
                            <td><?= htmlspecialchars($hocPhan['SoTinChi']) ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="remove_maHP" value="<?= htmlspecialchars($hocPhan['MaHP']) ?>">
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>📌 Thống kê</h3>
            <p><strong>Tổng số học phần:</strong> <?= count($hocPhanList) ?></p>
            <p><strong>Tổng số tín chỉ:</strong> <?= $totalCredits ?></p>

            <!-- Nút xóa tất cả học phần -->
            <form method="POST">
                <button type="submit" name="clear_cart" class="btn btn-warning mt-3">Xóa tất cả</button>
            </form>

            <!-- Nút xác nhận đăng ký -->
            <form method="POST">
                <button type="submit" name="confirm_registration" class="btn btn-primary mt-3">Xác nhận đăng ký</button>
            </form>
        <?php endif; ?>

        <br>
        <a href="hocphan.php" class="btn btn-secondary">⬅ Quay lại danh sách học phần</a>
    </div>
</body>
</html>
