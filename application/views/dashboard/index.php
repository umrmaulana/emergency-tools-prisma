<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Main Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            padding: 20px;
        }

        .header-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .menu-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: inherit;
        }

        .menu-card.active:hover {
            transform: translateY(-15px);
        }

        .menu-card.inactive {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .menu-card.inactive:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .menu-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
        }

        .menu-icon.emergency {
            background: linear-gradient(45deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-icon.ppic {
            background: linear-gradient(45deg, #007bff, #6610f2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-icon.delivery {
            background: linear-gradient(45deg, #fd7e14, #ffc107);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .menu-subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .coming-soon-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .user-info {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .logout-btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 25px;
            color: white;
            padding: 8px 20px;
            transition: transform 0.2s;
        }

        .logout-btn:hover {
            transform: scale(1.05);
            color: white;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }

            .menu-icon {
                font-size: 2.5rem;
            }

            .menu-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header-card p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0 user-info">Welcome, <?= $user->name ?></h3>
                    <small class="text-muted">Main Dashboard - AIS System</small>
                </div>
                <div class="col-auto">
                    <span class="badge bg-success me-3">
                        <i class="fas fa-user-shield me-1"></i>
                        <?= ucfirst($user->level) ?>
                    </span>
                    <a href="<?= base_url('auth/logout') ?>" class="btn logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Menu -->
        <div class="row justify-content-center">
            <!-- Emergency Tools -->
            <div class="col-md-4 mb-4">
                <a href="<?= base_url('dashboard/emergency_tools') ?>" class="menu-card active p-4 text-center">
                    <div class="card-body d-flex flex-column justify-content-center h-100">
                        <i class="fas fa-tools menu-icon emergency"></i>
                        <div class="menu-title">Emergency Tools</div>
                        <div class="menu-subtitle">Equipment inspection & management</div>
                    </div>
                </a>
            </div>

            <!-- PPIC -->
            <div class="col-md-4 mb-4">
                <a href="<?= base_url('dashboard/ppic') ?>" class="menu-card inactive p-4 text-center"
                    onclick="return false;">
                    <div class="coming-soon-badge">Coming Soon</div>
                    <div class="card-body d-flex flex-column justify-content-center h-100">
                        <i class="fas fa-chart-line menu-icon ppic"></i>
                        <div class="menu-title">PPIC</div>
                        <div class="menu-subtitle">Production Planning & Control</div>
                    </div>
                </a>
            </div>

            <!-- Delivery -->
            <div class="col-md-4 mb-4">
                <a href="<?= base_url('dashboard/delivery') ?>" class="menu-card inactive p-4 text-center"
                    onclick="return false;">
                    <div class="coming-soon-badge">Coming Soon</div>
                    <div class="card-body d-flex flex-column justify-content-center h-100">
                        <i class="fas fa-truck menu-icon delivery"></i>
                        <div class="menu-title">Delivery</div>
                        <div class="menu-subtitle">Shipping & logistics management</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Information -->
        <div class="info-card p-4">
            <h5 class="mb-3">System Information</h5>
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-3">
                    <i class="fas fa-calendar-check text-primary" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">Today</small>
                        <div class="fw-bold"><?= date('d M Y') ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <i class="fas fa-clock text-success" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">Time</small>
                        <div class="fw-bold" id="currentTime"><?= date('H:i') ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <i class="fas fa-id-badge text-info" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">NPK</small>
                        <div class="fw-bold"><?= $user->npk ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <i class="fas fa-building text-warning" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">Company</small>
                        <div class="fw-bold">AIS</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="info-card p-4 mt-4">
            <h6 class="mb-3">Module Status</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">Active</span>
                        <span>Emergency Tools</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2">Soon</span>
                        <span>PPIC Module</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2">Soon</span>
                        <span>Delivery Module</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add click animation to active menu cards
            const menuCards = document.querySelectorAll('.menu-card.active');
            menuCards.forEach(card => {
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

            setInterval(updateTime, 60000);

            // Show tooltip for inactive cards
            const inactiveCards = document.querySelectorAll('.menu-card.inactive');
            inactiveCards.forEach(card => {
                card.addEventListener('click', function (e) {
                    e.preventDefault();
                    alert('This module is coming soon!');
                    return false;
                });
            });
        });
    </script>
</body>

</html>