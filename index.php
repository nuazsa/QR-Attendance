<?php
require_once __DIR__ . '/component/connection.php';
$pdo = connectToDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare the statement to fetch the user by username
    $stmt = $pdo->prepare('SELECT * FROM pengguna WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user is found
    if ($user && password_verify($password, $user['password'])) {
        // Start the session
        session_start();

        // Store user information in session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];

        if ($user['role'] === 'admin') {
            $_SESSION['username'] = $user['username'];
            header('Location: admin/index.php');
            exit;
        } else {
            header('Location: user/index.php');
            exit;
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance - Sign In</title>

    <!-- Style CSS -->
    <link rel="shortcut icon" href="component/svg/Frame1.svg" type="image/x-icon">
    <link rel="stylesheet" href="component/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Script JS -->
    <script src="component/js/date.js"></script>

    <style>
        body {
            background-image: url('component/svg/Frame.svg');
        }

        form {
            display: block;
        }

        .profile-picture {
            width: 80px;
            height: 80px;
            background-color: #1089ff;
            /* Placeholder color */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-picture i {
            font-size: 34px;
            color: #ffffff;
        }

        /* Container styling */
        section .container {
            display: block;
            max-width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
        }

        /* Row styling */
        .row {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
        }

        /* Profile picture styling */
        .profile-picture {
            text-align: center;
        }

        /* Form label styling */
        label {
            display: block;
            margin-bottom: 5px;
        }

        /* Form input styling */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Form button styling */
        button {
            width: 100%;
            padding: 10px;
            background-color: #1089ff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 425px) {

            section .container {
                max-width: 250px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h3><i class="fa-solid fa-qrcode"></i>QR Attendance</h3>
            <div class="date">
                <p><i class="fa-regular fa-calendar"></i><?= date('l, d/M/Y'); ?></p>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="profile-picture">
                    <i class="fa-regular fa-user"></i>
                </div>
                <h3>Sign In</h3>
                <?php if (isset($error)) : ?>
                    <p style="color: red; margin-bottom: 20px;"><?= $error ?></p>
                <?php endif; ?>

            </div>
            <div class="row">
                <div class="form">
                    <form action="" method="post">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password">
                        <button name="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>