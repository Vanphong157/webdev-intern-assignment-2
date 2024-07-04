<!DOCTYPE html>
<html>
<head>
    <title>Current Weather Information</title>
</head>
<body>
    <h1>Current Weather Information</h1>
    <p><strong>Location:</strong> {{ $weatherData['location']['name'] }}</p>
    <p><strong>Local Time:</strong> {{ $weatherData['location']['localtime'] }}</p>
    <p><strong>Temperature:</strong> {{ $weatherData['current']['temp_c'] }}°C</p>
    <p><strong>Wind:</strong> {{ $weatherData['current']['wind_mph'] }} Mph</p>
    <p><strong>Humidity:</strong> {{ $weatherData['current']['humidity'] }}%</p>
</body>
</html>
