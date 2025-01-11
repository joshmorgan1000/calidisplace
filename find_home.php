<?php
// find_home.php
require_once 'init.php';
require_once 'db_connect.php';

try {
    $stmt = $pdo->query("
        SELECT full_name, email, city, available_spaces, additional_info, created_at 
        FROM homes
        ORDER BY created_at DESC
    ");
    $homes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find a Home - Cali Fires Shelter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Cali Fires Shelter</a>
  </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-4">Find a Home</h1>

    <section class="mb-5">
        <h2>Available Homes</h2>
        <?php if (!empty($homes)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>Spaces</th>
                            <th>Email</th>
                            <th>Additional Info</th>
                            <th>Listed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($homes as $home): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($home['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($home['city']); ?></td>
                                <td><?php echo (int)$home['available_spaces']; ?></td>
                                <td><?php echo htmlspecialchars($home['email']); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($home['additional_info'])); ?></td>
                                <td><?php echo htmlspecialchars($home['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No homes listed yet. Please check back later.</p>
        <?php endif; ?>
    </section>

    <hr>
    <p><a href="index.php" class="btn btn-secondary">Back</a></p>
</div>

</body>
</html>
