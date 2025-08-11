<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Select Location' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet" />
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

        .filter-btn {
            margin: 2px;
        }

        .filter-btn.active {
            background: linear-gradient(45deg, #007bff, #0056b3) !important;
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
        }

        .location-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .location-card.expanded {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.05);
        }

        .equipment-item {
            transition: all 0.2s;
        }

        .equipment-item:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .toggle-equipment {
            cursor: pointer;
            transition: transform 0.3s;
        }

        .toggle-equipment.rotated {
            transform: rotate(180deg);
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

        <!-- Leaflet Map -->
        <div id="map"></div>

        <div class="map-controls">
            <button id="toggleMapping" class="btn map-btn active">
                <i class="fas fa-layer-group me-1"></i>Show Equipment
            </button>
            <button id="fitMapping" class="btn map-btn">
                <i class="fas fa-expand-arrows-alt me-1"></i>Fit to Area
            </button>
            <button id="resetView" class="btn map-btn">
                <i class="fas fa-home me-1"></i>Reset View
            </button>
        </div>

        <!-- Equipment Filter Controls -->
        <div class="map-controls mb-3">
            <button id="showAll" class="btn map-btn filter-btn active" data-filter="all">
                <i class="fas fa-th me-1"></i>All Equipment
            </button>
            <?php
            $displayed_types = [];
            foreach ($equipment_types as $type):
                if (!in_array($type->equipment_type, $displayed_types)):
                    $displayed_types[] = $type->equipment_type;
                    ?>
                    <button class="btn map-btn filter-btn" data-filter="<?= strtolower($type->equipment_type) ?>"
                        data-type-id="<?= $type->id ?>">
                        <i class="fas fa-fire-extinguisher me-1"></i><?= $type->equipment_type ?>
                    </button>
                    <?php
                endif;
            endforeach;
            ?>
        </div>

        <p class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Area mapping with equipment locations - Click markers to view details
        </p>
    </div>

    <!-- Location Selection with Equipment -->
    <div class="location-container">
        <h6 class="map-title">
            <i class="fas fa-building me-2"></i>Location & Equipment Status
        </h6>

        <?php foreach ($locations as $location): ?>
            <div class="location-card" data-location-id="<?= $location->id ?>">
                <div class="row align-items-center mb-3">
                    <div class="col">
                        <h6 class="mb-1"><?= $location->location_name ?></h6>
                        <small class="text-muted">
                            <i class="fas fa-code me-1"></i><?= $location->location_code ?>
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chevron-down text-primary toggle-equipment"
                            data-location="<?= $location->id ?>"></i>
                    </div>
                </div>

                <!-- Equipment List for this Location -->
                <div class="equipment-list" id="equipment-<?= $location->id ?>" style="display: none;">
                    <?php
                    $location_equipments = array_filter($all_equipments, function ($equipment) use ($location) {
                        return $equipment->location_id == $location->id;
                    });

                    if (empty($location_equipments)): ?>
                        <div class="text-muted text-center py-3">
                            <i class="fas fa-info-circle me-1"></i>
                            No equipment found in this location
                        </div>
                    <?php else: ?>
                        <?php foreach ($location_equipments as $equipment): ?>
                            <div class="equipment-item p-3 mb-2"
                                style="background: rgba(102, 126, 234, 0.05); border-radius: 10px; border-left: 4px solid <?= isset($equipment->last_check_date) && strtotime($equipment->last_check_date) > strtotime('-30 days') ? '#28a745' : '#dc3545' ?>;">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($equipment->icon_url) && $equipment->icon_url): ?>
                                                <img src="<?= base_url('uploads/equipment_types/' . $equipment->icon_url) ?>"
                                                    alt="Equipment Icon"
                                                    style="width: 24px; height: 24px; margin-right: 8px; object-fit: contain;"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';" />
                                                <i class="fas fa-wrench text-primary me-2" style="display: none;"></i>
                                            <?php else: ?>
                                                <i class="fas fa-wrench text-primary me-2"></i>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= $equipment->equipment_code ?></strong>
                                                <?php if (isset($equipment->equipment_name) && $equipment->equipment_name): ?>
                                                    <br><small class="text-muted"><?= $equipment->equipment_name ?></small>
                                                <?php endif; ?>
                                                <?php if (isset($equipment->equipment_type) && $equipment->equipment_type): ?>
                                                    <br><span class="badge bg-info text-dark" style="font-size: 0.7rem;">
                                                        <i class="fas fa-tag me-1"></i><?= $equipment->equipment_type ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <span
                                            class="badge <?= isset($equipment->last_check_date) && strtotime($equipment->last_check_date) > strtotime('-30 days') ? 'bg-success' : 'bg-danger' ?>">
                                            <i
                                                class="fas fa-<?= isset($equipment->last_check_date) && strtotime($equipment->last_check_date) > strtotime('-30 days') ? 'check' : 'exclamation-triangle' ?> me-1"></i>
                                            <?= isset($equipment->last_check_date) && strtotime($equipment->last_check_date) > strtotime('-30 days') ? 'OK' : 'Need Check' ?>
                                        </span>
                                    </div>
                                </div>

                                <?php if (isset($equipment->last_check_date) && $equipment->last_check_date): ?>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-calendar me-1"></i>
                                        Last checked: <?= date('d M Y H:i', strtotime($equipment->last_check_date)) ?>
                                    </small>
                                <?php else: ?>
                                    <small class="text-warning d-block mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Never checked
                                    </small>
                                <?php endif; ?>

                                <div class="mt-2">
                                    <a href="<?= base_url('emergency_tools/inspection_form/' . $equipment->id) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-clipboard-check me-1"></i>Inspect
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center mt-4">
            <a href="<?= base_url('emergency_tools/index') ?>" class="btn btn-primary">
                <i class="fas fa-qrcode me-2"></i>Scan QR Code
            </a>
        </div>
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
        let mappingOverlay = null;
        let equipmentMarkers = [];
        let showEquipmentMarkers = true;

        // Define mapping image bounds (adjust these coordinates to match your actual area)
        let imageBounds = [[-6.220, 106.830], [-6.190, 106.860]];

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize map
            initializeMap();

            // Event listeners
            document.getElementById('toggleMapping').addEventListener('click', toggleEquipmentMarkers);
            document.getElementById('fitMapping').addEventListener('click', fitToMappingArea);
            document.getElementById('resetView').addEventListener('click', resetMapView);

            // Filter button event listeners
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    // Remove active from all filter buttons
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    // Add active to clicked button
                    this.classList.add('active');
                    // Filter equipment
                    filterEquipmentByType(this.dataset.filter, this.dataset.typeId);
                });
            });

            // Equipment toggle functionality
            document.querySelectorAll('.toggle-equipment').forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const locationId = this.dataset.location;
                    const equipmentList = document.getElementById(`equipment-${locationId}`);
                    const locationCard = this.closest('.location-card');

                    if (equipmentList.style.display === 'none' || equipmentList.style.display === '') {
                        // Expand
                        equipmentList.style.display = 'block';
                        this.classList.add('rotated');
                        this.classList.remove('fa-chevron-down');
                        this.classList.add('fa-chevron-up');
                        locationCard.classList.add('expanded');
                    } else {
                        // Collapse
                        equipmentList.style.display = 'none';
                        this.classList.remove('rotated');
                        this.classList.remove('fa-chevron-up');
                        this.classList.add('fa-chevron-down');
                        locationCard.classList.remove('expanded');
                    }
                });
            });

            // Auto-expand first location if it has equipment
            const firstLocation = document.querySelector('.toggle-equipment');
            if (firstLocation) {
                const locationId = firstLocation.dataset.location;
                const equipmentList = document.getElementById(`equipment-${locationId}`);
                const hasEquipment = equipmentList && !equipmentList.querySelector('.text-muted.text-center');

                if (hasEquipment) {
                    firstLocation.click();
                }
            }
        });

        function initializeMap() {
            // Calculate image dimensions for proper display (same as master_location)
            var imageWidth = 1000;  // Adjust based on your image width
            var imageHeight = 800;  // Adjust based on your image height
            var bounds = [[0, 0], [imageHeight, imageWidth]];

            // Initialize map without any base layer (same as master_location)
            map = L.map('map', {
                crs: L.CRS.Simple,
                minZoom: -2,
                maxZoom: 3,
                zoomControl: true,
                attributionControl: false
            });

            // Add the Mapping-area.png as the main layer
            mappingOverlay = L.imageOverlay('<?= base_url("assets/emergency_tools/img/Mapping-area.png") ?>', bounds, {
                opacity: 1.0
            }).addTo(map);

            // Fit map to the bounds of the image and set max bounds
            map.fitBounds(bounds);
            map.setMaxBounds(bounds);

            // Set the initial view to show the entire image at maximum zoom out
            map.setView([imageHeight / 2, imageWidth / 2], map.getBoundsZoom(bounds));

            // Add equipment markers
            addEquipmentMarkers();
        }

        function addEquipmentMarkers() {
            const equipmentData = [
                <?php
                // Generate equipment data for JavaScript using proper coordinate system
                $equipmentJsData = [];

                // Process equipment with their actual coordinates from database
                foreach ($all_equipments as $equipment) {
                    // Find location for this equipment
                    $location = null;
                    foreach ($locations as $loc) {
                        if ($loc->id == $equipment->location_id) {
                            $location = $loc;
                            break;
                        }
                    }

                    if ($location) {
                        // Use actual coordinates from database if available
                        if (isset($location->area_x) && isset($location->area_y) && $location->area_x && $location->area_y) {
                            $x = floatval($location->area_x);
                            $y = floatval($location->area_y);
                        } else {
                            // Default position if no coordinates
                            $x = 400;
                            $y = 400;
                        }

                        $equipmentJsData[] = [
                            'id' => $equipment->id,
                            'code' => $equipment->equipment_code,
                            'name' => isset($equipment->equipment_name) ? $equipment->equipment_name : '',
                            'location_id' => $equipment->location_id,
                            'location_name' => $location->location_name,
                            'equipment_type_id' => $equipment->equipment_type_id,
                            'equipment_type' => isset($equipment->equipment_type) ? $equipment->equipment_type : 'Unknown',
                            'icon_url' => isset($equipment->icon_url) ? $equipment->icon_url : '',
                            'last_check' => isset($equipment->last_check_date) ? $equipment->last_check_date : null,
                            'status' => (isset($equipment->last_check_date) && strtotime($equipment->last_check_date) > strtotime('-30 days')) ? 'OK' : 'Need Check',
                            'x' => $x,
                            'y' => $y
                        ];
                    }
                }

                foreach ($equipmentJsData as $index => $equipment) {
                    echo "{";
                    echo "id: " . $equipment['id'] . ",";
                    echo "code: '" . addslashes($equipment['code']) . "',";
                    echo "name: '" . addslashes($equipment['name']) . "',";
                    echo "locationId: " . $equipment['location_id'] . ",";
                    echo "location: '" . addslashes($equipment['location_name']) . "',";
                    echo "equipmentTypeId: " . $equipment['equipment_type_id'] . ",";
                    echo "equipmentType: '" . addslashes($equipment['equipment_type']) . "',";
                    echo "iconUrl: " . ($equipment['icon_url'] ? "'" . addslashes($equipment['icon_url']) . "'" : 'null') . ",";
                    echo "status: '" . $equipment['status'] . "',";
                    echo "lastCheck: " . ($equipment['last_check'] ? "'" . $equipment['last_check'] . "'" : 'null') . ",";
                    echo "x: " . $equipment['x'] . ", y: " . $equipment['y'];
                    echo "}";
                    if ($index < count($equipmentJsData) - 1)
                        echo ",";
                    echo "\n";
                }
                ?>
            ];

            // Clear existing markers
            equipmentMarkers.forEach(marker => map.removeLayer(marker));
            equipmentMarkers = [];

            // Add markers for each equipment
            equipmentData.forEach(equipment => {
                const isOK = equipment.status === 'OK';
                const backgroundColor = isOK ? '#28a745' : '#dc3545';

                // Create icon HTML based on equipment icon or default
                let iconHtml;
                if (equipment.iconUrl) {
                    const iconSrc = 'https://ais.umrmaulana.my.id/assets/emergency_tools/img/equipment/' + equipment.iconUrl;
                    iconHtml = `
                        <div style="
                            background: ${backgroundColor}; 
                            border: 3px solid white; 
                            border-radius: 50%; 
                            width: 30px; 
                            height: 30px; 
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 3px 10px rgba(0,0,0,0.4);
                            position: relative;
                        ">
                            <img src="${iconSrc}" style="
                                width: 18px; 
                                height: 18px; 
                                object-fit: contain;
                            " onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                            <i class="fas fa-wrench" style="
                                color: white; 
                                font-size: 14px;
                                display: none;
                            "></i>
                        </div>
                    `;
                } else {
                    iconHtml = `
                        <div style="
                            background: ${backgroundColor}; 
                            border: 3px solid white; 
                            border-radius: 50%; 
                            width: 30px; 
                            height: 30px; 
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 3px 10px rgba(0,0,0,0.4);
                        ">
                            <i class="fas fa-wrench" style="color: white; font-size: 14px;"></i>
                        </div>
                    `;
                }

                // Create custom icon
                const icon = L.divIcon({
                    className: 'custom-equipment-marker',
                    html: iconHtml,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                    popupAnchor: [0, -15]
                });

                const marker = L.marker([equipment.x, equipment.y], {
                    icon: icon,
                    equipmentTypeId: equipment.equipmentTypeId,
                    equipmentType: equipment.equipmentType
                }).addTo(map);

                // Create popup content
                const popupContent = `
                    <div class="equipment-popup" style="min-width: 220px;">
                        <div class="d-flex align-items-center mb-2">
                            ${equipment.iconUrl ?
                        `<img src="<?= base_url("uploads/equipment_types/") ?>${equipment.iconUrl}" 
                                     style="width: 24px; height: 24px; margin-right: 8px; object-fit: contain;" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';" />
                                 <i class="fas fa-wrench text-primary me-2" style="display: none;"></i>` :
                        `<i class="fas fa-wrench text-primary me-2"></i>`
                    }
                            <strong>${equipment.code}</strong>
                        </div>
                        ${equipment.name ? `<div class="text-muted small mb-2">${equipment.name}</div>` : ''}
                        <div class="small text-muted mb-2">
                            <i class="fas fa-tag me-1"></i><strong>${equipment.equipmentType}</strong>
                        </div>
                        <div class="small text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>${equipment.location}
                        </div>
                        <div class="mb-2">
                            <span class="badge ${isOK ? 'bg-success' : 'bg-danger'}">
                                <i class="fas fa-${isOK ? 'check' : 'exclamation-triangle'} me-1"></i>
                                ${equipment.status}
                            </span>
                        </div>
                        ${equipment.lastCheck ?
                        `<div class="small text-muted mb-2">
                                <i class="fas fa-calendar me-1"></i>
                                Last: ${new Date(equipment.lastCheck).toLocaleDateString('id-ID')}
                            </div>` :
                        `<div class="small text-warning mb-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>Never checked
                            </div>`
                    }
                        <div class="mt-2">
                            <a href="<?= base_url('emergency_tools/inspection_form/') ?>${equipment.id}" 
                               class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-clipboard-check me-1"></i>Inspect Now
                            </a>
                        </div>
                        <div class="mt-1">
                            <button onclick="highlightLocationEquipment(${equipment.locationId})" 
                                    class="btn btn-sm btn-outline-secondary w-100">
                                <i class="fas fa-eye me-1"></i>Show Location Equipment
                            </button>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent, {
                    maxWidth: 250,
                    className: 'equipment-popup-wrapper'
                });

                equipmentMarkers.push(marker);
            });
        } function toggleEquipmentMarkers() {
            const btn = document.getElementById('toggleMapping');
            showEquipmentMarkers = !showEquipmentMarkers;

            equipmentMarkers.forEach(marker => {
                if (showEquipmentMarkers) {
                    marker.addTo(map);
                } else {
                    map.removeLayer(marker);
                }
            });

            btn.innerHTML = `<i class="fas fa-layer-group me-1"></i>${showEquipmentMarkers ? 'Hide' : 'Show'} Equipment`;
            btn.classList.toggle('active', showEquipmentMarkers);
        }

        function fitToMappingArea() {
            if (mappingOverlay) {
                map.fitBounds(mappingOverlay.getBounds());
            }
        }

        function resetMapView() {
            if (mappingOverlay) {
                map.fitBounds(mappingOverlay.getBounds());
            }
        }

        // Function to highlight equipment from specific location
        function highlightLocationEquipment(locationId) {
            // First, close any open popups
            map.closePopup();

            // Expand the location card in the list below
            const locationCard = document.querySelector(`[data-location-id="${locationId}"]`);
            if (locationCard) {
                const toggle = locationCard.querySelector('.toggle-equipment');
                if (toggle && !locationCard.classList.contains('expanded')) {
                    toggle.click();
                }

                // Scroll to the location card
                locationCard.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Highlight the card temporarily
                locationCard.style.transition = 'all 0.3s';
                locationCard.style.borderColor = '#ff6b6b';
                locationCard.style.boxShadow = '0 5px 20px rgba(255, 107, 107, 0.5)';

                setTimeout(() => {
                    locationCard.style.borderColor = '';
                    locationCard.style.boxShadow = '';
                }, 2000);
            }
        }

        // Function to filter equipment by type
        function filterEquipmentByType(filterType, typeId = null) {
            equipmentMarkers.forEach(marker => {
                // Only process equipment markers (skip location markers)
                if (!marker.options.equipmentTypeId) return;

                if (filterType === 'all') {
                    // Show all equipment markers if currently visible
                    if (showEquipmentMarkers) {
                        marker.addTo(map);
                    }
                } else {
                    // Filter by equipment type
                    const markerTypeId = marker.options.equipmentTypeId;
                    const markerType = marker.options.equipmentType.toLowerCase();

                    // Show only matching equipment type
                    if (typeId && markerTypeId == typeId) {
                        // Show markers of this specific type ID
                        if (showEquipmentMarkers) {
                            marker.addTo(map);
                        }
                    } else if (!typeId && markerType === filterType.toLowerCase()) {
                        // Show markers of this general type
                        if (showEquipmentMarkers) {
                            marker.addTo(map);
                        }
                    } else {
                        // Hide markers that don't match
                        map.removeLayer(marker);
                    }
                }
            });
        }        // Custom CSS for equipment popups
        const style = document.createElement('style');
        style.textContent = `
            .equipment-popup-wrapper .leaflet-popup-content-wrapper {
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            }
            .custom-equipment-marker {
                background: transparent !important;
                border: none !important;
            }
            .location-tooltip {
                background: rgba(102, 126, 234, 0.9) !important;
                border: none !important;
                border-radius: 8px !important;
                color: white !important;
                font-weight: 500 !important;
                font-size: 12px !important;
                padding: 4px 8px !important;
            }
            .location-tooltip::before {
                border-top-color: rgba(102, 126, 234, 0.9) !important;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>