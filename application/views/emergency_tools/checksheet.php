<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Checksheet' ?></title>
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

        .action-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .action-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .qr-icon {
            color: #28a745;
        }

        .dropdown-icon {
            color: #007bff;
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

        .select-equipment {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px;
        }

        .select-equipment:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
        }

        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
        }

        .equipment-info {
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #007bff;
        }

        .qr-scanner {
            display: none;
            text-align: center;
        }

        .camera-container {
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        #video {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }

            .action-icon {
                font-size: 2.5rem;
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
                        <i class="fas fa-clipboard-check me-2"></i>Checksheet
                    </h3>
                    <small class="text-muted">Select equipment to inspect</small>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('emergency_tools/inspector/emergency_tools') ?>" class="btn back-btn">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Action Selection -->
        <div class="row justify-content-center">
            <!-- QR Code Scanner -->
            <div class="col-md-6 col-lg-5 mb-4">
                <div class="action-card p-4 text-center" onclick="toggleQRScanner()">
                    <i class="fas fa-qrcode action-icon qr-icon"></i>
                    <h5 class="mb-2">Scan QR Code</h5>
                    <p class="text-muted mb-0">Use camera to scan equipment QR code</p>
                </div>
            </div>

            <!-- Equipment Dropdown -->
            <div class="col-md-6 col-lg-5 mb-4">
                <div class="action-card p-4 text-center" onclick="toggleDropdown()">
                    <i class="fas fa-list action-icon dropdown-icon"></i>
                    <h5 class="mb-2">Select Equipment</h5>
                    <p class="text-muted mb-0">Choose from equipment list</p>
                </div>
            </div>
        </div>

        <!-- QR Scanner Section -->
        <div class="content-card p-4 qr-scanner" id="qrScanner">
            <h5 class="mb-3 text-center">QR Code Scanner</h5>
            <div class="camera-container">
                <video id="video" autoplay playsinline></video>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-danger me-2" onclick="stopScanner()">
                    <i class="fas fa-stop me-2"></i>Stop Scanner
                </button>
                <button type="button" class="btn btn-secondary" onclick="switchCamera()" id="switchBtn"
                    style="display:none;">
                    <i class="fas fa-sync me-2"></i>Switch Camera
                </button>
            </div>

            <!-- Manual QR Input -->
            <div class="mt-4">
                <label class="form-label">Or enter QR code manually:</label>
                <form action="<?= base_url('emergency_tools/inspector/process_qr') ?>" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" name="qr_code" placeholder="Enter QR code" required>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Equipment Selection Section -->
        <div class="content-card p-4" id="equipmentSelection" style="display:none;">
            <h5 class="mb-3">Select Equipment</h5>

            <form action="<?= base_url('emergency_tools/inspector/inspection_form') ?>" method="post"
                id="equipmentForm">
                <div class="row">
                    <div class="col-md-8">
                        <select class="form-select select-equipment" name="equipment_id" id="equipmentSelect" required
                            onchange="showEquipmentInfo()">
                            <option value="">Choose Equipment...</option>
                            <?php if (!empty($equipments)): ?>
                                <?php foreach ($equipments as $equipment): ?>
                                    <option value="<?= $equipment->id ?>" data-code="<?= $equipment->equipment_code ?>"
                                        data-location="<?= $equipment->location_name ?>"
                                        data-type="<?= $equipment->equipment_name ?> (<?= $equipment->equipment_type ?>)">
                                        <?= $equipment->equipment_code ?> - <?= $equipment->equipment_name ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100" disabled id="inspectBtn">
                            <i class="fas fa-clipboard-check me-2"></i>Start Inspection
                        </button>
                    </div>
                </div>
            </form>

            <!-- Equipment Information -->
            <div id="equipmentInfo" class="equipment-info p-3 mt-3" style="display:none;">
                <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Equipment Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Equipment Code:</small>
                        <div class="fw-bold" id="infoCode">-</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Location:</small>
                        <div class="fw-bold" id="infoLocation">-</div>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Type:</small>
                    <div class="fw-bold" id="infoType">-</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        let video, canvas, context;
        let currentStream = null;
        let cameras = [];
        let currentCameraIndex = 0;

        function toggleQRScanner() {
            const scanner = document.getElementById('qrScanner');
            const dropdown = document.getElementById('equipmentSelection');

            if (scanner.style.display === 'none' || scanner.style.display === '') {
                scanner.style.display = 'block';
                dropdown.style.display = 'none';
                startScanner();
            } else {
                scanner.style.display = 'none';
                stopScanner();
            }
        }

        function toggleDropdown() {
            const scanner = document.getElementById('qrScanner');
            const dropdown = document.getElementById('equipmentSelection');

            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
                scanner.style.display = 'none';
                stopScanner();
            } else {
                dropdown.style.display = 'none';
            }
        }

        async function startScanner() {
            video = document.getElementById('video');
            canvas = document.createElement('canvas');
            context = canvas.getContext('2d');

            try {
                // Get available cameras
                const devices = await navigator.mediaDevices.enumerateDevices();
                cameras = devices.filter(device => device.kind === 'videoinput');

                if (cameras.length > 1) {
                    document.getElementById('switchBtn').style.display = 'inline-block';
                }

                // Start with rear camera if available
                const constraints = {
                    video: {
                        facingMode: cameras.length > 1 ? 'environment' : 'user'
                    }
                };

                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = currentStream;

                // Start scanning
                requestAnimationFrame(scanQR);

            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Unable to access camera. Please make sure you have granted permission.');
            }
        }

        function scanQR() {
            if (video && video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    // QR code found
                    stopScanner();
                    processQRCode(code.data);
                    return;
                }
            }

            if (currentStream) {
                requestAnimationFrame(scanQR);
            }
        }

        function processQRCode(qrData) {
            // Send QR data to server
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('emergency_tools/inspector/process_qr') ?>';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'qr_code';
            input.value = qrData;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        function stopScanner() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }
            document.getElementById('qrScanner').style.display = 'none';
        }

        async function switchCamera() {
            if (cameras.length < 2) return;

            stopScanner();
            currentCameraIndex = (currentCameraIndex + 1) % cameras.length;

            try {
                const constraints = {
                    video: { deviceId: cameras[currentCameraIndex].deviceId }
                };

                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = currentStream;
                requestAnimationFrame(scanQR);
            } catch (err) {
                console.error('Error switching camera:', err);
            }
        }

        function showEquipmentInfo() {
            const select = document.getElementById('equipmentSelect');
            const info = document.getElementById('equipmentInfo');
            const btn = document.getElementById('inspectBtn');

            if (select.value) {
                const option = select.options[select.selectedIndex];
                document.getElementById('infoCode').textContent = option.dataset.code;
                document.getElementById('infoLocation').textContent = option.dataset.location;
                document.getElementById('infoType').textContent = option.dataset.type;

                info.style.display = 'block';
                btn.disabled = false;
            } else {
                info.style.display = 'none';
                btn.disabled = true;
            }
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function () {
            stopScanner();
        });
    </script>
</body>

</html>