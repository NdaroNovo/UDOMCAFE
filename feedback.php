<?php
require_once 'config.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $category = $_POST['category'] ?? 'other';
    
    if (empty($message)) {
        $error = 'Please enter your feedback.';
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO feedback (customer_name, message, rating, category) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $message, $rating, $category]);
            $success = true;
        } catch (Exception $e) {
            $error = 'Error submitting feedback. Please try again.';
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
    <title><?php echo APP_NAME; ?> - <?php __('feedback'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <?php __('feedback'); ?>
            </h1>
            <div class="header-actions">
                <div class="lang-toggle">
                    <button class="lang-btn <?php echo $lang === 'en' ? 'active' : ''; ?>" onclick="location.href='set-language.php?lang=en&redirect=feedback.php'">EN</button>
                    <button class="lang-btn <?php echo $lang === 'sw' ? 'active' : ''; ?>" onclick="location.href='set-language.php?lang=sw&redirect=feedback.php'">SW</button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-content">
                <?php if ($success): ?>
                <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span><?php echo $lang === 'sw' ? 'Asante kwa maoni yako!' : 'Thank you for your feedback!'; ?></span>
                </div>
                <div class="text-center" style="margin-top: 2rem;">
                    <a href="index.php" class="btn btn-primary">
                        <?php __('back_to_menu'); ?>
                    </a>
                </div>
                <?php else: ?>
                    <?php if ($error): ?>
                    <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo $lang === 'sw' ? 'Tuma Maoni Yako' : 'Send Your Feedback'; ?></h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label class="form-label"><?php echo $lang === 'sw' ? 'Jina (Si lazima)' : 'Name (Optional)'; ?></label>
                                    <input type="text" name="name" class="form-input" placeholder="<?php echo $lang === 'sw' ? 'Jina lako' : 'Your name'; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label"><?php __('rating'); ?></label>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <label style="cursor: pointer;">
                                            <input type="radio" name="rating" value="<?php echo $i; ?>" style="display: none;" <?php echo $i === 5 ? 'checked' : ''; ?>>
                                            <svg class="star-icon" data-rating="<?php echo $i; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px; color: var(--gray-300); transition: color 0.2s;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label"><?php __('message'); ?> *</label>
                                    <textarea name="message" class="form-textarea" rows="4" required placeholder="<?php echo $lang === 'sw' ? 'Andika maoni yako hapa...' : 'Write your feedback here...'; ?>"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    <?php __('send_feedback'); ?>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="index.php" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <?php __('menu'); ?>
            </a>
            <a href="my-order.php" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <?php __('my_order'); ?>
            </a>
            <a href="feedback.php" class="nav-item active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <?php __('feedback'); ?>
            </a>
        </nav>
    </div>
    
    <script>
        // Star rating interaction
        document.querySelectorAll('.star-icon').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                document.querySelectorAll('.star-icon').forEach((s, index) => {
                    if (index < rating) {
                        s.style.fill = '#f59e0b';
                        s.style.color = '#f59e0b';
                    } else {
                        s.style.fill = 'none';
                        s.style.color = 'var(--gray-300)';
                    }
                });
            });
        });
    </script>
</body>
</html>
