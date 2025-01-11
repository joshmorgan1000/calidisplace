<?php
// register.php
require_once 'init.php';
require_once 'db_connect.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    } else {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validations
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }

        if (empty($errors)) {
            // Check if email already used
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "Email is already registered.";
            } else {
                // Insert new user
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
                if ($stmt->execute([$email, $password_hash])) {
                    $success = true;
                } else {
                    $errors[] = "Error creating user account.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Cali Fires Shelter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1>Register</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">
            Registration successful! <a href="login.php">Login here</a>.
        </div>
    <?php endif; ?>

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
            <label for="password" class="form-label">Password (min 6 chars):</label>
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

        <button type="submit" class="btn btn-primary">Register</button>
        <a href="index.php" class="btn btn-secondary float-end">Cancel</a>
    </form>
</div>
</body>
</html>
