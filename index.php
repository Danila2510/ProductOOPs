<?php
include 'models/Category.php';
include 'models/Product.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['categories'])) {
    $products1 = [
        new Product("iPhone", 500),
        new Product("MacBook", 1000)
    ];
    $products2 = [
        new Product("Galaxy S10", 300),
        new Product("Galaxy Tab", 600)
    ];
    $products3 = [
        new Product("BMW", 50000),
        new Product("Mercedes", 100000)
    ];

    $_SESSION['categories'] = [
        new Category("Gadgets", $products1),
        new Category("Health", $products2),
        new Category("Cars", $products3)
    ];
}

$categories = $_SESSION['categories'];

function searchCategoryByName($categories, $name) {
    foreach ($categories as $category) {
        if ($category->getCategoryName() === $name) {
            return $category;
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $categoryName = $_POST['product_category'];

    $category = searchCategoryByName($categories, $categoryName);

    if ($category && !empty($productName) && is_numeric($productPrice)) {
        $category->addProduct(new Product($productName, $productPrice));
    }

    $_SESSION['categories'] = $categories;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $categoryName = $_POST['category_name'];

    if (!empty($categoryName)) {
        $newCategory = new Category($categoryName, []);
        $categories[] = $newCategory;
    }

    $_SESSION['categories'] = $categories;
}

$currentCategory = null;
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $currentCategory = searchCategoryByName($categories, $_GET['category']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories and Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        h2 {
            color: #444;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 5px 0;
        }
        a {
            color: #007BFF; /* Синий цвет для ссылок */
            text-decoration: none;
        }
        a:hover {
            color: red; /* Красный цвет при наведении */
            text-decoration: underline;
        }
        form {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 300px;
        }
        form input, form select, form button {
            margin: 10px 0;
            padding: 10px;
            width: calc(100% - 22px); /* Учитываем padding */
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<h2>Categories:</h2>
<ul>
    <?php foreach ($categories as $category): ?>
        <li>
            <a href="?category=<?php echo urlencode($category->getCategoryName()); ?>">
                <?php echo htmlspecialchars($category->getCategoryName()); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php if ($currentCategory): ?>
    <h2>Products in "<?php echo htmlspecialchars($currentCategory->getCategoryName()); ?>"</h2>
    <ul>
        <?php foreach ($currentCategory->getCategoryProducts() as $product): ?>
            <li><?php echo htmlspecialchars($product->getProduct()); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <h2>No category selected</h2>
<?php endif; ?>

<h2>Add Product</h2>
<form method="POST">
    <input type="text" name="product_name" placeholder="Product Name" required>
    <input type="number" name="product_price" placeholder="Product Price" required>
    <select name="product_category" required>
        <option value="" disabled selected>Select Category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo htmlspecialchars($category->getCategoryName()); ?>">
                <?php echo htmlspecialchars($category->getCategoryName()); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="add_product">Add Product</button>
</form>

<h2>Add Category</h2>
<form method="POST">
    <input type="text" name="category_name" placeholder="Category Name" required>
    <button type="submit" name="add_category">Add Category</button>
</form>
</body>
</html>
