<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];

// Fetch the clothing item to edit
$query = "SELECT * FROM clothing_items WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (isset($_POST['edit_item'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];

    $query = "UPDATE clothing_items SET name = '$name', category_id = '$category_id' WHERE id = '$id'";
    mysqli_query($conn, $query);
    header('Location: dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Clothing Item</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Edit Clothing Item</h1>
    <form action="" method="post">
        <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" class="form-control mb-2" required>
        <select name="category_id" class="form-select mb-2" required>
            <?php
            $categoriesResult = mysqli_query($conn, "SELECT * FROM categories");
            while ($category = mysqli_fetch_assoc($categoriesResult)) {
                $selected = ($category['id'] == $item['category_id']) ? 'selected' : '';
                echo "<option value='" . $category['id'] . "' $selected>" . $category['name'] . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="edit_item" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script src="js/bootstrap.min.js"></script>
</body>
</html>