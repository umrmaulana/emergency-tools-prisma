<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Inspection Form' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-bg: rgba(255, 255, 255, 0.95);
            --shadow-light: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 8px 25px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --border-radius-lg: 20px;
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 50px;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        .header-card {
            background: var(--light-bg);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-medium);
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
        }

        .equipment-info {
            background: var(--primary-gradient);
            color: white;
            border-radius: var(--border-radius-lg);
            margin-bottom: 25px;
            box-shadow: var(--shadow-medium);
        }

        /* Inspection Form Card */
        .content-card {
            background: var(--light-bg);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-medium);
            backdrop-filter: blur(10px);
        }

        /* Modern Checksheet Items */
        .checksheet-item {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .checksheet-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #007bff, #0056b3);
            transition: width 0.3s ease;
        }

        .checksheet-item:hover::before {
            width: 8px;
        }

        .checksheet-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .checksheet-item.completed {
            border-color: var(--success-color);
            background: linear-gradient(145deg, #f8fff9, #f1f9f2);
        }

        .checksheet-item.completed::before {
            background: var(--success-color);
        }

        /* Item Number Badge */
        .item-number {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            position: relative;
        }

        .item-number::after {
            content: '';
            position: absolute;
            width: 55px;
            height: 55px;
            border: 2px solid rgba(0, 123, 255, 0.2);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }

            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        /* Standard Image Container */
        .standard-container {
            position: relative;
            display: inline-block;
        }

        .standard-image {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: var(--border-radius);
            border: 3px solid var(--success-color);
            box-shadow: var(--shadow-light);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .standard-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--success-color);
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transform: translate(10px, -10px);
            box-shadow: var(--shadow-light);
        }

        /* Status Selection */
        .status-container {
            background: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 20px;
            margin: 15px 0;
        }

        .status-radio {
            display: none;
        }

        .status-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border: 2px solid #e9ecef;
            border-radius: 50px;
            margin: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            min-width: 120px;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .status-label::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            transition: all 0.4s ease;
            transform: translate(-50%, -50%);
        }

        .status-label:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .status-radio:checked+.status-label.ok {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .status-radio:checked+.status-label.ok::before {
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
        }

        .status-radio:checked+.status-label.not-ok {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .status-radio:checked+.status-label.not-ok::before {
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Photo Upload Section */
        .photo-section {
            background: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 20px;
            margin: 15px 0;
            border: 2px dashed #dee2e6;
            transition: all 0.3s ease;
        }

        .photo-section:hover {
            border-color: #007bff;
            background: rgba(0, 123, 255, 0.02);
        }

        .photo-upload-btn {
            background: linear-gradient(135deg, #17a2b8, #138496);
            border: none;
            border-radius: 50px;
            color: white;
            padding: 10px 20px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.3);
        }

        .photo-preview {
            max-width: 180px;
            max-height: 180px;
            object-fit: cover;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border-radius: var(--border-radius);
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: #fff;
        }

        /* Buttons */
        .back-btn {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            border-radius: 50px;
            color: white;
            padding: 10px 24px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
            color: white;
        }

        .btn-submit {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        /* Progress Indicator */
        .progress-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            z-index: 1000;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }

            .item-number {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }

            .standard-image {
                width: 100px;
                height: 100px;
            }

            .status-label {
                min-width: 100px;
                padding: 10px 20px;
                font-size: 14px;
            }

            .checksheet-item {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .status-label {
                width: 100%;
                margin: 5px 0;
            }
        }

        /* Multiple Photos Section */
        .additional-photos-section {
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            border-radius: var(--border-radius);
            padding: 25px;
            margin: 20px 0;
            border: 2px dashed #28a745;
            transition: all 0.3s ease;
        }

        .additional-photos-section:hover {
            border-color: #20c997;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.1);
        }

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .photo-item {
            position: relative;
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }

        .photo-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }

        .photo-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            cursor: pointer;
        }

        .photo-item .photo-actions {
            position: absolute;
            top: 5px;
            right: 5px;
            display: flex;
            gap: 5px;
        }

        .photo-action-btn {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            transition: all 0.2s ease;
        }

        .photo-action-btn.view {
            background: rgba(0, 123, 255, 0.8);
            color: white;
        }

        .photo-action-btn.delete {
            background: rgba(220, 53, 69, 0.8);
            color: white;
        }

        .photo-action-btn:hover {
            transform: scale(1.1);
        }

        .photo-info {
            padding: 8px;
            background: white;
        }

        .photo-info .photo-name {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .photo-info .photo-description {
            font-size: 10px;
            color: #adb5bd;
        }

        .add-photo-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: 2px dashed transparent;
            border-radius: var(--border-radius);
            color: white;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .add-photo-btn:hover {
            background: linear-gradient(135deg, #20c997, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .photo-counter {
            background: var(--info-color);
            color: white;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-left: 10px;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <!-- Progress Indicator -->
    <div class="progress-indicator">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <div class="container-fluid">
        <!-- Header -->
        <div class="header-card p-4 fade-in">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0"
                        style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">
                        <i class="fas fa-clipboard-check me-2"></i>Inspection Form
                    </h3>
                    <small class="text-muted">Complete equipment inspection checklist</small>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('emergency_tools/index') ?>" class="btn back-btn">
                        <i class="fas fa-arrow-left me-2"></i>Back to Scanner
                    </a>
                </div>
            </div>
        </div>

        <!-- Equipment Information -->
        <div class="equipment-info p-4 fade-in">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-3">
                        <i class="fas fa-tools me-2"></i>
                        <?= $equipment->equipment_code ?>
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <small class="opacity-75 d-block">Equipment Type:</small>
                            <div class="fw-bold"><?= $equipment->equipment_name ?></div>
                            <small class="badge bg-light text-dark mt-1"><?= $equipment->equipment_type ?></small>
                        </div>
                        <div class="col-md-4">
                            <small class="opacity-75 d-block">Location:</small>
                            <div class="fw-bold"><?= $equipment->location_name ?></div>
                        </div>
                        <div class="col-md-4">
                            <small class="opacity-75 d-block">Status:</small>
                            <span
                                class="badge bg-<?= $equipment->status == 'active' ? 'success' : ($equipment->status == 'maintenance' ? 'warning' : 'danger') ?> fw-bold fs-6 px-3 py-2">
                                <i
                                    class="fas fa-<?= $equipment->status == 'active' ? 'check-circle' : 'exclamation-triangle' ?> me-1"></i>
                                <?= ucfirst($equipment->status) ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <small class="opacity-75">Inspection Date & Time:</small>
                    <div class="fw-bold fs-5"><?= date('d M Y') ?></div>
                    <div class="fw-bold"><?= date('H:i') ?> WIB</div>
                </div>
            </div>
        </div>

        <!-- Inspection Form -->
        <div class="content-card p-4">
            <form action="<?= base_url('emergency_tools/submit_inspection') ?>" method="post"
                enctype="multipart/form-data" id="inspectionForm">
                <input type="hidden" name="equipment_id" value="<?= $equipment->id ?>">

                <!-- Checksheet Items -->
                <?php if (!empty($checksheet_items)): ?>
                    <?php foreach ($checksheet_items as $index => $item): ?>
                        <div class="checksheet-item p-4 fade-in" data-item-id="<?= $item->id ?>"
                            style="animation-delay: <?= $index * 0.1 ?>s">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="item-number">
                                        <?= $item->order_number ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-4">
                                        <h5 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-check-square me-2"></i><?= $item->item_name ?>
                                        </h5>
                                        <div class="text-muted small">
                                            <i class="fas fa-info-circle me-1"></i>Item <?= $item->order_number ?> of
                                            <?= count($checksheet_items) ?>
                                        </div>
                                    </div>

                                    <!-- Standard Information Card -->
                                    <div class="row mb-4">
                                        <div class="col-md-8">
                                            <div class="bg-light p-3 rounded-3 mb-3">
                                                <label class="form-label text-success fw-bold mb-2">
                                                    <i class="fas fa-star me-1"></i>Standard Condition:
                                                </label>
                                                <p class="fw-bold text-dark mb-0 fs-6"><?= $item->standar_condition ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <?php if ($item->standar_picture_url): ?>
                                                <label class="form-label text-success fw-bold mb-2 d-block">
                                                    <i class="fas fa-image me-1"></i>Standard Reference:
                                                </label>
                                                <div class="standard-container">
                                                    <img src="<?= 'https://ais.umrmaulana.my.id/assets/emergency_tools/img/standars/' . $item->standar_picture_url ?>"
                                                        alt="Standard Reference" class="standard-image"
                                                        onclick="showImageModal(this.src, 'Standard Reference - <?= $item->item_name ?>')">
                                                    <div class="image-overlay">
                                                        <i class="fas fa-search-plus"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block mt-2">Click to enlarge</small>
                                            <?php else: ?>
                                                <div class="text-muted p-3">
                                                    <i class="fas fa-image fa-2x mb-2"></i>
                                                    <small class="d-block">No standard image available</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Status Selection Card -->
                                    <div class="status-container">
                                        <label class="form-label fw-bold mb-3">
                                            <i class="fas fa-clipboard-check me-1"></i>Inspection Status
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex flex-wrap justify-content-center">
                                            <input type="radio" id="status_ok_<?= $item->id ?>"
                                                name="checksheet_items[<?= $item->id ?>][status]" value="ok"
                                                class="status-radio" required>
                                            <label for="status_ok_<?= $item->id ?>" class="status-label ok">
                                                <i class="fas fa-check-circle me-2"></i>OK
                                            </label>

                                            <input type="radio" id="status_not_ok_<?= $item->id ?>"
                                                name="checksheet_items[<?= $item->id ?>][status]" value="not_ok"
                                                class="status-radio" required>
                                            <label for="status_not_ok_<?= $item->id ?>" class="status-label not-ok">
                                                <i class="fas fa-times-circle me-2"></i>Not OK
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Photo Upload Card -->
                                    <div class="photo-section">
                                        <label class="form-label fw-bold mb-3">
                                            <i class="fas fa-camera me-1"></i>Photo Evidence
                                            <small class="text-muted fw-normal">(Optional)</small>
                                        </label>
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <input type="file" class="form-control" name="photo_<?= $item->id ?>"
                                                    id="photo_input_<?= $item->id ?>" accept="image/*" capture="environment"
                                                    onchange="previewImage(this, <?= $item->id ?>)" style="display: none;">
                                                <button type="button" class="photo-upload-btn w-100"
                                                    onclick="document.getElementById('photo_input_<?= $item->id ?>').click()">
                                                    <i class="fas fa-camera me-2"></i>Take Photo
                                                </button>
                                                <small class="text-muted d-block mt-2 text-center">
                                                    Max size: 5MB | Formats: JPG, PNG, GIF
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="preview_<?= $item->id ?>" class="text-center"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Notes Card -->
                                    <div class="bg-light p-3 rounded-3">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-sticky-note me-1"></i>Additional Notes
                                        </label>
                                        <textarea class="form-control" name="checksheet_items[<?= $item->id ?>][note]" rows="3"
                                            placeholder="Any additional observations, findings, or recommendations..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-muted mb-3">No Checksheet Items Available</h4>
                        <p class="text-muted">No inspection items found for this equipment type.</p>
                        <a href="<?= base_url('emergency_tools/index') ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Scanner
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Additional Photos Section -->
                <div class="content-card p-4 mt-4 fade-in">
                    <h5 class="mb-4 text-primary">
                        <i class="fas fa-images me-2"></i>Additional Photos
                        <span class="photo-counter" id="photoCounter">0 Photos</span>
                    </h5>

                    <div class="additional-photos-section">
                        <div class="text-center mb-3">
                            <p class="text-muted mb-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Add additional photos to support your inspection findings
                            </p>
                            <small class="text-muted">
                                Maximum 10 photos | Max size: 5MB each | Formats: JPG, PNG, GIF
                            </small>
                        </div>

                        <div class="photo-grid" id="photoGrid">
                            <div class="add-photo-btn" onclick="triggerPhotoUpload()" id="addPhotoBtn">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span class="fw-bold">Add Photo</span>
                                <small class="mt-1">Click to select or take photo</small>
                            </div>
                        </div>

                        <!-- Hidden file input for additional photos -->
                        <input type="file" id="additionalPhotoInput" accept="image/*" capture="environment" multiple
                            style="display: none;" onchange="handleAdditionalPhotos(this)">
                    </div>
                </div>

                <!-- General Notes Section -->
                <div class="content-card p-4 mt-4 fade-in">
                    <h5 class="mb-4 text-primary">
                        <i class="fas fa-edit me-2"></i>General Inspection Notes
                    </h5>
                    <div class="bg-light p-3 rounded-3">
                        <textarea class="form-control border-0 bg-transparent" name="notes" rows="4"
                            placeholder="Any general observations, recommendations, safety concerns, or additional comments about this equipment inspection..."></textarea>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        Include any overall assessment, maintenance recommendations, or safety observations
                    </small>
                </div>

                <!-- Submit Button Section -->
                <?php if (!empty($checksheet_items)): ?>
                    <div class="text-center mt-5 fade-in">
                        <div class="bg-light p-4 rounded-3 mb-4">
                            <h6 class="text-muted mb-2">Ready to Submit?</h6>
                            <small class="text-muted">
                                Please ensure all items have been inspected and status selected before submitting.
                            </small>
                        </div>
                        <button type="submit" class="btn btn-submit btn-lg" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i>
                            Submit Inspection Report
                        </button>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                This inspection will be recorded and cannot be modified after submission
                            </small>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let completedItems = 0;
        const totalItems = <?= !empty($checksheet_items) ? count($checksheet_items) : 0 ?>;
        let additionalPhotos = [];
        const maxAdditionalPhotos = 10;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            updateProgress();
            initializeAnimations();
            updatePhotoCounter();

            // Add smooth scrolling behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });

        // Additional Photos Management
        function triggerPhotoUpload() {
            if (additionalPhotos.length >= maxAdditionalPhotos) {
                showNotification(`Maximum ${maxAdditionalPhotos} additional photos allowed.`, 'warning');
                return;
            }
            document.getElementById('additionalPhotoInput').click();
        }

        function handleAdditionalPhotos(input) {
            const files = Array.from(input.files);

            if (additionalPhotos.length + files.length > maxAdditionalPhotos) {
                showNotification(`Can only add ${maxAdditionalPhotos - additionalPhotos.length} more photos.`, 'warning');
                return;
            }

            files.forEach((file, index) => {
                // Validate file
                if (file.size > 5 * 1024 * 1024) {
                    showNotification(`File ${file.name} is too large. Max size: 5MB`, 'error');
                    return;
                }

                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showNotification(`File ${file.name} has invalid type. Use JPG, PNG, or GIF.`, 'error');
                    return;
                }

                // Create photo object
                const photoId = Date.now() + index;
                const photoObj = {
                    id: photoId,
                    file: file,
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    description: ''
                };

                additionalPhotos.push(photoObj);

                // Create preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    addPhotoToGrid(photoObj, e.target.result);
                };
                reader.readAsDataURL(file);
            });

            // Clear input
            input.value = '';
            updatePhotoCounter();
        }

        function addPhotoToGrid(photoObj, imageSrc) {
            const photoGrid = document.getElementById('photoGrid');
            const addBtn = document.getElementById('addPhotoBtn');

            const photoItem = document.createElement('div');
            photoItem.className = 'photo-item';
            photoItem.dataset.photoId = photoObj.id;

            photoItem.innerHTML = `
                <img src="${imageSrc}" alt="Additional Photo" onclick="showImageModal('${imageSrc}', 'Additional Photo - ${photoObj.name}')">
                <div class="photo-actions">
                    <button type="button" class="photo-action-btn view" onclick="showImageModal('${imageSrc}', 'Additional Photo - ${photoObj.name}')" title="View">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="photo-action-btn delete" onclick="removeAdditionalPhoto(${photoObj.id})" title="Remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="photo-info">
                    <div class="photo-name">${photoObj.name}</div>
                    <input type="text" class="form-control form-control-sm photo-description-input" 
                           placeholder="Add description..." 
                           onchange="updatePhotoDescription(${photoObj.id}, this.value)"
                           value="${photoObj.description}">
                </div>
            `;

            photoGrid.insertBefore(photoItem, addBtn);

            // Show/hide add button based on limit
            if (additionalPhotos.length >= maxAdditionalPhotos) {
                addBtn.style.display = 'none';
            }

            showNotification('Photo added successfully!', 'success');
        }

        function removeAdditionalPhoto(photoId) {
            if (confirm('Are you sure you want to remove this photo?')) {
                // Remove from array
                additionalPhotos = additionalPhotos.filter(photo => photo.id !== photoId);

                // Remove from DOM
                const photoItem = document.querySelector(`[data-photo-id="${photoId}"]`);
                if (photoItem) {
                    photoItem.remove();
                }

                // Show add button if hidden
                const addBtn = document.getElementById('addPhotoBtn');
                if (additionalPhotos.length < maxAdditionalPhotos) {
                    addBtn.style.display = 'flex';
                }

                updatePhotoCounter();
                showNotification('Photo removed successfully!', 'success');
            }
        }

        function updatePhotoDescription(photoId, description) {
            const photo = additionalPhotos.find(p => p.id === photoId);
            if (photo) {
                photo.description = description;
            }
        }

        function updatePhotoCounter() {
            const counter = document.getElementById('photoCounter');
            counter.textContent = `${additionalPhotos.length} Photo${additionalPhotos.length !== 1 ? 's' : ''}`;

            if (additionalPhotos.length > 0) {
                counter.classList.remove('d-none');
            }
        }

        function previewImage(input, itemId) {
            const previewDiv = document.getElementById('preview_' + itemId);
            previewDiv.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];
                console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);

                // Check file size (5MB limit)
                if (file.size > 5 * 1024 * 1024) {
                    showNotification('File size too large. Please select a file smaller than 5MB.', 'error');
                    input.value = '';
                    return;
                }

                // Check file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showNotification('Invalid file type. Please select JPG, PNG, or GIF.', 'error');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    const container = document.createElement('div');
                    container.className = 'position-relative d-inline-block';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'photo-preview mt-2';
                    img.alt = 'Photo Preview';
                    img.onclick = () => showImageModal(e.target.result, 'Photo Evidence - Item ' + itemId);

                    const overlay = document.createElement('div');
                    overlay.className = 'position-absolute top-0 end-0 bg-success text-white rounded-circle d-flex align-items-center justify-content-center';
                    overlay.style.width = '24px';
                    overlay.style.height = '24px';
                    overlay.style.transform = 'translate(50%, -50%)';
                    overlay.innerHTML = '<i class="fas fa-check" style="font-size: 10px;"></i>';

                    container.appendChild(img);
                    container.appendChild(overlay);
                    previewDiv.appendChild(container);

                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'alert alert-success alert-sm mt-2 p-2';
                    successMsg.innerHTML = `
                        <i class="fas fa-check-circle me-1"></i>
                        <small><strong>Photo uploaded:</strong> ${file.name}</small>
                    `;
                    previewDiv.appendChild(successMsg);

                    // Auto-hide success message
                    setTimeout(() => {
                        if (successMsg.parentNode) {
                            successMsg.remove();
                        }
                    }, 3000);

                    showNotification('Photo uploaded successfully!', 'success');
                };
                reader.readAsDataURL(file);
            }
        }

        function showImageModal(src, title) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        function showNotification(message, type = 'info') {
            const alertClass = type === 'error' ? 'alert-danger' : type === 'success' ? 'alert-success' : 'alert-info';
            const icon = type === 'error' ? 'fas fa-exclamation-triangle' : type === 'success' ? 'fas fa-check-circle' : 'fas fa-info-circle';

            const notification = document.createElement('div');
            notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto-dismiss after 4 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 4000);
        }

        function updateProgress() {
            const progressBar = document.getElementById('progressBar');
            const percentage = totalItems > 0 ? (completedItems / totalItems) * 100 : 0;
            progressBar.style.width = percentage + '%';
        }

        function markItemCompleted(itemId) {
            const item = document.querySelector(`[data-item-id="${itemId}"]`);
            if (item && !item.classList.contains('completed')) {
                item.classList.add('completed');
                completedItems++;
                updateProgress();

                // Add completion animation
                const itemNumber = item.querySelector('.item-number');
                itemNumber.innerHTML = '<i class="fas fa-check"></i>';
                itemNumber.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            }
        }

        function initializeAnimations() {
            // Add scroll reveal animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.checksheet-item').forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(item);
            });
        }

        // Enhanced form validation
        document.getElementById('inspectionForm').addEventListener('submit', function (e) {
            const statusInputs = document.querySelectorAll('.status-radio:checked');
            const totalItems = document.querySelectorAll('.checksheet-item').length;

            // Validation check
            if (statusInputs.length !== totalItems) {
                e.preventDefault();
                showNotification('Please complete all inspection items before submitting.', 'error');

                // Scroll to first incomplete item
                const firstIncompleteItem = document.querySelector('.checksheet-item:not(.completed)');
                if (firstIncompleteItem) {
                    firstIncompleteItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstIncompleteItem.style.borderColor = '#dc3545';
                    setTimeout(() => {
                        firstIncompleteItem.style.borderColor = '';
                    }, 3000);
                }
                return false;
            }

            // Add additional photos to form data
            if (additionalPhotos.length > 0) {
                addAdditionalPhotosToForm();
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

            // Confirmation dialog
            const totalPhotos = document.querySelectorAll('input[type="file"]').length + additionalPhotos.length;
            let confirmMessage = 'Are you sure you want to submit this inspection report?';
            if (totalPhotos > 0) {
                confirmMessage += `\n\nThis includes ${totalPhotos} photo(s).`;
            }
            confirmMessage += '\n\nThis action cannot be undone and the report will be permanently recorded.';

            if (!confirm(confirmMessage)) {
                e.preventDefault();
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return false;
            }

            // Debug file uploads
            const fileInputs = document.querySelectorAll('input[type="file"]');
            let uploadCount = 0;
            fileInputs.forEach(input => {
                if (input.files.length > 0) {
                    uploadCount++;
                    console.log('File selected for ' + input.name + ':', input.files[0].name, 'Size:', input.files[0].size);
                }
            });

            if (additionalPhotos.length > 0) {
                uploadCount += additionalPhotos.length;
                console.log(`Additional photos: ${additionalPhotos.length}`);
            }

            if (uploadCount > 0) {
                showNotification(`Submitting inspection with ${uploadCount} photo(s)...`, 'info');
            }

            // Let form submit naturally
            return true;
        });

        function addAdditionalPhotosToForm() {
            const form = document.getElementById('inspectionForm');

            // Remove any existing additional photo inputs
            const existingInputs = form.querySelectorAll('input[name^="additional_photos"]');
            existingInputs.forEach(input => input.remove());

            // Add additional photos as hidden inputs
            additionalPhotos.forEach((photo, index) => {
                // Create a file input for each photo
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.name = `additional_photos[${index}]`;
                fileInput.style.display = 'none';

                // Create a new FileList with the single file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(photo.file);
                fileInput.files = dataTransfer.files;

                form.appendChild(fileInput);

                // Add description as hidden input
                if (photo.description) {
                    const descInput = document.createElement('input');
                    descInput.type = 'hidden';
                    descInput.name = `additional_photo_descriptions[${index}]`;
                    descInput.value = photo.description;
                    form.appendChild(descInput);
                }
            });
        }

        // Enhanced status selection handling
        document.querySelectorAll('.status-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                const itemId = this.name.match(/\[(\d+)\]/)[1];
                const currentItem = this.closest('.checksheet-item');

                // Mark item as completed
                markItemCompleted(itemId);

                // Visual feedback
                const statusContainer = this.closest('.status-container');
                statusContainer.style.background = this.value === 'ok' ?
                    'rgba(40, 167, 69, 0.1)' : 'rgba(220, 53, 69, 0.1)';

                // Auto-scroll to next item
                const nextItem = currentItem.nextElementSibling;
                if (nextItem && nextItem.classList.contains('checksheet-item')) {
                    setTimeout(() => {
                        nextItem.scrollIntoView({ behavior: 'smooth', block: 'center' });

                        // Highlight next item briefly
                        nextItem.style.borderColor = '#007bff';
                        nextItem.style.boxShadow = '0 0 15px rgba(0, 123, 255, 0.3)';
                        setTimeout(() => {
                            nextItem.style.borderColor = '';
                            nextItem.style.boxShadow = '';
                        }, 2000);
                    }, 500);
                } else {
                    // All items completed, scroll to submit button
                    setTimeout(() => {
                        document.getElementById('submitBtn').scrollIntoView({ behavior: 'smooth', block: 'center' });
                        showNotification('All items completed! Ready to submit inspection.', 'success');
                    }, 500);
                }
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            // Alt + S to submit (if all items completed)
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                if (completedItems === totalItems && totalItems > 0) {
                    document.getElementById('submitBtn').click();
                }
            }

            // Escape to scroll to top
            if (e.key === 'Escape') {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // Add floating action button for quick navigation
        if (totalItems > 3) {
            const fab = document.createElement('div');
            fab.className = 'position-fixed';
            fab.style.cssText = 'bottom: 30px; right: 30px; z-index: 1000;';
            fab.innerHTML = `
                <div class="btn-group-vertical">
                    <button type="button" class="btn btn-primary btn-sm rounded-circle mb-2" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Scroll to top">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-success btn-sm rounded-circle" onclick="document.getElementById('submitBtn').scrollIntoView({behavior: 'smooth'})" title="Go to submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(fab);
        }
    </script>
</body>

</html>