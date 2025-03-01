<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'wordrobesystem';
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Add a new clothing item
if (isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];

    $query = "INSERT INTO clothing_items (name, category_id) VALUES ('$name', '$category_id')";
    mysqli_query($conn, $query);
}

// Delete a clothing item
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM clothing_items WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: dashboard.php');
}

// Fetch categories for dropdown
$query = "SELECT * FROM categories";
$categoriesResult = mysqli_query($conn, $query);

// Fetch clothing items with optional filtering and searching
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT clothing_items.id, clothing_items.name, categories.name AS category 
          FROM clothing_items 
          LEFT JOIN categories ON clothing_items.category_id = categories.id 
          WHERE clothing_items.name LIKE '%$searchQuery%'";

if ($categoryFilter) {
    $query .= " AND category_id = '$categoryFilter'";
}

$result = mysqli_query($conn, $query);
$clothingItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $clothingItems[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Wardrobe Management</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Wardrobe Management</h1>

    <!-- Add Clothing Item Form -->
    <form action="" method="post" class="mb-4">
        <div class="row g-3 align-items-center">
            <div>
                    <!-- Link to manage categories -->
                <a href="categories.php" class="btn btn-secondary mt-4">Manage Categories</a>
                    <!-- Link to manage categories -->
            </div>
            <div class="col-md-4">
                <input type="text" name="name" placeholder="Clothing Item Name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php while ($category = mysqli_fetch_assoc($categoriesResult)) { ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" name="add_item" class="btn btn-primary">Add Item</button>
            </div>
        </div>
    </form>

    <!-- Filter and Search -->
    <form action="" method="get" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" placeholder="Search Items" value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php
                    // Reset categories result for dropdown
                    $categoriesResult = mysqli_query($conn, "SELECT * FROM categories");
                    while ($category = mysqli_fetch_assoc($categoriesResult)) { ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($categoryFilter == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Clothing Items Table -->
    <?php if (!empty($clothingItems)) { ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clothingItems as $index => $item) { ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td>
                            <a href="edit_clothes.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                        </td>
                        <td>
                            <!-- Delete Button -->
                            <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No items found.</p>
    <?php } ?>

    <!-- Logout Button -->
    <a href="logout.php" class="btn btn-warning mt-4">Logout</a>

</div>

<script src="js/bootstrap.min.js"></script>
</body>
</html>