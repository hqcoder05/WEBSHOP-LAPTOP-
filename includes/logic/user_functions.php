<?php
include_once BASE_PATH.("/autoload.php");
function registerUser($username, $password, $email) {
    global $conn;

    $sql = "select * from users where username='?'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return "Tên đăng nhập đã tồn tại.";
    }
    //Mã hóa password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    //thêm user
    $sql = "insert into users (username, password, email) values (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $hashedPassword, $email);

    if ($stmt->execute()) {
        return "Đăng ký thành công.";
    } else {
        return "Có lỗi xảy ra, vui lòng thử lại!";
    }

    function loginUser($username, $password) {
        global $conn;

        $sql = "select * from users where username='?'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if (password_verify($password, $username['password'])) {
            session_start();
            $_SESSION['user_id'] = $username['id'];
            $_SESSION['username'] = $username['username'];
            $_SESSION['role'] = $username['role'];

            echo json_encode([
                'success' => true,
                'message' => "Đăng nhập thành công!",
                'redirect' => "../user/home.php"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Mật khẩu không chính xác!"
            ]);
        }
    }

    function logoutUser() {
        session_start();
        session_unset();
        session_destroy();
        return "Đã đăng xuất!";
    }

    function isLoggedIn() {
        session_start();
        return isset($_SESSION['user_id']);
    }

    function getUserInfo() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            global $conn;
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }
}