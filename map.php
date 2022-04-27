<?php
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

    $sql = 'SELECT latitude FROM visit';
    $stmt = $conn->prepare($sql);
    $stmt->execute([  ]);
    $latitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'SELECT longitude FROM visit';
    $stmt = $conn->prepare($sql);
    $stmt->execute([  ]);
    $longitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo sizeof($longitudes) . "  and " . sizeof($latitudes);
    // echo "<pre>";
    // var_dump($latitudes);
    // echo "</pre>";

    // echo "<hr>toto je len prva latitude: " . $latitudes[0]['latitude'];

    $arrLatitudes = array();
    $arrLongitudes = array();
    $numOfP = sizeof($latitudes);

    foreach($latitudes as $lat){
        array_push($arrLatitudes, $lat['latitude']);
    }

    foreach($longitudes as $lon){
        array_push($arrLongitudes, $lon['longitude']);
    }

    // $points = array();

    // for($i=0; $i < sizeof($longitudes); $i++){
    //     $points += [$latitudes[$i]['latitude'] => $longitudes[$i]['longitude']];
    // }
    
    // echo "<pre>";
    // var_dump($arrLatitudes);
    // echo "</pre>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css" type="text/css">
    <style>
      .map {
        height: 400px;
        width: 100%;
      }
    </style>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"></script>
    <title>Map</title>
</head>
<body>
    <h1>Visits have been made from following locations:</h1>

    <div id="map" class="map"></div>

    <script type="text/javascript">

        // get all points from PHP
        let arrLat = <?php echo json_encode($arrLatitudes); ?>;
        let arrLon = <?php echo json_encode($arrLongitudes); ?>;
        let numOfPoints = <?php echo json_encode($numOfP); ?>

        // console.log(arrLat);
        // console.log(arrLon);
        // console.log(numOfPoints);
        // arrLat.forEach(thing => console.log(thing));
        // console.log(arrLat[2]);

        // ================ MAP ================
        var map = new ol.Map({
            target: 'map',
            
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            
            view: new ol.View({
                center: ol.proj.fromLonLat([27.41, 48.82]),
                zoom: 4
            })
        });

        // ================ MARKERS ================
        const markersMap = new Map();
        const markersArr = [];
        for (let i = 0; i < numOfPoints; i++) {
            markersMap.set(arrLat[i], arrLon[i]);

            let temp = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([arrLat[i], arrLon[i]])),   
            });

            markersArr.push(temp);
        }

        console.log(markersArr);

        let mark1 = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.fromLonLat([4.35247, 50.84673])),   
        });

        let mark2 = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.fromLonLat([15.35247, 40.84673]))   
        });

        var layer = new ol.layer.Vector({
            source: new ol.source.Vector({
                features: [
                    mark1, mark2
                ]
            })
        });

        map.addLayer(layer); // add markers to map
    </script>

</body>
</html>