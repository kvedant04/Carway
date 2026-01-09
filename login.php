<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carway";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables and messages
$email = $password = $contact = "";
$success_message = $error_message = "";

// Process form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];

    // Basic validation
    if (!empty($email) && !empty($password) && !empty($contact)) {
        $sql = "INSERT INTO `carway` (`email`, `password`, `contact`) VALUES ('$email', '$password', '$contact')";

        if ($conn->query($sql)) {
            $success_message = "Registration successful!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        $error_message = "All fields are required!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarWay Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff8c00;
            --secondary-color: #6a5af9;
            --dark-bg: #121212;
            --card-bg: #1e1e1e;
            --input-bg: #2a2a2a;
            --text-light: #aaaaaa;
            --text-white: #ffffff;
            --border-color: #333;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--dark-bg);
            color: var(--text-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
            margin: 20px 0;
        }
        
        .image-section {
            flex: 1;
            background: url('https://i.ibb.co/M54z3Yw/Screenshot-2025-01-15-120725.png') no-repeat center center;
            background-size: contain;
            background-color: var(--card-bg);
            min-height: 400px;
        }
        
        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        
        .form-section h2 {
            margin-bottom: 25px;
            color: var(--primary-color);
            text-align: center;
            font-size: 2rem;
            position: relative;
        }
        
        .form-section h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .form-section form {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
            width: 100%;
        }
        
        label {
            font-size: 14px;
            color: var(--text-light);
            display: block;
            margin-bottom: 8px;
        }
        
        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--input-bg);
            color: var(--text-white);
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.2);
        }
        
        .remember-recover {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .remember-recover a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .remember-recover a:hover {
            color: #5948c7;
            text-decoration: underline;
        }
        
        .remember-recover .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remember-recover .checkbox-container label {
            margin: 0;
            cursor: pointer;
        }
        
        button {
            background: linear-gradient(to right, var(--secondary-color), #5948c7);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(106, 90, 249, 0.3);
            width: 100%;
        }
        
        button:hover {
            background: linear-gradient(to right, #5948c7, var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(106, 90, 249, 0.4);
        }
        
        .divider {
            text-align: center;
            margin: 25px 0;
            color: var(--text-light);
            font-size: 14px;
            position: relative;
        }
        
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: var(--border-color);
        }
        
        .divider::before {
            left: 0;
        }
        
        .divider::after {
            right: 0;
        }
        
        .social-login {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
        }
        
        .social-login button {
            background: var(--input-bg);
            color: white;
            border: 1px solid var(--border-color);
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .social-login button:hover {
            background: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .social-login img {
            width: 20px;
            height: 20px;
        }
        
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .back-btn:hover {
            color: #ff8c00;
            transform: translateX(-3px);
        }
        
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            animation: fadeIn 0.5s ease;
            width: 100%;
        }
        
        .message.success {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        
        .message.error {
            background-color: rgba(244, 67, 54, 0.2);
            color: #F44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .image-section {
                min-height: 200px;
                background-size: contain;
                background-position: center;
            }
            
            .form-section {
                padding: 30px 25px;
            }
            
            .back-btn {
                top: 15px;
                left: 15px;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .form-section {
                padding: 25px 20px;
            }
            
            .remember-recover {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.html" class="back-btn animate__animated animate__fadeIn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
        
        <div class="image-section"></div>
        
        <!-- Right form section -->
        <div class="form-section">
            <h2 class="animate__animated animate__fadeIn">Sign In to your account</h2>
            
            <?php if (!empty($success_message)): ?>
                <div class="message success animate__animated animate__fadeIn"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="message error animate__animated animate__fadeIn"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group animate__animated animate__fadeIn">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                
                <div class="form-group animate__animated animate__fadeIn">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="form-group animate__animated animate__fadeIn">
                    <label for="contact">Contact Number</label>
                    <input type="text" id="contact" name="contact" placeholder="Enter your contact number" value="<?php echo htmlspecialchars($contact); ?>" required>
                </div>
                
                <div class="remember-recover animate__animated animate__fadeIn">
                    <div class="checkbox-container">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#">Forgot Password?</a>
                </div>
                
                <button type="submit" class="animate__animated animate__fadeIn">Sign In</button>
            </form>
            
            <div class="divider animate__animated animate__fadeIn">Or continue with</div>
            
            <div class="social-login animate__animated animate__fadeIn">
                <button>
                    <img src="https://proxys.io/files/blog/chrome/Paint.png" alt="Google Logo">
                    Sign In with Google
                </button>
                <button>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook Logo">
                    Sign In with Facebook
                </button>
            </div>
        </div>
    </div>
</body>
</html>