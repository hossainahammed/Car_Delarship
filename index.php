<?php
include 'connect.php';

// Get current year and cutoff year
$currentYear = date("Y");
$cutoffYear = $currentYear - 5;

$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = trim($_GET['search']);
    $searchTerm = "%" . $search . "%";

    // Prepared statement for search
    $stmt = $conn->prepare("SELECT * FROM cars 
                            WHERE (brand LIKE ? OR model LIKE ?) 
                            AND year >= ? 
                            ORDER BY year DESC");
    $stmt->bind_param("ssi", $searchTerm, $searchTerm, $cutoffYear);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Prepared statement for recent cars
    $stmt = $conn->prepare("SELECT * FROM cars 
                            WHERE year >= ? 
                            ORDER BY year DESC");
    $stmt->bind_param("i", $cutoffYear);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Dealership</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0; padding: 0;
        }
        header {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .search-box {
            text-align: center;
            margin: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
        }
        button {
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .car-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .car-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .car-card:hover {
            transform: scale(1.03);
        }
        .car-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .car-info {
            margin-top: 10px;
            color: #555;
        }
        .rating {
            color: #ff9800;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>🚗 Car Dealership</h1>
        <p>Find Your Perfect Ride</p>
    </header>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search by Brand or Model" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="car-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="car-card">
                    <div class="car-title"><?php echo htmlspecialchars($row['brand'] . " " . $row['model']); ?></div>
                    <div class="car-info">Year: <?php echo $row['year']; ?></div>
                    <div class="rating">⭐ <?php echo $row['rating']; ?>/5</div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">No cars found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
