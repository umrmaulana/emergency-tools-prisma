<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Locations' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-fluid {
            padding: 20px;
        }

        .header-card,
        .content-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .header-card {
            margin-bottom: 30px;
        }

        .location-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            margin-bottom: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 4px solid #28a745;
        }

        .location-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .location-code {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .area-badge {
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
        }

        .back-btn {
            background: linear-gradient(45deg, #6c757d, #495057);
            border: none;
            border-radius: 25px;
            color: white;
            padding: 8px 20px;
            transition: transform 0.2s;
        }

        .back-btn:hover {
            transform: scale(1.05);
            color: white;
        }

        .search-box {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 10px 20px;
        }

        .search-box:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .no-locations {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header-card p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0"
                        style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">
                        <i class="fas fa-map-marker-alt me-2"></i>Locations
                    </h3>
                    <small class="text-muted">Equipment locations overview</small>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('emergency_tools/inspector/emergency_tools') ?>" class="btn back-btn">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="content-card p-4 mb-4">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"
                            style="border-radius: 25px 0 0 25px; border: 2px solid #e9ecef; border-right: none;">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control search-box border-start-0" id="searchLocation"
                            placeholder="Search locations..." style="border-radius: 0 25px 25px 0; border-left: none;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Location List -->
        <div class="content-card p-4">
            <div id="locationList">
                <?php if (!empty($locations)): ?>
                    <?php foreach ($locations as $location): ?>
                        <div class="location-item p-3"
                            data-location="<?= strtolower($location->location_name . ' ' . $location->location_code) ?>">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="location-code me-3"><?= $location->location_code ?></span>
                                        <?php if ($location->area_code): ?>
                                            <span class="area-badge"><?= $location->area_code ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h6 class="mb-1 fw-bold"><?= $location->location_name ?></h6>
                                    <?php if ($location->desc): ?>
                                        <p class="mb-0 text-muted small"><?= $location->desc ?></p>
                                    <?php endif; ?>
                                    <?php if ($location->area_x && $location->area_y): ?>
                                        <small class="text-info">
                                            <i class="fas fa-crosshairs me-1"></i>
                                            Coordinates: <?= number_format($location->area_x, 2) ?>,
                                            <?= number_format($location->area_y, 2) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-locations">
                        <i class="fas fa-map-marker-alt" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h5 class="mt-3">No Locations Found</h5>
                        <p class="text-muted">No locations have been registered in the system yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Search functionality
            const searchInput = document.getElementById('searchLocation');
            const locationItems = document.querySelectorAll('.location-item');

            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();

                locationItems.forEach(item => {
                    const locationText = item.getAttribute('data-location');
                    if (locationText.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Add hover effects
            locationItems.forEach(item => {
                item.addEventListener('mouseenter', function () {
                    this.style.borderLeftColor = '#007bff';
                });

                item.addEventListener('mouseleave', function () {
                    this.style.borderLeftColor = '#28a745';
                });
            });
        });
    </script>
</body>

</html>