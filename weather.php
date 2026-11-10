<?php

require_once "config.php";
if ($argc < 2) {
    echo "Usage: php weather.php \"City Name\"\n";
    exit;
}
$city = implode(" ", array_slice($argv, 1));


$url = "https://api.openweathermap.org/data/2.5/weather?q="
     . urlencode($city)
     . "&appid=" . urlencode($apiKey)
     . "&units=metric";
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 15);

$response = curl_exec($curl);

// Check for connection errors
if ($response === false) {
    echo "Connection Error: " . curl_error($curl) . "\n";
    curl_close($curl);
    exit;
}

// Get HTTP response code
$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

$data = json_decode($response, true);

if ($statusCode != 200) {
    echo "Error: " . ($data["message"] ?? "Unable to fetch weather") . "\n";
    exit;
}

echo "\n================================\n";
echo "       WEATHER INFORMATION\n";
echo "================================\n";

echo "City: " . $data["name"] . "\n";
echo "Country: " . $data["sys"]["country"] . "\n";
echo "Temperature: " . $data["main"]["temp"] . "°C\n";
echo "Weather: " . $data["weather"][0]["main"] . "\n";
echo "Description: " . $data["weather"][0]["description"] . "\n";
echo "Humidity: " . $data["main"]["humidity"] . "%\n";
echo "Wind Speed: " . $data["wind"]["speed"] . " m/s\n";

echo "================================\n";

?>