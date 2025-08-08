<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Emergency Tools' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .scanner-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            margin: 20px;
            padding: 30px;
            text-align: center;
            min-height: calc(100vh - 200px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .scanner-title {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 25px;
            font-size: 1.6rem;
        }

        #scanner-container {
            position: relative;
            max-width: 350px;
            margin: 0 auto 25px;
            border-radius: 15px;
            overflow: hidden;
            background: #000;
        }

        #video {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 15px;
        }

        .scanner-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 220px;
            height: 220px;
            border: 4px solid rgba(102, 126, 234, 0.8);
            border-radius: 15px;
            pointer-events: none;
        }

        .scanner-overlay::before {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border: 3px solid rgba(255, 255, 255, 0.6);
            border-radius: 15px;
            animation: scan 2s ease-in-out infinite;
        }

        @keyframes scan {

            0%,
            100% {
                opacity: 0.4;
            }

            50% {
                opacity: 1;
            }
        }

        .camera-controls {
            margin: 25px 0;
        }

        .camera-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            color: white;
            padding: 12px 25px;
            margin: 8px;
            transition: transform 0.2s;
            font-size: 0.95rem;
        }

        .camera-btn:hover {
            transform: scale(1.05);
            color: white;
        }

        .manual-selection {
            margin-top: 40px;
            padding-top: 40px;
            border-top: 3px solid rgba(102, 126, 234, 0.2);
        }

        .dropdown-container {
            margin: 20px 0;
        }

        .form-select {
            border-radius: 25px;
            border: 2px solid rgba(102, 126, 234, 0.3);
            padding: 15px 25px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
        }

        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 8px;
        }

        .proceed-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            color: white;
            padding: 15px 35px;
            font-size: 1.1rem;
            margin-top: 25px;
            transition: transform 0.3s;
            width: 100%;
            max-width: 300px;
        }

        .proceed-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
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
            font-weight: 500;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
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

        .success-feedback {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid rgba(40, 167, 69, 0.3);
            border-radius: 15px;
            padding: 15px;
            margin: 20px 0;
            color: #155724;
            display: none;
        }

        @media (max-width: 768px) {
            .scanner-container {
                margin: 15px;
                padding: 25px 20px;
            }

            #scanner-container {
                max-width: 280px;
            }

            .scanner-overlay {
                width: 180px;
                height: 180px;
            }

            .scanner-title {
                font-size: 1.3rem;
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
                    <i class="fas fa-tools me-2"></i>Emergency Tools
                </h5>
                <small class="text-muted">Equipment Inspection System</small>
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

    <!-- Main Scanner Container -->
    <div class="scanner-container">
        <h6 class="scanner-title">
            <i class="fas fa-qrcode me-2"></i>Scan Equipment QR Code
        </h6>

        <div id="scanner-container">
            <video id="video" autoplay playsinline></video>
            <div class="scanner-overlay"></div>
        </div>

        <div class="camera-controls">
            <button id="startCamera" class="btn camera-btn">
                <i class="fas fa-camera me-2"></i>Start Camera
            </button>
            <button id="switchCamera" class="btn camera-btn" style="display: none;">
                <i class="fas fa-sync-alt me-2"></i>Switch Camera
            </button>
            <button id="stopCamera" class="btn camera-btn">
                <i class="fas fa-stop me-2"></i>Stop Camera
            </button>
        </div>

        <div class="spinner-custom" id="scannerSpinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Scanning...</span>
            </div>
            <p class="mt-2">Processing QR Code...</p>
        </div>

        <div class="success-feedback" id="successFeedback">
            <i class="fas fa-check-circle me-2"></i>
            <span>QR Code detected successfully!</span>
        </div>

        <!-- Manual Selection -->
        <div class="manual-selection">
            <h6 class="scanner-title">
                <i class="fas fa-hand-pointer me-2"></i>Or Select Equipment Code
            </h6>

            <div class="dropdown-container">
                <label for="equipmentCode" class="form-label">Equipment Code:</label>
                <select class="form-select" id="equipmentCode">
                    <option value="">-- Select Equipment Code --</option>
                    <?php foreach ($equipments as $equipment): ?>
                        <option value="<?= $equipment->id ?>"><?= $equipment->equipment_code ?> -
                            <?= $equipment->equipment_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button id="proceedManual" class="btn proceed-btn" disabled>
                <i class="fas fa-arrow-right me-2"></i>Start Inspection
            </button>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="row g-2">
            <div class="col-6">
                <a href="<?= base_url('emergency_tools') ?>" class="btn nav-btn w-100">
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
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        // Global variables
        let video, canvas, canvasContext, currentStream;
        let selectedEquipmentId = null;
        let isScanning = false;
        let cameras = [];
        let currentCameraIndex = 0;

        document.addEventListener('DOMContentLoaded', function () {
            video = document.getElementById('video');
            canvas = document.createElement('canvas');
            canvasContext = canvas.getContext('2d');

            // Initialize camera list
            initializeCameras();

            // Event listeners
            document.getElementById('startCamera').addEventListener('click', startCamera);
            document.getElementById('switchCamera').addEventListener('click', switchCamera);
            document.getElementById('stopCamera').addEventListener('click', stopCamera);
            document.getElementById('equipmentCode').addEventListener('change', selectEquipment);
            document.getElementById('proceedManual').addEventListener('click', proceedToInspection);

            // Auto-start camera
            startCamera();
        });

        async function initializeCameras() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                cameras = devices.filter(device => device.kind === 'videoinput');

                if (cameras.length > 1) {
                    document.getElementById('switchCamera').style.display = 'inline-block';
                }
            } catch (error) {
                console.error('Error enumerating cameras:', error);
            }
        }

        async function startCamera() {
            try {
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }

                // Prefer rear camera
                const constraints = {
                    video: {
                        facingMode: cameras.length > 0 && currentCameraIndex < cameras.length ?
                            { deviceId: cameras[currentCameraIndex].deviceId } :
                            { facingMode: 'environment' },
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    }
                };

                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = currentStream;

                isScanning = true;
                scanQRCode();

                document.getElementById('startCamera').disabled = true;
                document.getElementById('stopCamera').disabled = false;
            } catch (error) {
                console.error('Error starting camera:', error);
                alert('Unable to access camera. Please check permissions.');
            }
        }

        function stopCamera() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }

            video.srcObject = null;
            isScanning = false;

            document.getElementById('startCamera').disabled = false;
            document.getElementById('stopCamera').disabled = true;
            document.getElementById('scannerSpinner').style.display = 'none';
            document.getElementById('successFeedback').style.display = 'none';
        }

        async function switchCamera() {
            if (cameras.length <= 1) return;

            currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
            if (isScanning) {
                await startCamera();
            }
        }

        function scanQRCode() {
            if (!isScanning || !video.videoWidth || !video.videoHeight) {
                requestAnimationFrame(scanQRCode);
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvasContext.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);
            const qrCode = jsQR(imageData.data, canvas.width, canvas.height);

            if (qrCode) {
                processQRCode(qrCode.data);
                return;
            }

            requestAnimationFrame(scanQRCode);
        }

        function processQRCode(qrData) {
            if (!isScanning) return;

            isScanning = false;
            document.getElementById('scannerSpinner').style.display = 'block';

            fetch('<?= base_url("emergency_tools/process_qr") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'qr_code=' + encodeURIComponent(qrData)
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('scannerSpinner').style.display = 'none';

                    if (data.status === 'success') {
                        document.getElementById('successFeedback').style.display = 'block';
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    } else {
                        alert(data.message);
                        isScanning = true;
                        scanQRCode();
                    }
                })
                .catch(error => {
                    document.getElementById('scannerSpinner').style.display = 'none';
                    console.error('Error processing QR code:', error);
                    alert('Error processing QR code');
                    isScanning = true;
                    scanQRCode();
                });
        }

        function selectEquipment() {
            const equipmentId = document.getElementById('equipmentCode').value;
            selectedEquipmentId = equipmentId;
            document.getElementById('proceedManual').disabled = !selectedEquipmentId;
        }

        function proceedToInspection() {
            if (!selectedEquipmentId) {
                alert('Please select equipment first');
                return;
            }

            // Redirect to inspection form with selected equipment
            window.location.href = `<?= base_url('emergency_tools/inspection_form/') ?>${selectedEquipmentId}`;
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function () {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }
        });
    </script>
</body>

</html>