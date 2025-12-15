<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akun - KopiCa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --color-coffee-dark: #4A2C2A; 
            --color-coffee-medium: #795548; 
            --color-cream-light: #F8F5F2; 
            --color-white: #ffffff;
        }
    
        body {
            background-color: var(--color-cream-light); 
            color: var(--color-coffee-dark); 
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('https://placehold.co/1920x1080/EAE0D3/795548?text=Coffee+Background');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            background-color: var(--color-white);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); 
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .login-container h2 {
            color: var(--color-coffee-dark);
            margin-bottom: 30px;
            font-weight: 700;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            background-color: var(--color-cream-light);
        }

        .login-container input[type="submit"] {
            width: 100%;
            background-color: var(--color-coffee-dark);
            color: var(--color-white);
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .login-container input[type="submit"]:hover {
            background-color: var(--color-coffee-medium);
        }

        .login-container p {
            margin-top: 20px;
        }
        .login-container a {
            color: var(--color-coffee-medium);
            text-decoration: none;
            font-weight: bold;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2><i class="fas fa-coffee me-2"></i> Login Akun</h2>
    <form action="proses_login.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>