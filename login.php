<?php
require_once 'config.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
$role = $_GET['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            
            // Update last login
            $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Redirect based on role
            if ($user['role'] === 'manager' || $user['role'] === 'admin') {
                redirect('dashboard.php');
            } elseif ($user['role'] === 'cashier') {
                redirect('cashier.php');
            } elseif ($user['role'] === 'cook') {
                redirect('kitchen.php');
            }
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

$lang = getLang();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h2><?php echo APP_NAME; ?></h2>
                <p style="color: var(--gray-500);"><?php echo $lang === 'sw' ? 'Ingia kwenye mfumo' : 'Sign in to system'; ?></p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-error" style="margin-bottom: 1rem;">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label"><?php echo $lang === 'sw' ? 'Jina la Mtumiaji' : 'Username'; ?></label>
                    <input type="text" name="username" class="form-input" required autofocus>
                </div>
                
                <div class="form-group">
                    <label class="form-label"><?php echo $lang === 'sw' ? 'Neno la Siri' : 'Password'; ?></label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <?php __('login'); ?>
                </button>
            </form>
            
            <div style="margin-top: 1.5rem; text-align: center; font-size: 0.875rem; color: var(--gray-500);">
                <p><?php echo $lang === 'sw' ? 'Akaunti za Mfano:' : 'Demo Accounts:'; ?></p>
                <p>manager / password123</p>
                <p>cashier1 / password123</p>
                <p>cook1 / password123</p>
            </div>
            
            <div style="margin-top: 1.5rem; text-align: center;">
                <a href="index.php" style="color: var(--gray-500); font-size: 0.875rem;">
                    ← <?php echo $lang === 'sw' ? 'Rudi kwa Menyu' : 'Back to Menu'; ?>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
