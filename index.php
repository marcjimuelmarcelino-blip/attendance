<?php
// index.php
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['is_admin']) {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: employee/dashboard.php");
    }
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM employees WHERE employee_id = '$employee_id' AND status = 'active'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['employee_id'] = $user['employee_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            if ($user['is_admin']) {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: employee/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Employee ID not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Employee Attendance System - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 400px;
            width: 90%;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .login-header i {
            font-size: 60px;
            margin-bottom: 20px;
            background: rgba(255,255,255,0.2);
            padding: 20px;
            border-radius: 50%;
        }
        .login-form {
            padding: 40px 30px;
        }
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        .form-group i {
            position: absolute;
            left: 15px;
            top: 15px;
            color: #999;
        }
        .form-control {
            height: 50px;
            padding-left: 45px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            font-size: 16px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 50px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .demo-credentials {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #e0e0e0;
            font-size: 14px;
            color: #666;
        }
        .demo-credentials p {
            margin-bottom: 5px;
        }
        .demo-credentials small {
            display: block;
            margin-top: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-clock"></i>
            <h1>Employee Attendance</h1>
            <p>Clock In / Out System</p>
        </div>
        
        <div class="login-form">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" class="form-control" name="employee_id" placeholder="Employee ID" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
            
            <div class="demo-credentials text-center">
                <p><strong>Demo Credentials:</strong></p>
                <p>Admin: ADMIN001 / admin123</p>
                <p>Employee: EMP001 / employee123</p>
                <small>Use your mobile device for best experience</small>
            </div>
        </div>
    </div>
</body>
</html>