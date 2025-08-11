<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-gray: #ecf0f1;
            --dark-gray: #95a5a6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            background: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-good {
            background-color: var(--success-color);
            color: white;
        }

        .status-defect {
            background-color: var(--danger-color);
            color: white;
        }

        .status-missing {
            background-color: var(--warning-color);
            color: white;
        }

        .attachment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .attachment-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }

        .attachment-card:hover {
            border-color: var(--secondary-color);
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .attachment-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            cursor: pointer;
        }

        .attachment-info {
            padding: 1rem;
        }

        .btn-back {
            background: var(--secondary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--primary-color);
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-white">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Inspection Details
                    </h2>
                    <button onclick="history.back()" class="btn btn-back text-white">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </button>
                </div>

                <!-- Inspection Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Inspection Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="info-label">Equipment:</span>
                            <span><?= $inspection->equipment_code ?? 'N/A' ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Location:</span>
                            <span><?= $inspection->location_name ?? 'N/A' ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Inspector:</span>
                            <span><?= $user->fullname ?? $user->username ?? 'N/A' ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Inspection Date:</span>
                            <span><?= date('d/m/Y H:i', strtotime($inspection->inspection_date)) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Overall Status:</span>
                            <span class="status-badge status-<?= strtolower($inspection->overall_status) ?>">
                                <?= $inspection->overall_status ?>
                            </span>
                        </div>
                        <?php if (!empty($inspection->remarks)): ?>
                            <div class="info-row">
                                <span class="info-label">Remarks:</span>
                                <span><?= nl2br(htmlspecialchars($inspection->remarks)) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Checklist Results Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-list-check me-2"></i>
                            Checklist Results
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-row">
                                    <span class="info-label">Condition Check:</span>
                                    <span class="status-badge status-<?= strtolower($inspection->checklist_1) ?>">
                                        <?= $inspection->checklist_1 ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-row">
                                    <span class="info-label">Position Check:</span>
                                    <span class="status-badge status-<?= strtolower($inspection->checklist_2) ?>">
                                        <?= $inspection->checklist_2 ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-row">
                                    <span class="info-label">Cleanliness Check:</span>
                                    <span class="status-badge status-<?= strtolower($inspection->checklist_3) ?>">
                                        <?= $inspection->checklist_3 ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Photo Card -->
                <?php if (!empty($inspection->photo_path)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-camera me-2"></i>
                                Main Photo
                            </h4>
                        </div>
                        <div class="card-body text-center">
                            <img src="<?= base_url($inspection->photo_path) ?>" alt="Main Inspection Photo"
                                class="img-fluid rounded" style="max-height: 400px; cursor: pointer;"
                                onclick="showImageModal('<?= base_url($inspection->photo_path) ?>', 'Main Inspection Photo')">
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Additional Photos Card -->
                <?php if (!empty($attachments)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-images me-2"></i>
                                Additional Photos (<?= count($attachments) ?>)
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="attachment-grid">
                                <?php foreach ($attachments as $attachment): ?>
                                    <?php if (strpos($attachment->mime_type, 'image/') === 0): ?>
                                        <div class="attachment-card">
                                            <img src="<?= base_url($attachment->file_path) ?>"
                                                alt="<?= htmlspecialchars($attachment->file_name) ?>" class="attachment-image"
                                                onclick="showImageModal('<?= base_url($attachment->file_path) ?>', '<?= htmlspecialchars($attachment->file_name) ?>')">
                                            <div class="attachment-info">
                                                <h6 class="mb-2"><?= htmlspecialchars($attachment->file_name) ?></h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-file-image me-1"></i>
                                                    <?= number_format($attachment->file_size / 1024, 1) ?> KB
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?= date('d/m/Y H:i', strtotime($attachment->created_at)) ?>
                                                </small>
                                                <div class="mt-2">
                                                    <a href="<?= base_url('emergency_tools/download_attachment/' . $attachment->id) ?>"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download me-1"></i>Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImageModal(imageSrc, imageTitle) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModalLabel').textContent = imageTitle;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        // Add loading animation
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', function () {
                    this.style.opacity = '1';
                });
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.3s ease';
            });
        });
    </script>
</body>

</html>