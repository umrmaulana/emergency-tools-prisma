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

        .nav-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
            height: 200px;
        }

        .nav-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: inherit;
        }

        .nav-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .nav-icon.location {
            color: #28a745;
        }

        .nav-icon.checksheet {
            color: #007bff;
        }

        .nav-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .nav-subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .back-btn,
        .logout-btn {
            background: linear-gradient(45deg, #6c757d, #495057);
            border: none;
            border-radius: 25px;
            color: white;
            padding: 8px 20px;
            transition: transform 0.2s;
        }

        .logout-btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
        }

        .back-btn:hover,
        .logout-btn:hover {
            transform: scale(1.05);
            color: white;
        }

        .user-info {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }

            .nav-icon {
                font-size: 3rem;
            }

            .nav-title {
                font-size: 1.2rem;
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
                    <h3 class="mb-0 user-info">
                        <i class="fas fa-tools me-2"></i>Emergency Tools
                    </h3>
                    <small class="text-muted">Welcome, <?= $user->name ?> - Choose your action</small>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('emergency_tools/inspector') ?>" class="btn back-btn me-2">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                    <a href="<?= base_url('auth/logout') ?>" class="btn logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Options -->
        <div class="row justify-content-center">
            <!-- Location -->
            <div class="col-md-6 col-lg-5 mb-4">
                <a href="<?= base_url('emergency_tools/inspector/location') ?>" class="nav-card p-4 text-center">
                    <div class="card-body d-flex flex-column justify-content-center h-100">
                        <i class="fas fa-map-marker-alt nav-icon location"></i>
                        <div class="nav-title">Location</div>
                        <div class="nav-subtitle">View and manage equipment locations</div>
                    </div>
                </a>
            </div>

            <!-- Checksheet -->
            <div class="col-md-6 col-lg-5 mb-4">
                <a href="<?= base_url('emergency_tools/inspector/checksheet') ?>" class="nav-card p-4 text-center">
                    <div class="card-body d-flex flex-column justify-content-center h-100">
                        <i class="fas fa-clipboard-check nav-icon checksheet"></i>
                        <div class="nav-title">Checksheet</div>
                        <div class="nav-subtitle">Perform equipment inspection</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="content-card p-4 mt-4">
            <h5 class="mb-3">Quick Information</h5>
            <div class="row text-center">
                <div class="col-3">
                    <i class="fas fa-calendar-check text-primary" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">Today's Date</small>
                        <div class="fw-bold"><?= date('d M Y') ?></div>
                    </div>
                </div>
                <div class="col-3">
                    <i class="fas fa-user-shield text-success" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">Role</small>
                        <div class="fw-bold text-capitalize"><?= $user->level ?></div>
                    </div>
                </div>
                <div class="col-3">
                    <i class="fas fa-id-badge text-info" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">NPK</small>
                        <div class="fw-bold"><?= $user->npk ?></div>
                    </div>
                </div>
                <div class="col-3">
                    <i class="fas fa-clock text-warning" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">Time</small>
                        <div class="fw-bold" id="currentTime"><?= date('H:i') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add click animation to navigation cards
        document.addEventListener('DOMContentLoaded', function () {
            const navCards = document.querySelectorAll('.nav-card');
            navCards.forEach(card => {
                card.addEventListener('click', function () {
                    this.style.transform = 'scale(0.98) translateY(-10px)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Update time every minute
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                document.getElementById('currentTime').textContent = timeString;
            }

            setInterval(updateTime, 60000); // Update every minute
        });
    </script>
</body>

</html>