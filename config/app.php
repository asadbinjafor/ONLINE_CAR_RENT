<?php
define('ROOT_DIR', dirname(__DIR__));
define('BASE_URL', '/project5');
define('APP_NAME', 'Online Car Rent');

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'project5');
define('DB_USER', 'root');
define('DB_PASS', '');

define('REMEMBER_DAYS', 30);
define('REMEMBER_SECRET', 'project5_remember_secret_change_in_production');
define('UPLOAD_MAX_BYTES', 2 * 1024 * 1024);

define('PROFILE_UPLOAD_DIR', ROOT_DIR . '/public/uploads/profiles/');
define('CAR_UPLOAD_DIR', ROOT_DIR . '/public/uploads/cars/');
define('PROFILE_UPLOAD_WEB', BASE_URL . '/public/uploads/profiles/');
define('CAR_UPLOAD_WEB', BASE_URL . '/public/uploads/cars/');

define('CAR_TYPES', ['Private car', 'Microbus', 'Pick-up', 'SUV', 'Sedan', 'Luxury']);
define('CAR_STATUSES', ['available', 'unavailable', 'maintenance']);
define('ORDER_STATUSES', ['pending', 'confirmed', 'cancelled']);
define('PAYMENT_METHODS', [
    'credit_card' => 'Credit Card',
    'bkash' => 'bKash',
    'nagad' => 'Nagad',
    'bank_transfer' => 'Bank Transfer',
    'cash_on_delivery' => 'Cash on Delivery',
]);

function ensureUploadDirs(): void
{
    foreach ([PROFILE_UPLOAD_DIR, CAR_UPLOAD_DIR] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
