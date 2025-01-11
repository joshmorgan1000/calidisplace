<?php
// manage_listings.php
require_once 'init.php';
require_once 'db_connect.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT id, full_name, email, city, available_spaces, additional_info, created_at
        FROM homes
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$user_id]);
    $my_homes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Listings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Cali Fires Shelter</a>
  </div>
</nav>

<div class="container my-5">
    <h1>Manage Your Listings</h1>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>

    <?php if (!empty($my_homes)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Spaces</th>
                        <th>Additional Info</th>
                        <th>Created At</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($my_homes as $home): ?>
                    <tr>
                        <td><?php echo $home['id']; ?></td>
                        <td><?php echo htmlspecialchars($home['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($home['city']); ?></td>
                        <td><?php echo (int)$home['available_spaces']; ?></td>
                        <td><?php echo nl2br(htmlspecialchars($home['additional_info'])); ?></td>
                        <td><?php echo $home['created_at']; ?></td>
                        <td>
                            <form 
                                action="delete_listing.php" 
                                method="post" 
                                onsubmit="return confirm('Are you sure?');"
                            >
                                <!-- CSRF Token -->
                                <input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">
                                <input type="hidden" name="home_id" value="<?php echo $home['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>You have not listed any homes yet.</p>
    <?php endif; ?>

    <div class="mt-3">
        <a href="index.php" class="btn btn-secondary">Back</a>
    </div>
</div>
</body>
</html>
