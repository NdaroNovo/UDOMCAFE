<?php
/**
 * CIVE Cafeteria Management System
 * Configuration File
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cive_cafeteria');

// Application Settings
define('APP_NAME', 'CIVE Cafeteria');
define('APP_NAME_SW', 'Cafeteria ya CIVE');
define('VERSION', '1.0.0');
define('CURRENCY', 'TSh');
define('TIMEZONE', 'Africa/Dar_es_Salaam');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Session configuration
session_start();

// Database Connection Class
class Database {
    private $connection;
    
    public function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function close() {
        $this->connection = null;
    }
}

// Helper Functions
function formatCurrency($amount) {
    return CURRENCY . ' ' . number_format($amount, 0, ',', ',');
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}

function formatTime($time) {
    return date('H:i', strtotime($time));
}

function generateOrderNumber() {
    return 'ORD' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function showAlert($message, $type = 'success') {
    $icons = [
        'success' => 'check-circle',
        'error' => 'x-circle',
        'warning' => 'alert-triangle',
        'info' => 'info'
    ];
    $icon = $icons[$type] ?? 'info';
    
    return "<div class='alert alert-{$type}'>
        <svg class='alert-icon'><use href='#icon-{$icon}'></use></svg>
        <span>{$message}</span>
    </div>";
}

function getStockStatusBadge($status, $lang = 'en') {
    $labels = [
        'available' => ['en' => 'Available', 'sw' => 'Ipo'],
        'low' => ['en' => 'Low Stock', 'sw' => 'Imeisha Mzigo Mdogo'],
        'finished' => ['en' => 'Finished', 'sw' => 'Imeisha']
    ];
    
    $label = $labels[$status][$lang] ?? $labels[$status]['en'];
    return "<span class='badge badge-{$status}'>{$label}</span>";
}

function getOrderStatusBadge($status, $lang = 'en') {
    $labels = [
        'pending' => ['en' => 'Pending', 'sw' => 'Inasubiri'],
        'preparing' => ['en' => 'Preparing', 'sw' => 'Inaandaliwa'],
        'ready' => ['en' => 'Ready', 'sw' => 'Iko Tayari'],
        'completed' => ['en' => 'Completed', 'sw' => 'Imekamilika'],
        'cancelled' => ['en' => 'Cancelled', 'sw' => 'Imeghairiwa']
    ];
    
    $label = $labels[$status][$lang] ?? $labels[$status]['en'];
    return "<span class='status-badge status-{$status}'>{$label}</span>";
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check user role
function hasRole($role) {
    return isLoggedIn() && $_SESSION['user_role'] === $role;
}

// Redirect helper
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Language helper
function getLang() {
    return isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
}

function setLang($lang) {
    $_SESSION['lang'] = ($lang === 'sw') ? 'sw' : 'en';
}

// Translation helper
function __($key) {
    $translations = [
        'en' => [
            'menu' => 'Menu',
            'order' => 'Order',
            'place_order' => 'Place Order',
            'view_menu' => 'View Menu',
            'my_order' => 'My Order',
            'total' => 'Total',
            'submit_order' => 'Submit Order',
            'available' => 'Available',
            'low_stock' => 'Low Stock',
            'finished' => 'Finished',
            'name' => 'Name',
            'phone' => 'Phone (Optional)',
            'cashier' => 'Cashier',
            'kitchen' => 'Kitchen',
            'manager' => 'Manager',
            'orders' => 'Orders',
            'pending' => 'Pending',
            'ready' => 'Ready',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'mark_ready' => 'Mark Ready',
            'mark_paid' => 'Mark Paid',
            'cancel' => 'Cancel',
            'logout' => 'Logout',
            'login' => 'Login',
            'dashboard' => 'Dashboard',
            'sales' => 'Sales',
            'stock' => 'Stock',
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'this_week' => 'This Week',
            'this_month' => 'This Month',
            'food_items' => 'Food Items',
            'add_stock' => 'Add Stock',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'category' => 'Category',
            'search' => 'Search',
            'no_items' => 'No items found',
            'order_number' => 'Order Number',
            'customer' => 'Customer',
            'items' => 'Items',
            'status' => 'Status',
            'payment' => 'Payment',
            'actions' => 'Actions',
            'refresh' => 'Refresh',
            'loading' => 'Loading...',
            'error_loading' => 'Error loading data',
            'add_to_order' => 'Add to Order',
            'remove' => 'Remove',
            'your_order' => 'Your Order',
            'empty_order' => 'Your order is empty',
            'thank_you' => 'Thank you!',
            'order_placed' => 'Your order has been placed',
            'order_ready' => 'Your order is ready',
            'estimated_time' => 'Estimated time',
            'minutes' => 'minutes',
            'notifications' => 'Notifications',
            'low_stock_alert' => 'Low Stock Alert',
            'daily_sales' => 'Daily Sales',
            'total_orders' => 'Total Orders',
            'revenue' => 'Revenue',
            'average_order' => 'Average Order',
            'food_running_out' => 'Food Running Out Fast',
            'back_to_menu' => 'Back to Menu',
            'feedback' => 'Feedback',
            'send_feedback' => 'Send Feedback',
            'rating' => 'Rating',
            'message' => 'Message',
            'language' => 'Language',
            'english' => 'English',
            'swahili' => 'Swahili'
        ],
        'sw' => [
            'menu' => 'Orodha ya Chakula',
            'order' => 'Agizo',
            'place_order' => 'Weka Agizo',
            'view_menu' => 'Angalia Orodha',
            'my_order' => 'Agizo Langu',
            'total' => 'Jumla',
            'submit_order' => 'Wasilisha Agizo',
            'available' => 'Ipo',
            'low_stock' => 'Imeisha Mzigo Mdogo',
            'finished' => 'Imeisha',
            'name' => 'Jina',
            'phone' => 'Simu (Si Lazima)',
            'cashier' => 'Mhasibu',
            'kitchen' => 'Jikoni',
            'manager' => 'Msimamizi',
            'orders' => 'Maagizo',
            'pending' => 'Yanasubiri',
            'ready' => 'Iko Tayari',
            'completed' => 'Imekamilika',
            'cancelled' => 'Imeghairiwa',
            'mark_ready' => 'Weka Tayari',
            'mark_paid' => 'Weka Imelipiwa',
            'cancel' => 'Ghairi',
            'logout' => 'Toka',
            'login' => 'Ingia',
            'dashboard' => 'Dashibodi',
            'sales' => 'Mauzo',
            'stock' => 'Mzigo',
            'today' => 'Leo',
            'yesterday' => 'Jana',
            'this_week' => 'Wiki Hii',
            'this_month' => 'Mwezi Huu',
            'food_items' => 'Vyakula',
            'add_stock' => 'Ongeza Mzigo',
            'quantity' => 'Kiasi',
            'price' => 'Bei',
            'category' => 'Kundi',
            'search' => 'Tafuta',
            'no_items' => 'Hakuna vitu vilivyopatikana',
            'order_number' => 'Namba ya Agizo',
            'customer' => 'Mteja',
            'items' => 'Vitu',
            'status' => 'Hali',
            'payment' => 'Malipo',
            'actions' => 'Vitendo',
            'refresh' => 'Furahisha',
            'loading' => 'Inapakia...',
            'error_loading' => 'Hitilafu katika kupakia data',
            'add_to_order' => 'Ongeza kwa Agizo',
            'remove' => 'Ondoa',
            'your_order' => 'Agizo Lako',
            'empty_order' => 'Agizo lako ni tupu',
            'thank_you' => 'Asante!',
            'order_placed' => 'Agizo lako limewekwa',
            'order_ready' => 'Agizo lako liko tayari',
            'estimated_time' => 'Muda unaokadiriwa',
            'minutes' => 'dakika',
            'notifications' => 'Arifa',
            'low_stock_alert' => 'Arifa ya Mzigo Mdogo',
            'daily_sales' => 'Mauzo ya Kila Siku',
            'total_orders' => 'Jumla ya Maagizo',
            'revenue' => 'Mapato',
            'average_order' => 'Wastani wa Agizo',
            'food_running_out' => 'Chakula Kinachoisha Haraka',
            'back_to_menu' => 'Rudi kwa Orodha',
            'feedback' => 'Maoni',
            'send_feedback' => 'Tuma Maoni',
            'rating' => 'Ukadiriaji',
            'message' => 'Ujumbe',
            'language' => 'Lugha',
            'english' => 'Kiingereza',
            'swahili' => 'Kiswahili'
        ]
    ];
    
    $lang = getLang();
    return $translations[$lang][$key] ?? $key;
}

// Global database instance
$db = new Database();
$conn = $db->getConnection();
