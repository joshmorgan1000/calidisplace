<?php
// login.php
require_once 'init.php';
require_once 'db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    } else {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Look up user by email
        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Valid login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $email;
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Cali Fires Shelter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1>Login</h1>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                class="form-control"
                required
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            >
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control"
                required
            >
        </div>

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">

        <button type="submit" class="btn btn-primary">Login</button>
        <a href="index.php" class="btn btn-secondary float-end">Cancel</a>
    </form>
</div>
</body>
</html>
