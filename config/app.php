<?php
define('ROOT_DIR', dirname(__DIR__));
define('BASE_URL', getenv('APP_BASE_URL') !== false ? rtrim(getenv('APP_BASE_URL'), '/') : '/project5');
define('APP_NAME', 'Online Car Rent');

define('REMEMBER_DAYS', 30);
define('REMEMBER_SECRET', getenv('REMEMBER_SECRET') ?: bin2hex(random_bytes(32)));
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
    if (getenv('SUPABASE_URL') && getenv('SUPABASE_SERVICE_ROLE_KEY')) {
        return;
    }
    foreach ([PROFILE_UPLOAD_DIR, CAR_UPLOAD_DIR] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
