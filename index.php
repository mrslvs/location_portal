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
          echo "<br><hr><hr><hr><pre>Country is:" . $realResult['country'];

          // extract data to DB
          $latitude = $realResult['latitude'];
          $longitude = $realResult['longitude'];
          $country = $realResult['country'];
          $code = $realResult['country_code'];

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
            <p>GPS coordinates: <?php ?></p>
            <p>Country: <?php ?></p>
            <p>Capital of this country: <?php ?></p>
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
                    <tr>
                        <th>Flag</th>
                        <th>Slovensko</th>
                        <th>1</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>