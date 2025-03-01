<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];

// Fetch the category to edit
$query = "SELECT * FROM categories WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$category = mysqli_fetch_assoc($result);

if (isset($_POST['edit_category'])) {
    $name = $_POST['name'];

    $query = "UPDATE categories SET name = '$name' WHERE id = '$id'";
    mysqli_query($conn, $query);
    header('Location: categories.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Edit Category</h1>
    <form action="" method="post">
        <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" class="form-control mb-2" required>
        <button type="submit" name="edit_category" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script src="js/bootstrap.min.js"></script>
</body>
</html>