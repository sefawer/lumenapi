<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Location Info</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #map { height: 500px; width: 100%; }
        .marker-icon {
            width: 30px; height: 30px;
            border-radius: 50%; display: block;
            border: 2px solid white;
        }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1 class="m-3">Location Information</h1>
    <button class="btn btn-success mb-3 ms-3" data-bs-toggle="modal" data-bs-target="#addModal">
        Add New Location
    </button>
    <div id="map" class="m-3"></div>

    <table class="m-3">
        <thead>
            <tr>
                <th>Name</th><th>Latitude</th><th>Longitude</th><th>Marker Color</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $location)
            <tr>
                <td>{{ $location['name'] }}</td>
                <td>{{ $location['latitude'] }}</td>
                <td>{{ $location['longitude'] }}</td>
                <td style="background-color: {{ $location['marker_color'] }}">{{ $location['marker_color'] }}</td>
                <td>
                    <button class="btn btn-primary btn-sm edit-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal" 
                            data-id="{{ $location['id'] }}" 
                            data-name="{{ $location['name'] }}" 
                            data-latitude="{{ $location['latitude'] }}" 
                            data-longitude="{{ $location['longitude'] }}" 
                            data-marker-color="{{ $location['marker_color'] }}">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $location['id'] }}">
                        Delete
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
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLatitude" class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" id="editLatitude" name="latitude" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLongitude" class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" id="editLongitude" name="longitude" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMarkerColor" class="form-label">Marker Color</label>
                            <input type="text" class="form-control" id="editMarkerColor" name="marker_color" required>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="mb-3">
                            <label for="addName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="addName" name="name" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addLatitude" class="form-label">Latitude</label>
                                    <input type="number" step="any" class="form-control" 
                                           id="addLatitude" name="latitude" 
                                           min="-90" max="90" required>
                                    <div class="invalid-feedback">Please enter valid latitude (-90 to 90)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addLongitude" class="form-label">Longitude</label>
                                    <input type="number" step="any" class="form-control" 
                                           id="addLongitude" name="longitude" 
                                           min="-180" max="180" required>
                                    <div class="invalid-feedback">Please enter valid longitude (-180 to 180)</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="addMarkerColor" class="form-label">Marker Color</label>
                            <input type="text" class="form-control" id="addMarkerColor" 
                                   name="marker_color" value="#ff0000" placeholder="#ff0000 or red" required>
                        </div>
                        <div id="addMap" style="height: 300px;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveNew">Save Location</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Axios CSRF Config
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // Main Map Initialization
        const map = L.map('map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        let latLngs = [];
        let bounds = L.latLngBounds();

        // Initialize Markers
        @foreach ($locations as $location)
            const marker{{ $loop->index }} = L.marker(
                [{{ $location['latitude'] }}, {{ $location['longitude'] }}],
                { 
                    icon: L.divIcon({
                        className: 'marker-icon',
                        html: `<div style="background-color: {{ $location['marker_color'] }}; width:30px;height:30px;border-radius:50%;border:2px solid white"></div>`,
                        iconSize: [30, 30],
                        iconAnchor: [15, 30]
                    })
                }
            ).addTo(map).bindPopup("<b>{{ $location['name'] }}</b>");
            
            latLngs.push([{{ $location['latitude'] }}, {{ $location['longitude'] }}]);
            bounds.extend([{{ $location['latitude'] }}, {{ $location['longitude'] }}]);
        @endforeach

        if(latLngs.length > 0) {
            L.polyline(latLngs, { color: 'blue' }).addTo(map);
            map.fitBounds(bounds);
        }

        // Add Modal Functionality
        let addMap, addMarker, manualUpdate = false;

        document.getElementById('addModal').addEventListener('shown.bs.modal', function() {
            const initialLat = parseFloat(document.getElementById('addLatitude').value) || 39.925533;
            const initialLng = parseFloat(document.getElementById('addLongitude').value) || 32.866287;
            
            addMap = L.map('addMap').setView([initialLat, initialLng], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(addMap);

            if(initialLat && initialLng) createMarker([initialLat, initialLng]);

            addMap.on('click', function(e) {
                manualUpdate = true;
                document.getElementById('addLatitude').value = e.latlng.lat.toFixed(6);
                document.getElementById('addLongitude').value = e.latlng.lng.toFixed(6);
                manualUpdate = false;
                updateMarkerPosition();
            });

            document.getElementById('addLatitude').addEventListener('change', updateMarkerFromInputs);
            document.getElementById('addLongitude').addEventListener('change', updateMarkerFromInputs);
        });

        function updateMarkerFromInputs() {
            if(manualUpdate) return;
            const lat = parseFloat(document.getElementById('addLatitude').value);
            const lng = parseFloat(document.getElementById('addLongitude').value);
            
            if(!isNaN(lat) && !isNaN(lng)) {
                if(addMarker) addMarker.setLatLng([lat, lng]);
                else createMarker([lat, lng]);
                addMap.setView([lat, lng], addMap.getZoom());
            }
        }

        function updateMarkerPosition() {
            const lat = document.getElementById('addLatitude').value;
            const lng = document.getElementById('addLongitude').value;
            if(lat && lng) {
                if(addMarker) addMarker.setLatLng([lat, lng]);
                else createMarker([lat, lng]);
            }
        }

        function createMarker(latlng) {
            if(addMarker) addMap.removeLayer(addMarker);
            
            addMarker = L.marker(latlng, {
                icon: L.divIcon({
                    className: 'marker-icon',
                    html: `<div style="background-color: ${document.getElementById('addMarkerColor').value}; width:30px;height:30px;border-radius:50%;border:2px solid white"></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                }),
                draggable: true
            }).addTo(addMap);

            addMarker.on('dragend', function(e) {
                const newPos = e.target.getLatLng();
                manualUpdate = true;
                document.getElementById('addLatitude').value = newPos.lat.toFixed(6);
                document.getElementById('addLongitude').value = newPos.lng.toFixed(6);
                manualUpdate = false;
            });
        }

        // Color Conversion
        const colorNameToHex = (color) => {
            const colors = {
                'red':'#ff0000','blue':'#0000ff','green':'#008000',
                'yellow':'#ffff00','black':'#000000','white':'#ffffff',
                'purple':'#800080','orange':'#ffa500','lime':'#00ff00'
            };
            return colors[color.toLowerCase()] || color;
        };

        document.getElementById('addMarkerColor').addEventListener('input', function(e) {
            const hexColor = colorNameToHex(e.target.value);
            if(addMarker) {
                addMarker.setIcon(L.divIcon({
                    className: 'marker-icon',
                    html: `<div style="background-color: ${hexColor}; width:30px;height:30px;border-radius:50%;border:2px solid white"></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                }));
            }
        });

        // Save New Location
        document.getElementById('saveNew').addEventListener('click', function() {
            const formData = {
                name: document.getElementById('addName').value,
                latitude: document.getElementById('addLatitude').value,
                longitude: document.getElementById('addLongitude').value,
                marker_color: colorNameToHex(document.getElementById('addMarkerColor').value)
            };

            if(!formData.latitude || !formData.longitude) {
                alert('Please select coordinates from the map or enter manually!');
                return;
            }

            axios.post('/location/create', formData)
                .then(() => {
                    alert('Location added successfully!');
                    window.location.reload();
                })
                .catch(error => {
                    alert('Error: ' + error.response.data.message);
                });
        });

        // Edit Functions
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('editId').value = this.dataset.id;
                document.getElementById('editName').value = this.dataset.name;
                document.getElementById('editLatitude').value = this.dataset.latitude;
                document.getElementById('editLongitude').value = this.dataset.longitude;
                document.getElementById('editMarkerColor').value = this.dataset.markerColor;
            });
        });

        document.getElementById('saveChanges').addEventListener('click', function() {
            const formData = {
                id: document.getElementById('editId').value,
                name: document.getElementById('editName').value,
                latitude: document.getElementById('editLatitude').value,
                longitude: document.getElementById('editLongitude').value,
                marker_color: colorNameToHex(document.getElementById('editMarkerColor').value)
            };

            axios.put(`/location/update/${formData.id}`, formData)
                .then(() => {
                    alert('Location updated successfully!');
                    window.location.reload();
                })
                .catch(error => {
                    alert('Error: ' + error.response.data.message);
                });
        });

        // Delete Function
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if(confirm('Are you sure you want to delete this location?')) {
                    axios.delete(`/location/delete/${this.dataset.id}`)
                        .then(() => {
                            alert('Location deleted successfully!');
                            window.location.reload();
                        })
                        .catch(error => {
                            alert('Error: ' + error.response.data.message);
                        });
                }
            });
        });
    </script>
</body>
</html>