<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Inspector Dashboard' ?></title>
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

        .welcome-card {
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
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: inherit;
        }

        .menu-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .menu-subtitle {
            color: #666;
            font-size: 0.9rem;
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

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }

            .menu-icon {
                font-size: 3rem;
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
        <div class="welcome-card p-4">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0 user-info">Welcome, <?= $user->name ?></h3>
                    <small class="text-muted">Inspector Dashboard - Emergency Tools System</small>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('auth/logout') ?>" class="btn logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Menu -->
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="<?= base_url('emergency_tools/inspector/emergency_tools') ?>" class="menu-card p-4 text-center">
                    <div class="card-body d-flex flex-column justify-content-center h-100">
                        <i class="fas fa-tools menu-icon"></i>
                        <div class="menu-title">Emergency Tools</div>
                        <div class="menu-subtitle">Access inspection and location tools</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="welcome-card p-4 mt-4">
            <h5 class="mb-3">Quick Information</h5>
            <div class="row text-center">
                <div class="col-4">
                    <i class="fas fa-calendar-check text-primary" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">Today's Date</small>
                        <div class="fw-bold"><?= date('d M Y') ?></div>
                    </div>
                </div>
                <div class="col-4">
                    <i class="fas fa-user-shield text-success" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">Role</small>
                        <div class="fw-bold text-capitalize"><?= $user->level ?></div>
                    </div>
                </div>
                <div class="col-4">
                    <i class="fas fa-id-badge text-info" style="font-size: 2rem;"></i>
                    <div class="mt-2">
                        <small class="text-muted">NPK</small>
                        <div class="fw-bold"><?= $user->npk ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function () {
            // Add click animation to menu cards
            const menuCards = document.querySelectorAll('.menu-card');
            menuCards.forEach(card => {
                card.addEventListener('click', function () {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
        });
    </script>
</body>

</html>