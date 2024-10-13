<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>
    <h1>Weather Dashboard</h1>
    <div class="main-content">
        <div class="search-card">
            <h3>Enter City Name</h3>
            <form action="{{ route('weather.form') }}" method="post">
                @csrf
                <input type="text" name="city" id="city" placeholder="E.g., New York, London, Tokyo">
                <button class="search-btn" type="submit">Search</button>
                <div class="separator"></div>
            </form>
            <form id="locationForm" action="{{ route('weather.form') }}" method="post">
                @csrf
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <button class="location-btn" onclick="getLocation()">Use Current Location</button>
            </form>

            <button class="historyWeather" id="dashboard-btn">Receive daily weather forecast information</button>
            <button class="historyWeather" onclick="showWeatherLog()">Show History Weather</button>
            <div id="weather-log" style="display: none;"></div>

        </div>

        <div class="weather-info">
            <div class="current-weather">
                <div class="details">
                    <h2>
                        @if (isset($data['location']['name']) && isset($data['location']['localtime']))
                            {{ $data['location']['name'] }}
                            ({{ date('Y-m-d', strtotime($data['location']['localtime'])) }})
                        @else
                            --
                        @endif
                    </h2>

                    <h4>Temperature:
                        @if (isset($data['current']['temp_c']))
                            {{ $data['current']['temp_c'] }}°C
                        @else
                            --
                        @endif
                    </h4>
                    <h4>Wind:
                        @if (isset($data['current']['wind_mph']))
                            {{ $data['current']['wind_mph'] }} M/S
                        @else
                            --
                        @endif
                    </h4>
                    <h4>Humidity:
                        @if (isset($data['current']['humidity']))
                            {{ $data['current']['humidity'] }}%
                        @else
                            --
                        @endif
                    </h4>
                </div>
                <div class="icon">
                    @if (isset($data['current']['condition']['icon']))
                        <img src="{{ asset($data['current']['condition']['icon']) }}" alt="weather-icon" class="weather-icon">
                    @endif
                    <h4 class="weather-text">
                        @if (isset($data['current']['condition']['text']))
                            {{ $data['current']['condition']['text'] }}
                        @endif
                    </h4>
                </div>
            </div>

            <div class="forecast">
                <h2>14-Day Forecast</h2>
                <ul class="weather-cards">
                    @for ($i = 0; $i < 14; $i++)
                        <li class="card {{ $i >= 4 ? 'additional-forecasts' : '' }}" style="{{ $i >= 4 ? 'display: none;' : '' }}">
                            <h3>
                                @if (isset($dataFuture['forecast']['forecastday'][$i]['date']))
                                    {{ $dataFuture['forecast']['forecastday'][$i]['date'] }}
                                @else
                                    --
                                @endif
                            </h3>

                            <div class="icon">
                                @if (isset($dataFuture['forecast']['forecastday'][$i]['day']['condition']['icon']))
                                    <img src="{{ asset($dataFuture['forecast']['forecastday'][$i]['day']['condition']['icon']) }}" alt="Weather Icon">
                                @endif
                            </div>
                            <h3>Temp:
                                @if (isset($dataFuture['forecast']['forecastday'][$i]['day']['avgtemp_c']))
                                    {{ $dataFuture['forecast']['forecastday'][$i]['day']['avgtemp_c'] }}
                                @else
                                    --
                                @endif
                                °C
                            </h3>
                            <h3>Wind:
                                @if (isset($dataFuture['forecast']['forecastday'][$i]['day']['maxwind_mph']))
                                    {{ $dataFuture['forecast']['forecastday'][$i]['day']['maxwind_mph'] }}
                                @else
                                    --
                                @endif
                                M/S
                            </h3>
                            <h3>Humidity:
                                @if (isset($dataFuture['forecast']['forecastday'][$i]['day']['avghumidity']))
                                    {{ $dataFuture['forecast']['forecastday'][$i]['day']['avghumidity'] }}
                                @else
                                    --
                                @endif
                                %
                            </h3>
                        </li>
                    @endfor
                </ul>

                <div class="see-more-container">
                    <button id="toggle-button" class="see-more-btn" onclick="toggleMore()">See More</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('dashboard-btn').addEventListener('click', function() {
  
            @if (Auth::check())
                window.location.href = "{{ route('dashboard') }}"; 
            @else
                window.location.href = "{{ route('login') }}"; p
            @endif
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
     
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;

            document.getElementById('locationForm').submit();
        }

        function toggleMore() {
            const additionalForecasts = document.querySelectorAll('.additional-forecasts');
            const toggleButton = document.getElementById('toggle-button');

            if (toggleButton.innerText === 'See More') {
                additionalForecasts.forEach(card => {
                    card.style.display = 'block';
                });
                toggleButton.innerText = 'Retract';
            } else {
                additionalForecasts.forEach(card => {
                    card.style.display = 'none';
                });
                toggleButton.innerText = 'See More';
            }
        }

        function storeWeatherLog(city, temp, date) {
            const logData = JSON.parse(localStorage.getItem('weatherLog')) || [];
            logData.push({
                city,
                temp,
                date
            });
            localStorage.setItem('weatherLog', JSON.stringify(logData));
        }

        @if (isset($data['location']['name']) && isset($data['current']['temp_c']) && isset($data['location']['localtime']))
            const city = "{{ $data['location']['name'] }}";
            const temp = "{{ $data['current']['temp_c'] }}";
            const date = "{{ date('Y-m-d', strtotime($data['location']['localtime'])) }}";
            storeWeatherLog(city, temp, date);
        @endif

        function showWeatherLog() {
    const logDiv = document.getElementById('weather-log');
    const logData = JSON.parse(localStorage.getItem('weatherLog')) || [];

    logDiv.innerHTML = logData.map(log => `
        <div class="weather-log-entry">
            <h4>${log.city}</h4>
            <p>Temperature: <strong>${log.temp}°C</strong></p>
            <p>Date: <strong>${log.date}</strong></p>
        </div>
    `).join('');

    logDiv.style.display = logDiv.style.display === 'none' ? 'block' : 'none';
}

    </script>
</body>

</html>
