<!DOCTYPE html>
<html lang="en">
<body>
    <div class="container">
        <div class="main-content">
            <h1>Daily weather forecast information</h1>
            <div class="weather-info">
                <div class="current-weather card">
                    <div>
                        <h3>
                            @if (isset($data['location']['name']) && isset($data['location']['localtime']))
                                {{ $data['location']['name'] }}
                                ({{ date('Y-m-d', strtotime($data['location']['localtime'])) }})
                            @else
                                --
                            @endif
                        </h3>

                        <p>Temperature:
                            @if (isset($data['current']['temp_c']))
                                {{ $data['current']['temp_c'] }}°C
                            @else
                                --
                            @endif
                        </p>
                        <p>Wind:
                            @if (isset($data['current']['wind_mph']))
                                {{ $data['current']['wind_mph'] }} M/S
                            @else
                                --
                            @endif
                        </p>
                        <p>Humidity:
                            @if (isset($data['current']['humidity']))
                                {{ $data['current']['humidity'] }}%
                            @else
                                --
                            @endif
                        </p>
                    </div>
                    <div class="weather-image">
                        @if (isset($data['current']['condition']['icon']))
                            <img src="{{ asset($data['current']['condition']['icon']) }}" alt="Weather Icon" class="weather-icon">
                        @endif
                        <p class="weather-text">
                            @if (isset($data['current']['condition']['text']))
                                {{ $data['current']['condition']['text'] }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
