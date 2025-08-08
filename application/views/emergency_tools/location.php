<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Select Location' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet" />
    </style>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 100px;
        }

        .header-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 20px;
            margin-bottom: 30px;
        }

        .map-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            margin: 20px;
            padding: 30px;
            text-align: center;
        }

        .map-title {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 25px;
            font-size: 1.4rem;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .map-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .map-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 20px;
            color: white;
            padding: 8px 16px;
            font-size: 0.85rem;
            transition: transform 0.2s;
        }

        .map-btn:hover {
            transform: scale(1.05);
            color: white;
        }

        .map-btn.active {
            background: linear-gradient(45deg, #28a745, #20c997);
        }

        .location-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            margin: 20px;
            padding: 30px;
        }

        .dropdown-container {
            margin: 15px 0;
        }

        .form-select {
            border-radius: 25px;
            border: 2px solid rgba(102, 126, 234, 0.3);
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .location-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            margin: 10px 0;
            padding: 15px;
            border: 2px solid transparent;
            transition: all 0.3s;
            cursor: pointer;
        }

        .location-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .location-card.selected {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }

        .proceed-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            margin-top: 20px;
            transition: transform 0.3s;
            width: 100%;
        }

        .proceed-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .proceed-btn:disabled {
            background: #6c757d;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            color: white;
            padding: 12px 0;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
            font-size: 0.95rem;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            color: white;
        }

        .nav-btn i {
            margin-right: 8px;
        }

        .alert-custom {
            border-radius: 15px;
            border: none;
            margin: 20px;
        }

        .spinner-custom {
            display: none;
            margin: 20px 0;
        }

        @media (max-width: 768px) {
            .map-container {
                margin: 15px;
                padding: 20px;
            }

            #map {
                height: 300px;
            }

            .location-container {
                margin: 15px;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header-card p-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </div>
            <div class="col">
                <h5 class="mb-0" style="color: #667eea; font-weight: bold;">
                    <i class="fas fa-map-marker-alt me-2"></i>Select Location
                </h5>
                <small class="text-muted">Emergency Tools - Inspector</small>
            </div>
            <div class="col-auto">
                <span class="badge bg-success">
                    <i class="fas fa-user me-1"></i><?= $user->name ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-custom">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-custom">
            <i class="fas fa-check-circle me-2"></i>
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Map Area -->
    <div class="map-container">
        <h6 class="map-title">
            <i class="fas fa-map me-2"></i>Area Mapping
        </h6>
        <div id="map"></div>

        <div class="map-controls">
            <button id="toggleMapping" class="btn map-btn active">
                <i class="fas fa-layer-group me-1"></i>Hide Mapping
            </button>
            <button id="fitMapping" class="btn map-btn">
                <i class="fas fa-expand-arrows-alt me-1"></i>Fit to Area
            </button>
            <button id="resetView" class="btn map-btn">
                <i class="fas fa-home me-1"></i>Reset View
            </button>
        </div>

        <p class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Area mapping view - Click markers or location cards to select (Zoom limited for optimal viewing)
        </p>
    </div>

    <!-- Location Selection -->
    <div class="location-container">
        <h6 class="map-title">
            <i class="fas fa-building me-2"></i>Select Location
        </h6>

        <?php foreach ($locations as $location): ?>
            <div class="location-card" data-location-id="<?= $location->id ?>">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-1"><?= $location->location_name ?></h6>
                        <small class="text-muted">
                            <i class="fas fa-code me-1"></i><?= $location->location_code ?>
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chevron-right text-primary"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <button id="proceedLocation" class="btn proceed-btn" disabled>
            <i class="fas fa-arrow-right me-2"></i>Go to Checksheet
        </button>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="row g-2">
            <div class="col-6">
                <a href="<?= base_url('emergency_tools/index') ?>" class="btn nav-btn w-100">
                    <i class="fas fa-clipboard-check"></i>Checksheet
                </a>
            </div>
            <div class="col-6">
                <a href="<?= base_url('emergency_tools/location') ?>" class="btn nav-btn w-100">
                    <i class="fas fa-map-marker-alt"></i>Location
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Global variables
        let map;
        let selectedLocationId = null;
        let mappingOverlay = null;
        let imageBounds = [[-6.220, 106.830], [-6.190, 106.860]];

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize map
            initializeMap();

            // Event listeners
            document.getElementById('proceedLocation').addEventListener('click', proceedToChecksheet);
            document.getElementById('toggleMapping').addEventListener('click', toggleMappingOverlay);
            document.getElementById('fitMapping').addEventListener('click', fitToMappingArea);
            document.getElementById('resetView').addEventListener('click', resetMapView);

            // Location selection
            document.querySelectorAll('.location-card').forEach(card => {
                card.addEventListener('click', function () {
                    selectLocation(this.dataset.locationId);
                });
            });
        });

        function initializeMap() {
            // Initialize the map centered on mapping area with restricted zoom
            map = L.map('map', {
                minZoom: 13,
                maxZoom: 17,
                zoomControl: true
            }).setView([-6.205, 106.845], 14);

            // Don't add any tile layer - only show the custom mapping image

            // Add custom mapping area image overlay
            const mappingImageUrl = '<?= base_url("assets/emergency_tools/img/Mapping-area.png") ?>';

            mappingOverlay = L.imageOverlay(mappingImageUrl, imageBounds, {
                opacity: 1.0,
                interactive: true
            }).addTo(map);

            // Add markers for each location within the mapping area bounds
            <?php foreach ($locations as $index => $location): ?>
                // Distribute locations within the mapping area
                let lat = <?= isset($location->latitude) ? $location->latitude : (-6.220 + ($index * 0.005)) ?>;
                let lng = <?= isset($location->longitude) ? $location->longitude : (106.835 + ($index * 0.005)) ?>;

                let marker<?= $location->id ?> = L.marker([lat, lng], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                    })
                }).addTo(map);

                marker<?= $location->id ?>.bindPopup(`
                    <div class="text-center p-2">
                        <strong><?= $location->location_name ?></strong><br>
                        <small class="text-muted"><?= $location->location_code ?></small><br>
                        <button class="btn btn-sm btn-primary mt-2" onclick="selectLocationFromMap(<?= $location->id ?>)">
                            <i class="fas fa-check me-1"></i>Select Location
                        </button>
                    </div>
                `);
            <?php endforeach; ?>

            // Fit map to show the mapping area and disable further zoom out
            map.fitBounds(imageBounds);

            // Disable zoom out beyond the image bounds
            map.on('zoomend', function () {
                if (map.getZoom() < 13) {
                    map.setZoom(13);
                }
            });

            // Try to get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    // Add user location marker only if within mapping area
                    const mappingBounds = L.latLngBounds(imageBounds);
                    if (mappingBounds.contains([userLat, userLng])) {
                        L.marker([userLat, userLng], {
                            icon: L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                            })
                        }).addTo(map).bindPopup('<strong>Your Current Location</strong>');
                    }
                }, function (error) {
                    console.log('Geolocation error:', error);
                });
            }
        }

        function toggleMappingOverlay() {
            // Since we're only showing the mapping image now, this function is not needed
            // But we'll keep it for UI consistency
            const btn = document.getElementById('toggleMapping');
            if (mappingOverlay && map.hasLayer(mappingOverlay)) {
                map.removeLayer(mappingOverlay);
                btn.innerHTML = '<i class="fas fa-layer-group me-1"></i>Show Mapping';
                btn.classList.remove('active');
            } else {
                if (mappingOverlay) {
                    mappingOverlay.addTo(map);
                }
                btn.innerHTML = '<i class="fas fa-layer-group me-1"></i>Hide Mapping';
                btn.classList.add('active');
            }
        }

        function fitToMappingArea() {
            map.fitBounds(imageBounds);
            // Ensure minimum zoom level
            if (map.getZoom() < 13) {
                map.setZoom(13);
            }
        }

        function resetMapView() {
            map.setView([-6.205, 106.845], 14);
        }

        function selectLocationFromMap(locationId) {
            selectLocation(locationId);
            map.closePopup();

            // Scroll to location card
            const locationCard = document.querySelector(`[data-location-id="${locationId}"]`);
            if (locationCard) {
                locationCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function selectLocation(locationId) {
            selectedLocationId = locationId;

            // Update UI
            document.querySelectorAll('.location-card').forEach(card => {
                card.classList.remove('selected');
            });

            const selectedCard = document.querySelector(`[data-location-id="${locationId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }

            document.getElementById('proceedLocation').disabled = false;
        }

        function proceedToChecksheet() {
            if (!selectedLocationId) {
                alert('Please select location first');
                return;
            }

            window.location.href = `<?= base_url('emergency_tools/checksheet/') ?>${selectedLocationId}`;
        }
    </script>
</body>

</html>