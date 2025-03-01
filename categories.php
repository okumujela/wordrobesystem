<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Add a new category
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];

    $query = "INSERT INTO categories (name) VALUES ('$name')";
    mysqli_query($conn, $query);
}

// Delete a category
if (isset($_GET['delete_category'])) {
    $id = $_GET['delete_category'];
    $query = "DELETE FROM categories WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: categories.php');
}

// Fetch categories
$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);
$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Category Management</h1>

    <!-- Add Category Form -->
    <form action="" method="post" class="mb-4">
        <input type="text" name="name" placeholder="Category Name" class="form-control" required>
        <button type="submit" name="add_category" class="btn btn-primary mt-2">Add Category</button>
    </form>

    <!-- Categories Table -->
    <?php if (!empty($categories)) { ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $index => $category) { ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td>
                            <!-- Delete Button -->
                            <a href="?delete_category=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No categories found.</p>
    <?php } ?>

    <!-- Logout Button -->
    <a href="logout.php" class="btn btn-warning mt-4">Logout</a>

</div>

<script src="js/bootstrap.min.js"></script>
</body>
</html>