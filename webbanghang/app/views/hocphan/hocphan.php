    <?php
    session_start();
    require_once '../../config/Database.php';
    require_once '../../models/HocPhanModel.php';
    require_once '../shares/header.php';
    // Kết nối CSDL
    $database = new Database();
    $db = $database->getConnection();
    $hocPhanModel = new HocPhanModel($db);

    // Lấy danh sách học phần
    $hocPhanList = $hocPhanModel->getAllHocPhan();

    // Xử lý thêm học phần vào giỏ hàng
    if (isset($_POST['maHP'])) {
        $maHP = $_POST['maHP'];

        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['gio_hang'])) {
            $_SESSION['gio_hang'] = [];
        }

        // Kiểm tra xem học phần đã có trong giỏ chưa
        if (!in_array($maHP, $_SESSION['gio_hang'])) {
            $_SESSION['gio_hang'][] = $maHP;
        }

        echo json_encode(["success" => true, "message" => "Đã thêm vào giỏ hàng!"]);
        exit;
    }
    ?>

    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Danh sách học phần</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            button {
                cursor: pointer;
                padding: 5px 10px;
                background-color: blue;
                color: white;
                border: none;
                border-radius: 5px;
            }
        </style>
        <script>
            function dangKyHocPhan(maHP) {
                fetch("hocphan.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "maHP=" + maHP
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error("Lỗi:", error));
            }
        </script>
    </head>
    <body>
        <h2>Danh sách học phần</h2>
        <table>
            <tr>
                <th>Mã HP</th>
                <th>Tên học phần</th>
                <th>Số tín chỉ</th>
                <th>Hành động</th>
            </tr>
            <?php foreach ($hocPhanList as $hocPhan): ?>
                <tr>
                    <td><?= $hocPhan['MaHP'] ?></td>
                    <td><?= $hocPhan['TenHP'] ?></td>
                    <td><?= $hocPhan['SoTinChi'] ?></td>
                    <td>
                        <button onclick="dangKyHocPhan('<?= $hocPhan['MaHP'] ?>')">Đăng ký</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <br>
        <a href="giohang.php">🛒 Xem giỏ hàng</a>
    </body>
    </html>
