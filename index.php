<?php
require_once 'config.php';

// Get all active food items
$stmt = $conn->query("SELECT * FROM food_items WHERE is_active = 1 ORDER BY category, name");
$foodItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by category
$categories = [
    'main_dish' => ['en' => 'Main Dishes', 'sw' => 'Vitu vikuu'],
    'side_dish' => ['en' => 'Side Dishes', 'sw' => 'Vitu vidogo'],
    'drink' => ['en' => 'Drinks', 'sw' => 'Vinywaji'],
    'extra' => ['en' => 'Extras', 'sw' => 'Vitu vingine']
];

$lang = getLang();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CIVE Cafeteria - Order food online">
    <title><?php echo APP_NAME; ?> - <?php __('menu'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#10b981">
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <h1>
                <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <?php echo APP_NAME; ?>
            </h1>
            <div class="header-actions">
                <div class="lang-toggle">
                    <button class="lang-btn <?php echo $lang === 'en' ? 'active' : ''; ?>" onclick="setLanguage('en')">EN</button>
                    <button class="lang-btn <?php echo $lang === 'sw' ? 'active' : ''; ?>" onclick="setLanguage('sw')">SW</button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-content">
                <!-- Category Filter -->
                <div class="category-filter">
                    <button class="category-btn active" data-category="all"><?php echo $lang === 'sw' ? 'Vyote' : 'All'; ?></button>
                    <?php foreach ($categories as $key => $cat): ?>
                    <button class="category-btn" data-category="<?php echo $key; ?>"><?php echo $cat[$lang]; ?></button>
                    <?php endforeach; ?>
                </div>

                <!-- Menu Grid -->
                <div class="menu-grid" id="menuGrid">
                    <?php foreach ($foodItems as $item): ?>
                    <div class="food-card <?php echo $item['stock_status']; ?>" data-category="<?php echo $item['category']; ?>">
                        <div class="food-image">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="food-info">
                            <div class="food-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="food-name-sw"><?php echo htmlspecialchars($item['name_sw']); ?></div>
                            <div class="food-footer">
                                <span class="food-price"><?php echo formatCurrency($item['price']); ?></span>
                                <?php echo getStockStatusBadge($item['stock_status'], $lang); ?>
                            </div>
                            <?php if ($item['stock_status'] !== 'finished'): ?>
                            <button class="btn btn-primary btn-sm btn-block mt-2" onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['name']); ?>', '<?php echo htmlspecialchars($item['name_sw']); ?>', <?php echo $item['price']; ?>)" style="margin-top: 0.75rem;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <?php __('add_to_order'); ?>
                            </button>
                            <?php else: ?>
                            <button class="btn btn-sm btn-block btn-secondary" disabled style="margin-top: 0.75rem;">
                                <?php echo $lang === 'sw' ? 'Imeisha' : 'Finished'; ?>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Shopping Cart -->
                <div class="order-cart" id="orderCart" style="display: none;">
                    <div class="cart-header">
                        <h3>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <?php __('your_order'); ?>
                        </h3>
                    </div>
                    <div class="cart-items" id="cartItems"></div>
                    <div class="cart-footer">
                        <div class="cart-total">
                            <span><?php __('total'); ?>:</span>
                            <span class="cart-total-value" id="cartTotal"><?php echo CURRENCY; ?> 0</span>
                        </div>
                        <form id="orderForm" action="place-order.php" method="POST">
                            <input type="hidden" name="order_items" id="orderItemsInput">
                            <div class="form-group">
                                <label class="form-label"><?php __('name'); ?> *</label>
                                <input type="text" name="customer_name" class="form-input" required placeholder="<?php echo $lang === 'sw' ? 'Andika jina lako' : 'Enter your name'; ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><?php __('phone'); ?></label>
                                <input type="tel" name="customer_phone" class="form-input" placeholder="<?php echo $lang === 'sw' ? 'Namba ya simu (si lazima)' : 'Phone number (optional)'; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <?php __('submit_order'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="index.php" class="nav-item active">
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
            <a href="feedback.php" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <?php __('feedback'); ?>
            </a>
        </nav>
    </div>

    <script>
        let cart = [];

        function setLanguage(lang) {
            fetch('set-language.php?lang=' + lang)
                .then(() => location.reload());
        }

        function addToCart(id, name, nameSw, price) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, nameSw, price, quantity: 1 });
            }
            updateCartUI();
        }

        function removeFromCart(id) {
            const index = cart.findIndex(item => item.id === id);
            if (index > -1) {
                if (cart[index].quantity > 1) {
                    cart[index].quantity--;
                } else {
                    cart.splice(index, 1);
                }
            }
            updateCartUI();
        }

        function updateCartUI() {
            const cartEl = document.getElementById('orderCart');
            const itemsEl = document.getElementById('cartItems');
            const totalEl = document.getElementById('cartTotal');
            const inputEl = document.getElementById('orderItemsInput');

            if (cart.length === 0) {
                cartEl.style.display = 'none';
                return;
            }

            cartEl.style.display = 'block';
            
            let total = 0;
            let html = '';
            
            cart.forEach(item => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                html += `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price"><?php echo CURRENCY; ?> ${item.price.toLocaleString()} x ${item.quantity}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span class="cart-item-total"><?php echo CURRENCY; ?> ${subtotal.toLocaleString()}</span>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });

            itemsEl.innerHTML = html;
            totalEl.textContent = '<?php echo CURRENCY; ?> ' + total.toLocaleString();
            inputEl.value = JSON.stringify(cart);
            
            // Scroll to cart
            cartEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Category filter
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                document.querySelectorAll('.food-card').forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Service Worker for offline support
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js');
        }
    </script>
</body>
</html>
