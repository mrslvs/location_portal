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

    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){
        $locationInput = $_POST['address-input'];
        // echo $locationInput;

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
          
        //   echo "<pre>";
        //   var_dump($apiResult);
        //   echo "</pre>";

          $arr = $apiResult["data"];
          $realResult = $arr[0];
        //   echo "<br><hr><hr><hr><pre>Country is:" . $realResult['country'];

          // extract data to DB
          $latitude = $realResult['latitude'];
          $longitude = $realResult['longitude'];
          $country = $realResult['country'];
          $code = $realResult['country_code'];
          $county = $realResult['region'];


          //insert data to DB
          $sql = 'INSERT INTO visit (user_input, latitude, longitude, country_name, county, alpha_3) VALUES (?, ?, ?, ?, ?, ?)';
          $stmt = $conn->prepare($sql);                      
          $result = $stmt->execute([ $locationInput, $latitude, $longitude, $country, $county, $code ]);

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
                <input type="text" name="address-input" id="address-input" placeholder="Enter your location">
                <input type="submit" value="Enter address" class="btn">
            </form>
        </div>
        <div class="weather">
            <h2>B) Weather for your location:</h2>
            <?php
                function kelvin_to_celsius($given_value){
                    // function from: https://gist.github.com/ugraphix/396166118e472a1385368e7f97f801ae
                    $celsius=$given_value-273.15;
                    return $celsius ;
                }

                if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){
                    $weatherUrl = "https://api.openweathermap.org/data/2.5/onecall?lat=" .$latitude. "&lon=" .$longitude. "&appid=d78f558003ba3879c219caa081143cc8";

                    $cn = curl_init();
                    curl_setopt($cn, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($cn, CURLOPT_URL, $weatherUrl);    // get the contents using url
                    $weatherdataToDecode = curl_exec($cn);
                    curl_close($cn);
                    $weatherdata = json_decode($weatherdataToDecode, true);

                    // weather-location-info
                    $weatherTimeZone = $weatherdata['timezone'];
                    $weatherLat = $weatherdata['lat'];
                    $weatherLon = $weatherdata['lon'];
                    
                    // weather-metrics
                    $weatherTempNowKelvin = $weatherdata['current']['temp'];
                    $weatherTempNowCelsius = kelvin_to_celsius($weatherTempNowKelvin);
                    $weatherHumidity = $weatherdata['current']['humidity'];
                    $weatherWindMPH = $weatherdata['current']['wind_speed'];
                    $weatherWindKMH = $weatherWindMPH * 1.609344;

                    // actual weather
                    $weatherMain = $weatherdata['current']['weather'][0]['main'];
                    $weatherDesc = $weatherdata['current']['weather'][0]['description'];

                    // echo "<pre>";
                    // var_dump($weatherdata['current']['weather'][0]['description']);
                    // echo "</pre>";

                    
                }
            ?>
            <div class="weather-card">
                <div class="weather-location-info">
                <p><?php
                    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){
                    echo $weatherTimeZone ." (" . $weatherLat . ", " . $weatherLon . ")";
                    }
                    ?>
                </p>
                </div>
                <div class="weather-metrics">
                    <p><?php 
                        if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){
                            echo "Temperature: ".$weatherTempNowCelsius . "°C (" . $weatherTempNowKelvin . "°K)";
                        }
                        ?>
                    </p>
                    <p><?php 
                        if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){
                            echo "Humidity: " . $weatherHumidity . "%";
                        }
                        ?>
                    </p>
                    <p><?php 
                        if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){
                            echo "Wind: " . number_format((float)$weatherWindKMH, 2, '.', '') . " KM/H (" . $weatherWindMPH . " MP/H)";
                        }
                        ?>
                    </p>
                </div>
                <div class="weather-actual">
                    <p><?php 
                        if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){
                            echo "What will be the weather like? - " . $weatherMain . " (" . $weatherDesc . ")";
                        }
                        ?></p>
                </div>
            </div>
        </div>
        <div class="location">
            <h2>C) Location details</h2>
            <p>GPS coordinates: <?php if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){ echo "Latitude: ".$latitude.", Longtitude: ".$longitude;} ?></p>
            <p>Country: <?php if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){ echo $country;} ?></p>
            <p>Capital of this country: 
                <?php 
                    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['address-input'])){
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
            <h2>D) Statistics</h2>
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
                        
                        // TABLE OF COUNTRIES

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
                            echo "<th>";
                            echo "<form action=\"country_details.php\" method=\"get\">";
                            echo "<input type=\"hidden\" name=\"kodik\" id=\"kodik\"      value=\" $code \"     >";
                            echo "<input type=\"submit\" value=\" $countryNameToGetFlag \" class=\"input-btn\"  >";
                            echo "</form>";
                            echo "</th>";
                            // echo "<th>". $countryNameWithSpace['name'] . "</th>";

                            // add count
                            echo "<th>" . $num . "</th>";
                            echo "</tr>";
                        }
                        
                        echo "<tr><th colspan=\"2\">Total Visits:</th><th>" . $totalVisists . "</th></tr>";

                        // TIME ZONE VISITS
                        $arr6to15 = array();
                        $arr15to21 = array();
                        $arr21to24 = array();
                        $arr24to6 = array();

                        foreach($visits as $visit){
                            $temp = explode(" ", $visit['created']);
                            $time = explode(":", $temp[1]);

                            $hour = intval($time[0]);
                            
                            if( ($hour > 0) && ($hour < 6) ){
                                array_push($arr24to6, $visit);
                            }else if ( ($hour > 5) && ($hour < 15) ){
                                array_push($arr6to15, $visit);
                            }else if ( ($hour > 14) && ($hour < 21) ){
                                array_push($arr15to21, $visit);
                            }else if ( ($hour > 20) && ($hour < 24) ){
                                array_push($arr21to24, $visit);
                            }

                            // echo "cas: " . $time[0] . " - " . $time[1] . " - " . $time[2] . "<hr>";
                        }

                        echo "<tr>";
                            echo "<th colspan=\"2\">00:00 - 06:00</th>";
                            echo "<th>" . sizeof($arr24to6) . "</th>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th colspan=\"2\">06:00 - 15:00</th>";
                            echo "<th>" . sizeof($arr6to15) . "</th>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th colspan=\"2\">15:00 - 21:00</th>";
                            echo "<th>" . sizeof($arr15to21) . "</th>";
                        echo "</tr>";

                        echo "<tr>";
                            echo "<th colspan=\"2\">21:00 - 24:00</th>";
                            echo "<th>" . sizeof($arr21to24) . "</th>";
                        echo "</tr>";
                    ?>
                </tbody>
            </table>
        </div>
        <div class="map">
            <a href="./map.php" class="show-map-btn">Show map</a>
        </div>
    </div>
</body>
</html>