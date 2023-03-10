<?php
session_start();
include 'config.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([
            'username' => $username
        ]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Incorrect username or password. <a href='forgotten_password.php'>Forgotten password?</a>";
        }
    } catch (PDOException $e) {
        $error = "Error logging in: " . $e->getMessage();
    }
}

$page_name = "Login";
include("template/header.php");
include("template/left.php");

?>

<h2>Login</h2>
    <hr>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>
    <form action="" method="post">
        <p>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username">
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>
    <p>
        Don't have an account? <a href="signup.php">Sign up here</a>.
    </p>
<?php include("template/footer.php") ?>