<?php 
    // ============================ CONTENT SOURCE ============================
    // COUNTRY-CAPITAL TABLE FROM: https://www.html-code-generator.com/mysql/country-name-table
    // SMALL FLAGS FORM: https://dynamospanish.com/flags/downloads/
    // ========================================================================
    // ed25c51bb25b270db9e89e5ce42ca165

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

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $locationInput = $_POST['address-input'];
        echo $locationInput;

        // display flag
        // $getFlag = "./flags_style1_small/". $locationInput . ".png";
        // echo "<img src=\"" . $getFlag . "\">";

        // use API to get information from input
        $queryString = http_build_query([
            'access_key' => 'ed25c51bb25b270db9e89e5ce42ca165',
            'query' => $locationInput,
            'output' => 'json',
            'limit' => 1,
          ]);
          
          $ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/forward', $queryString));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          
          $json = curl_exec($ch);
          
          curl_close($ch);
          
          $apiResult = json_decode($json, true);
          
          echo "<pre>";
          var_dump($apiResult);
          echo "</pre>";

          $arr = $apiResult["data"];
          $realResult = $arr[0];
        //   echo "<br><hr><hr><hr><pre>Country is:" . $realResult['country'];

          // extract data to DB
          $latitude = $realResult['latitude'];
          $longitude = $realResult['longitude'];
          $country = $realResult['country'];
          $code = $realResult['country_code'];


          //insert data to DB
          $sql = 'INSERT INTO visit (user_input, latitude, longitude, country_name, alpha_3) VALUES (?, ?, ?, ?, ?)';
          $stmt = $conn->prepare($sql);                      
          $result = $stmt->execute([ $locationInput, $latitude, $longitude, $country, $code ]);

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./styles/styles.css">
    <title>Zadanie 7</title>
</head>
<body>
    <h1>Zadanie 7</h1>
    <div class="container">
        <div class="address">
            <h2>A) Adress input</h2>
            <form action="index.php" method="post">
                <label for="address-input">Input your address</label>
                <input type="text" name="address-input" id="address-input">
                <input type="submit" value="Enter address">
            </form>
        </div>
        <div class="weather">
            <h2>B) Weather for your location:</h2>
        </div>
        <div class="location">
            <h2>C) Location details</h2>
            <p>GPS coordinates: <?php if($_SERVER['REQUEST_METHOD'] == 'POST'){ echo "Latitude: ".$latitude.", Longtitude: ".$longitude;} ?></p>
            <p>Country: <?php if($_SERVER['REQUEST_METHOD'] == 'POST'){ echo $country;} ?></p>
            <p>Capital of this country: 
                <?php 
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        // get capital of this country
                        $sql = 'SELECT capital FROM country WHERE alpha_3 = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([ $code ]);
                        $capital = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo $capital['capital'];
                    }
                ?>
            </p>
        </div>
        <div class="statistics">
            <h2>Statistics</h2>
            <table>
                <thead>
                    <tr>
                        <th>Country flag</th>
                        <th>Country name</th>
                        <th>Count of visits</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        // get visits from database 
                        $sql = 'SELECT * FROM visit';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([]);
                        $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $totalVisists = sizeof($visits);

                        $table = array();
                        foreach($visits as $visit){
                            // go trough all visits
                            
                            // map how many visits there are from each country (based on country-code)
                            $keyToAdd = $visit['alpha_3'];
                            
                            if (array_key_exists($keyToAdd, $table)) {
                                $table[$keyToAdd]++;
                            }else{
                                $table += [$keyToAdd => 1];
                            }
                        }

                        // echo "<hr><pre>";
                        // var_dump($table);
                        // echo "</pre>";

                        foreach($table as $code => $num){
                            echo "<tr>";
                            // add flag

                            // pictures are saved like this: Czech_Republic.png
                            // get country name from database besed on country-code
                            $sql = 'SELECT name FROM country WHERE alpha_3 = ?';
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([ $code ]);
                            $countryNameWithSpace = $stmt->fetch(PDO::FETCH_ASSOC);

                            // echo "Toto je coutnry name so spacom " . $countryNameWithSpace['name'] . "<br>";
                            // replace spaces in name with "_"
                            $countryNameToGetFlag = str_replace(" ", "_", $countryNameWithSpace['name']);
                            // echo "Toto je bez spacu: " . $countryNameToGetFlag . "<hr>";

                            $flagLocation = "./flags_style1_small/" . $countryNameToGetFlag . ".png";
                            echo "<th><img src=\"" . $flagLocation . "\"></th>";

                            // add country name
                            echo "<th>". $countryNameWithSpace['name'] . "</th>";

                            // add count
                            echo "<th>" . $num . "</th>";
                            echo "</tr>";
                        }
                        echo "<tr><th colspan=\"2\">Total Visits:</th><th>" . $totalVisists . "</th></tr>";
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>