package com.example.demo;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.HttpMethod;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.client.RestTemplate;

@RestController
public class WeatherController {

    @Value("${weather.api.url}")
    private String apiUrl;

    @Value("${weather.api.key}")
    private String apiKey;

    @GetMapping("/fetch-weather")
    public String fetchWeather(@RequestParam String location) {
        RestTemplate restTemplate = new RestTemplate();
        String url = apiUrl + "?key=" + apiKey + "&q=" + location;

        ResponseEntity<String> response = restTemplate.exchange(url, HttpMethod.GET, null, String.class);

        return response.getBody();
    }

    @PostMapping("/fetch-weather")
    public String postWeather(@RequestBody LocationRequest locationRequest) {
        RestTemplate restTemplate = new RestTemplate();
        String url = apiUrl + "?key=" + apiKey + "&q=" + locationRequest.getLocation();

        ResponseEntity<String> response = restTemplate.exchange(url, HttpMethod.GET, null, String.class);

        return response.getBody();
    }

    @PostMapping("/fetch-forecast")
    public String fetchForecast(@RequestBody LocationRequest locationRequest) {
        RestTemplate restTemplate = new RestTemplate();
        String url = apiUrl + "/forecast.json?key=" + apiKey + "&q=" + locationRequest.getLocation() + "&days=5";

        ResponseEntity<String> response = restTemplate.exchange(url, HttpMethod.GET, null, String.class);

        return response.getBody();
    }
}

class LocationRequest {
    private String location;

    // Getter and Setter
    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }
}
