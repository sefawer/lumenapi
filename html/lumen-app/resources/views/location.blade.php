<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Location Info</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <!-- Bootstrap CSS (Modal için) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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

        /* Tablo için stil */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Location Information</h1>
    
    <div id="map"></div>

    <!-- Tablo -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Marker Color</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $location)
                <tr>
                    <td>{{ $location['name'] }}</td>
                    <td>{{ $location['latitude'] }}</td>
                    <td>{{ $location['longitude'] }}</td>
                    <td>{{ $location['marker_color'] }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" 
                                data-id="{{ $location['id'] }}" 
                                data-name="{{ $location['name'] }}" 
                                data-latitude="{{ $location['latitude'] }}" 
                                data-longitude="{{ $location['longitude'] }}" 
                                data-marker-color="{{ $location['marker_color'] }}">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="editLatitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="editLatitude" name="latitude">
                        </div>
                        <div class="mb-3">
                            <label for="editLongitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="editLongitude" name="longitude">
                        </div>
                        <div class="mb-3">
                            <label for="editMarkerColor" class="form-label">Marker Color</label>
                            <input type="text" class="form-control" id="editMarkerColor" name="marker_color">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Bootstrap JS (Modal için) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios (AJAX istekleri için) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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
            var iconColor = "{{ $location['marker_color'] }}"; // Marker'ın rengi
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

        // Edit Modal'ı doldurma
        document.querySelectorAll('.btn-primary').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('editId').value = this.dataset.id;
                document.getElementById('editName').value = this.dataset.name;
                document.getElementById('editLatitude').value = this.dataset.latitude;
                document.getElementById('editLongitude').value = this.dataset.longitude;
                document.getElementById('editMarkerColor').value = this.dataset.markerColor;
            });
        });

        // Save Changes butonu
        document.getElementById('saveChanges').addEventListener('click', function () {
            const formData = new FormData(document.getElementById('editForm'));

            axios.post(`/locations/update/${formData.get('id')}`, formData)
                .then(response => {
                    alert('Location updated successfully!');
                    window.location.reload(); // Sayfayı yenile
                })
                .catch(error => {
                    alert('Error updating location!');
                    console.error(error);
                });
        });
    </script>
</body>
</html>
