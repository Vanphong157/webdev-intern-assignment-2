import React, { useState, useEffect } from "react";
import "./App.css";
import { Button, Container, Grid, Box, Typography, Input } from "@mui/material";

function App() {
  const [location, setLocation] = useState("");
  const [weatherData, setWeatherData] = useState(null);
  const [forecastData, setForecastData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchWeather = async (location) => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch("http://localhost:8080/fetch-weather", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ location }),
      });
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      const data = await response.json();
      setWeatherData(data);
    } catch (error) {
      setError(error.toString());
    }
    setLoading(false);
  };

  const fetchForecast = async (location) => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch("http://localhost:8080/fetch-forecast", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ location }),
      });
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      const data = await response.json();
      setForecastData(data.forecast.forecastday);
      console.log(data);
    } catch (error) {
      setError(error.toString());
    }
    setLoading(false);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    fetchWeather(location);
    fetchForecast(location);
  };

  const handleUseCurrentLocation = () => {
    if (navigator.geolocation) {
      console.log("Using current location...");
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const { latitude, longitude } = position.coords;
          const location = `${latitude},${longitude}`;
          fetchWeather(location);
          fetchForecast(location);
        },
        (error) => {
          setError(error.message);
        }
      );
    } else {
      console.error("Geolocation is not supported by this browser.");
      setError("Geolocation is not supported by this browser.");
    }
  };

  useEffect(() => {
    handleUseCurrentLocation();
  }, []);

  return (
    <Container maxWidth="lg">
      <Grid
        container
        sx={{
          backgroundColor: "#5372F0",
          padding: 2,
          textAlign: "center",
          display: "flex",
          justifyContent: "center",
        }}
      >
        <Typography color={"white"} variant="h4">
          Weather Dashboard
        </Typography>
      </Grid>
      <Grid container backgroundColor={"#E3F2FD"} paddingTop={2}>
        <Grid
          item
          md={4}
          padding={2}
          display={"flex"}
          justifyContent={"flex-start"}
          flexDirection={"column"}
        >
          <Box sx={{ mb: 2 }}>
            <Typography fontWeight={800}>Enter a country</Typography>
          </Box>
          <Box sx={{ mb: 2 }}>
            <Input
              type="text"
              value={location}
              onChange={(e) => setLocation(e.target.value)}
              placeholder="E.g London Hanoi"
              sx={{ width: "100%" }}
            />
          </Box>
          <Box sx={{ mb: 2 }}>
            <Button
              onClick={handleSubmit}
              variant="contained"
              color="primary"
              sx={{
                width: "100%",
                "&:hover": {
                  backgroundColor: "#0056b3",
                },
              }}
            >
              Search
            </Button>
          </Box>
          <Box sx={{ display: "flex", alignItems: "center", mb: 2 }}>
            <Box sx={{ flex: 1, height: "1px", backgroundColor: "#D9E4EC" }} />
            <Typography sx={{ color: "#758088", mx: 2 }}>or</Typography>
            <Box sx={{ flex: 1, height: "1px", backgroundColor: "#D9E4EC" }} />
          </Box>
          <Box sx={{ mb: 2 }}>
            <Button
              onClick={handleUseCurrentLocation}
              variant="contained"
              sx={{
                width: "100%",
                backgroundColor: "#6C757D",
                "&:hover": {
                  backgroundColor: "#5a6268",
                },
              }}
            >
              Use Current Location
            </Button>
          </Box>
        </Grid>
        <Grid item md={8}>
          <Grid
            display={"flex"}
            backgroundColor={"#5372F0"}
            margin={2}
            padding={1}
            borderRadius={3}
          >
            {weatherData && (
              <>
                <Grid item md={6}>
                  <Box display={"flex"} alignItems={"center"}>
                    <Typography variant="h6" color={"white"}>
                      {weatherData.location.name}
                    </Typography>
                    <Typography variant="h6" color={"white"}>
                      ({weatherData.location.localtime})
                    </Typography>
                  </Box>
                  <Box>
                    <Typography color={"white"}>
                      Temperature: {weatherData.current.temp_c}°C
                    </Typography>
                  </Box>
                  <Box>
                    <Typography color={"white"}>
                      Wind: {weatherData.current.wind_mph} Mph
                    </Typography>
                  </Box>
                  <Box>
                    <Typography color={"white"}>
                      Humidity: {weatherData.current.humidity}%
                    </Typography>
                  </Box>
                </Grid>
                <Grid item md={6} display={"flex"} justifyContent={"center"}>
                  <Box>
                    {weatherData.current && weatherData.current.condition && (
                      <>
                        <img
                          src={weatherData.current.condition.icon}
                          alt={weatherData.current.condition.text}
                          style={{ width: 100 }}
                        />
                        <Typography color={"white"} textAlign={"center"}>
                          {weatherData.current.condition.text}
                        </Typography>
                      </>
                    )}
                  </Box>
                </Grid>
              </>
            )}
          </Grid>
          <Grid margin={2}>
            <Typography fontWeight={800} variant="h6">
              4-Day Forecast
            </Typography>
          </Grid>
          <Grid display={"flex"}>
            {forecastData &&
              forecastData.slice(1, 5).map((day, index) => (
                <Grid
                  item
                  key={index}
                  md={3}
                  backgroundColor={"#6C757D"}
                  margin={2}
                  padding={1}
                  borderRadius={2}
                >
                  <Box>
                    <Typography color={"white"}>{day.date}</Typography>
                    <img
                      src={day.day.condition.icon}
                      alt={day.day.condition.text}
                      style={{ width: 50 }}
                    />
                    <Typography color={"white"}>
                      Temp: {day.day.avgtemp_c}°C
                    </Typography>
                    <Typography color={"white"}>
                      Wind: {day.day.maxwind_mph} Mph
                    </Typography>
                    <Typography color={"white"}>
                      Humidity: {day.day.avghumidity}%
                    </Typography>
                  </Box>
                </Grid>
              ))}
          </Grid>
        </Grid>
      </Grid>
    </Container>
  );
}

export default App;
