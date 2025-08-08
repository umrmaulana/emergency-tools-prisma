<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Coming Soon' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .coming-soon-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .coming-soon-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 50px 30px;
            max-width: 600px;
            width: 100%;
        }

        .coming-soon-icon {
            font-size: 6rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .coming-soon-title {
            font-size: 3rem;
            font-weight: bold;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .coming-soon-subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .feature-list li {
            background: rgba(102, 126, 234, 0.1);
            margin: 10px 0;
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .feature-list li i {
            color: #667eea;
            margin-right: 10px;
        }

        .back-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            transition: transform 0.3s;
            margin-top: 20px;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .progress-container {
            margin: 30px 0;
        }

        .progress {
            height: 8px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar {
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 10px;
        }

        .module-card {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }

        .module-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .ppic-icon {
            background: linear-gradient(45deg, #007bff, #6610f2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .delivery-icon {
            background: linear-gradient(45deg, #fd7e14, #ffc107);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media (max-width: 768px) {
            .coming-soon-card {
                padding: 30px 20px;
                margin: 20px;
            }

            .coming-soon-title {
                font-size: 2rem;
            }

            .coming-soon-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>

<body>
    <div class="coming-soon-container">
        <div class="coming-soon-card">
            <?php if (isset($module_type) && $module_type === 'ppic'): ?>
                <i class="fas fa-chart-line coming-soon-icon ppic-icon"></i>
                <h1 class="coming-soon-title">PPIC Module</h1>
                <p class="coming-soon-subtitle">Production Planning & Inventory Control system is under development</p>

                <div class="module-card">
                    <h5>Planned Features</h5>
                    <ul class="feature-list">
                        <li><i class="fas fa-calendar-alt"></i> Production Planning & Scheduling</li>
                        <li><i class="fas fa-boxes"></i> Inventory Management</li>
                        <li><i class="fas fa-chart-bar"></i> Production Reports & Analytics</li>
                        <li><i class="fas fa-cogs"></i> Material Requirements Planning</li>
                        <li><i class="fas fa-users"></i> Resource Management</li>
                    </ul>
                </div>

            <?php elseif (isset($module_type) && $module_type === 'delivery'): ?>
                <i class="fas fa-truck coming-soon-icon delivery-icon"></i>
                <h1 class="coming-soon-title">Delivery Module</h1>
                <p class="coming-soon-subtitle">Shipping & Logistics Management system is under development</p>

                <div class="module-card">
                    <h5>Planned Features</h5>
                    <ul class="feature-list">
                        <li><i class="fas fa-shipping-fast"></i> Shipment Tracking</li>
                        <li><i class="fas fa-route"></i> Delivery Route Optimization</li>
                        <li><i class="fas fa-receipt"></i> Delivery Documentation</li>
                        <li><i class="fas fa-map-marked-alt"></i> GPS Tracking</li>
                        <li><i class="fas fa-clipboard-check"></i> Proof of Delivery</li>
                    </ul>
                </div>

            <?php else: ?>
                <i class="fas fa-rocket coming-soon-icon"></i>
                <h1 class="coming-soon-title">Coming Soon</h1>
                <p class="coming-soon-subtitle">This module is currently under development and will be available soon!</p>

                <div class="module-card">
                    <h5>What to Expect</h5>
                    <ul class="feature-list">
                        <li><i class="fas fa-mobile-alt"></i> Mobile-First Design</li>
                        <li><i class="fas fa-shield-alt"></i> Secure & Reliable</li>
                        <li><i class="fas fa-tachometer-alt"></i> High Performance</li>
                        <li><i class="fas fa-users-cog"></i> User-Friendly Interface</li>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="progress-container">
                <div class="d-flex justify-content-between mb-2">
                    <span>Development Progress</span>
                    <span>25%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 25%"></div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="mb-3">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Stay tuned for updates!
                </p>
                <a href="<?= base_url('dashboard') ?>" class="btn back-btn">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            <div class="mt-4 text-muted">
                <small>
                    <i class="fas fa-calendar me-1"></i>
                    Expected Release: Q2 2024
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animate progress bar on load
            setTimeout(() => {
                const progressBar = document.querySelector('.progress-bar');
                progressBar.style.transition = 'width 2s ease-in-out';
                progressBar.style.width = '25%';
            }, 500);

            // Add hover effects to feature list items
            const featureItems = document.querySelectorAll('.feature-list li');
            featureItems.forEach(item => {
                item.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateX(10px)';
                    this.style.transition = 'transform 0.3s ease';
                });

                item.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>

</html>