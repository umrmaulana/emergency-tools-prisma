<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Inspection Form' ?></title>
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

        .equipment-info {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .checksheet-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .checksheet-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .item-number {
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
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

        .btn-submit {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 12px 40px;
            font-weight: bold;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .status-radio {
            display: none;
        }

        .status-label {
            display: inline-block;
            padding: 8px 20px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            margin-right: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .status-radio:checked+.status-label.ok {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .status-radio:checked+.status-label.not-ok {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .photo-preview {
            max-width: 200px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .standard-image {
            max-width: 150px;
            border-radius: 10px;
            border: 3px solid #28a745;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }

            .item-number {
                width: 35px;
                height: 35px;
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
                        <i class="fas fa-clipboard-list me-2"></i>Inspection Form
                    </h3>
                    <small class="text-muted">Complete equipment inspection</small>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('emergency_tools/index') ?>" class="btn back-btn">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Equipment Information -->
        <div class="equipment-info p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">
                        <i class="fas fa-tools me-2"></i>
                        <?= $equipment->equipment_code ?>
                    </h4>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="opacity-75">Equipment Type:</small>
                            <div class="fw-bold"><?= $equipment->equipment_name ?> (<?= $equipment->equipment_type ?>)
                            </div>
                        </div>
                        <div class="col-md-4">
                            <small class="opacity-75">Location:</small>
                            <div class="fw-bold"><?= $equipment->location_name ?></div>
                        </div>
                        <div class="col-md-4">
                            <small class="opacity-75">Status:</small>
                            <span
                                class="badge bg-<?= $equipment->status == 'active' ? 'success' : ($equipment->status == 'maintenance' ? 'warning' : 'danger') ?> fw-bold">
                                <?= ucfirst($equipment->status) ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <small class="opacity-75">Inspection Date:</small>
                    <div class="fw-bold"><?= date('d M Y, H:i') ?></div>
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
                        <div class="checksheet-item p-4">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="item-number">
                                        <?= $item->order_number ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <h6 class="mb-3 fw-bold"><?= $item->item_name ?></h6>

                                    <!-- Standard Information -->
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <label class="form-label text-muted">Standard Condition:</label>
                                            <p class="fw-bold text-success"><?= $item->standar_condition ?></p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <?php if ($item->standar_picture_url): ?>
                                                <label class="form-label text-muted">Standard Picture:</label><br>
                                                <img src="<?= base_url('assets/emergency_tools/img/' . $item->standar_picture_url) ?>"
                                                    alt="Standard" class="standard-image"
                                                    onclick="showImageModal(this.src, 'Standard Picture')">
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Additional Notes -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Additional Notes</label>
                                            <textarea class="form-control" name="checksheet_items[<?= $item->id ?>][note]"
                                                rows="3" placeholder="Any additional notes or observations..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Status Selection -->
                                    <div class="mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap">
                                            <input type="radio" id="status_ok_<?= $item->id ?>"
                                                name="checksheet_items[<?= $item->id ?>][status]" value="ok"
                                                class="status-radio" required>
                                            <label for="status_ok_<?= $item->id ?>" class="status-label ok">
                                                <i class="fas fa-check me-2"></i>OK
                                            </label>

                                            <input type="radio" id="status_not_ok_<?= $item->id ?>"
                                                name="checksheet_items[<?= $item->id ?>][status]" value="not_ok"
                                                class="status-radio" required>
                                            <label for="status_not_ok_<?= $item->id ?>" class="status-label not-ok">
                                                <i class="fas fa-times me-2"></i>Not OK
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Photo Upload -->
                                    <div class="mb-3">
                                        <label class="form-label">Photo Evidence</label>
                                        <input type="file" class="form-control" name="photo_<?= $item->id ?>" accept="image/*"
                                            capture="environment" onchange="previewImage(this, <?= $item->id ?>)">
                                        <small class="text-muted">Optional - Take or upload a photo as evidence</small>
                                        <div id="preview_<?= $item->id ?>" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">No Checksheet Items</h5>
                        <p class="text-muted">No checksheet items found for this equipment type.</p>
                    </div>
                <?php endif; ?>

                <!-- General Notes -->
                <div class="content-card p-4 mt-4">
                    <h6 class="mb-3">General Notes</h6>
                    <textarea class="form-control" name="notes" rows="4"
                        placeholder="Any general observations, recommendations, or additional comments..."></textarea>
                </div>

                <!-- Submit Button -->
                <?php if (!empty($checksheet_items)): ?>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-submit btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>
                            Submit Inspection
                        </button>
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
        function previewImage(input, itemId) {
            const previewDiv = document.getElementById('preview_' + itemId);
            previewDiv.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'photo-preview mt-2';
                    img.alt = 'Photo Preview';
                    img.onclick = () => showImageModal(e.target.result, 'Photo Preview');
                    previewDiv.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function showImageModal(src, title) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        // Form validation
        document.getElementById('inspectionForm').addEventListener('submit', function (e) {
            const statusInputs = document.querySelectorAll('.status-radio:checked');
            const totalItems = document.querySelectorAll('.checksheet-item').length;

            if (statusInputs.length !== totalItems) {
                e.preventDefault();
                alert('Please select a status for all checksheet items.');
                return false;
            }

            // Confirm submission
            if (!confirm('Are you sure you want to submit this inspection? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });

        // Auto-scroll to next item after status selection
        document.querySelectorAll('.status-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                const currentItem = this.closest('.checksheet-item');
                const nextItem = currentItem.nextElementSibling;

                if (nextItem && nextItem.classList.contains('checksheet-item')) {
                    setTimeout(() => {
                        nextItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 300);
                }
            });
        });
    </script>
</body>

</html>