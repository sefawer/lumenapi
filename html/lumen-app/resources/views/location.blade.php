<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Location Info</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <style>
        /* Harita için stil ayarları */
        #map {
            height: 500px;
            width: 100%;
        }
        
        /* Marker için stil */
        .marker-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: block;
            background-color: red;
            border: 2px solid white;
        }
    </style>
</head>
<body>
    <h1>Location Information</h1>
    
    <div id="map"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Harita oluşturuluyor
        var map = L.map('map');

        // OpenStreetMap tile'larını haritaya ekliyoruz
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Konumlar arasına çizgi eklemek için bir array oluşturuyoruz
        var latLngs = [];

        // LatLngBounds oluşturuyoruz (harita zoom seviyesini ayarlamak için)
        var bounds = L.latLngBounds();

        // Location verisi üzerinden döngü yaparak marker ekliyoruz
        @foreach ($locations as $location)
            // Custom icon ile marker oluşturuluyor
            var iconColor = '{{ $location['markerColor'] }}'; // Marker'ın rengi
            var markerIcon = L.divIcon({
                className: 'marker-icon',
                html: '<div style="background-color: ' + iconColor + '; width: 30px; height: 30px; border-radius: 50%; border: 2px solid white;"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });

            var marker = L.marker([{{ $location['latitude'] }}, {{ $location['longitude'] }}], { icon: markerIcon }).addTo(map);
            
            // Popup ekliyoruz
            marker.bindPopup("<b>{{ $location['name'] }}</b><br>{{ $location['markerColor'] }}").openPopup();

            // Bu konumu polyline'e ekliyoruz
            latLngs.push([{{ $location['latitude'] }}, {{ $location['longitude'] }}]);

            // Konumu bounds'a ekliyoruz
            bounds.extend([{{ $location['latitude'] }}, {{ $location['longitude'] }}]);
        @endforeach

        // Konumlar arasına çizgi ekliyoruz (Polyline)
        L.polyline(latLngs, { color: 'blue' }).addTo(map);

        // Harita zoom seviyesini ve konumları ayarlıyoruz
        map.fitBounds(bounds);
    </script>
</body>
</html>