<?php
include 'connect.php'; // Ensure this file sets $con correctly

// Get current year and cutoff year
$currentYear = date("Y");
$cutoffYear = $currentYear - 5;

$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = trim($_GET['search']);
    $searchTerm = "%" . mysqli_real_escape_string($con, $search) . "%"; // Escape user input
    $cutoffYear = (int)$cutoffYear; // Force integer type

    // Construct the query for searching cars
    $query = "SELECT * FROM cars 
              WHERE (brand LIKE '$searchTerm' OR model LIKE '$searchTerm') 
              AND year >= $cutoffYear 
              ORDER BY year DESC";
    
    // Execute the query
    $run = mysqli_query($con, $query);
    if (!$run) {
        echo "Failed to run query: " . mysqli_error($con);
        exit(); // Optional: stop script execution
    }
    $result = $run; // Store the result set
} else {
    // Query for recent cars
    $cutoffYear = (int)$cutoffYear; // Force integer type

    // Construct the query for recent cars
    $query = "SELECT * FROM cars 
              WHERE year >= $cutoffYear 
              ORDER BY year DESC";
    
    // Execute the query
    $run = mysqli_query($con, $query);
    if (!$run) {
        echo "Failed to run query: " . mysqli_error($con);
        exit(); // Optional: stop script execution
    }
    $result = $run; // Store the result set
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Dealership</title>
    <link rel="stylesheet" href="style.css">
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
            <input type="text" name="search" placeholder="Search by Brand or Model"
             value="<?php echo htmlspecialchars($search); ?>">
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
