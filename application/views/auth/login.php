<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Emergency Tools AIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-container img {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            padding: 12px 15px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .input-group-text {
            background: transparent;
            border-right: none;
            border-radius: 12px 0 0 12px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
            margin-bottom: 0;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .alert {
            border-radius: 12px;
            border: none;
        }
        .text-title {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .text-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="<?php echo base_url('assets/img/aisin-logo.png'); ?>" alt="AISIN Logo" class="img-fluid">
                <h3 class="text-title">Emergency Tools AIS</h3>
                <p class="text-subtitle">Silakan login dengan akun Anda</p>
            </div>
            
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            
            <?php echo form_open('auth/login', ['class' => 'login-form']); ?>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user text-muted"></i>
                    </span>
                    <input type="text" class="form-control" name="username" placeholder="Username atau NPK" 
                           value="<?php echo set_value('username'); ?>" required>
                </div>
                <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </span>
                </div>
                <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Masuk
                </button>
            <?php echo form_close(); ?>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    &copy; <?php echo date('Y'); ?> AISIN. All rights reserved.
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.querySelector('input[name="password"]');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>
