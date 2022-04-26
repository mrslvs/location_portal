<?php
    $cCode = trim($_GET['kodik']); // country code

    // debugging, delete afterwards
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // DB connection
    require_once("conf.php");

    // connect to DB
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connectionStatus = "You have established connection to the database.";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    $sql = 'SELECT * FROM visit WHERE alpha_3 = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([ $cCode ]);
    $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo "<pre>";
    // var_dump($visits);
    // echo "</pre>";

    // get country name
    $i = 0;
    foreach($visits as $visit){
        if ($i==0){
            $countryName = $visit['country_name'];
        }
        $i++;
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country details</title>
</head>
<body>
    <h1>Details about <?php echo $countryName;?></h1>
    <div class="container">
        <?php
            foreach($visits as $visit){
                echo "<div class=\"country-detail-row\">";
                echo "<p>";
                echo "Location searched: " . $visit['user_input'] . " (" . $visit['latitude'] . ", " . $visit['longitude'] . ") at " . $visit['created'];
                echo "</p>";
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>