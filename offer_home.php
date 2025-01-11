<?php
// offer_home.php
require_once 'init.php';
require_once 'db_connect.php';

// If user not logged in, redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    } else {
        $user_id         = $_SESSION['user_id'];
        $full_name       = trim($_POST['full_name'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $city            = trim($_POST['city'] ?? '');
        $available_spaces= (int)($_POST['available_spaces'] ?? 0);
        $additional_info = trim($_POST['additional_info'] ?? '');

        if (empty($full_name) || empty($email) || empty($city) || $available_spaces < 1) {
            $errors[] = "Please fill in all required fields (spaces must be > 0).";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO homes (user_id, full_name, email, city, available_spaces, additional_info)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$user_id, $full_name, $email, $city, $available_spaces, $additional_info]);
                $success = true;
            } catch (PDOException $e) {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Offer a Home - Cali Fires Shelter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Cali Fires Shelter</a>
  </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-4">Offer a Home</h1>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <div class="alert alert-success" role="alert">
            Your home has been listed successfully!
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="card p-4 shadow">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name:</label>
            <input 
                type="text" 
                name="full_name" 
                id="full_name" 
                class="form-control" 
                required
                value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
            >
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address:</label>
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
            <label for="city" class="form-label">City/Region:</label>
            <input 
                type="text" 
                name="city" 
                id="city" 
                class="form-control" 
                required
                value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>"
            >
        </div>

        <div class="mb-3">
            <label for="available_spaces" class="form-label">Available Spaces:</label>
            <input 
                type="number" 
                name="available_spaces" 
                id="available_spaces" 
                class="form-control" 
                required
                value="<?php echo isset($_POST['available_spaces']) ? htmlspecialchars($_POST['available_spaces']) : '1'; ?>"
            >
        </div>

        <div class="mb-3">
            <label for="additional_info" class="form-label">Additional Info:</label>
            <textarea 
                name="additional_info" 
                id="additional_info" 
                rows="4" 
                class="form-control"
            ><?php echo isset($_POST['additional_info']) ? htmlspecialchars($_POST['additional_info']) : ''; ?></textarea>
        </div>

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="index.php" class="btn btn-secondary float-end">Back</a>
    </form>
</div>
</body>
</html>
