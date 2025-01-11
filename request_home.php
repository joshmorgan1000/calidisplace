<?php
// request_home.php
require_once 'init.php';
require_once 'db_connect.php';

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    } else {
        $full_name        = trim($_POST['full_name'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $city             = trim($_POST['city'] ?? '');
        $number_of_people = (int)($_POST['number_of_people'] ?? 1);
        $urgent           = isset($_POST['urgent']) ? 1 : 0;
        $notes            = trim($_POST['notes'] ?? '');

        if (empty($full_name) || empty($email) || $number_of_people < 1) {
            $errors[] = "Name, email, and a valid number of people are required.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO requests (full_name, email, city, number_of_people, urgent, notes)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$full_name, $email, $city, $number_of_people, $urgent, $notes]);
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
    <title>Request a Home - Cali Fires Shelter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1>Request a Home</h1>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">Your request has been submitted successfully!</div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label for="full_name" class="form-label">Your Full Name:</label>
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
            <label for="email" class="form-label">Your Email Address:</label>
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
            <label for="city" class="form-label">Current City (optional):</label>
            <input 
                type="text" 
                name="city" 
                id="city" 
                class="form-control"
                value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>"
            >
        </div>

        <div class="mb-3">
            <label for="number_of_people" class="form-label">Number of People:</label>
            <input 
                type="number" 
                name="number_of_people" 
                id="number_of_people" 
                class="form-control" 
                required
                value="<?php echo isset($_POST['number_of_people']) ? htmlspecialchars($_POST['number_of_people']) : '1'; ?>"
            >
        </div>

        <div class="form-check mb-3">
            <input 
                type="checkbox" 
                name="urgent" 
                id="urgent" 
                value="1" 
                class="form-check-input"
                <?php echo (isset($_POST['urgent']) && $_POST['urgent'] == '1') ? 'checked' : ''; ?>
            >
            <label for="urgent" class="form-check-label">Is this an urgent request?</label>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Additional Notes (medical needs, pet-friendly, etc.):</label>
            <textarea 
                name="notes" 
                id="notes" 
                rows="4" 
                class="form-control"
            ><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
        </div>

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">

        <button type="submit" class="btn btn-success">Submit Request</button>
        <a href="index.php" class="btn btn-secondary float-end">Back</a>
    </form>
</div>
</body>
</html>
