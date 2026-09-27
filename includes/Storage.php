<?php
class Storage
{
    public static function enabled(): bool
    {
        return (bool) (getenv('SUPABASE_URL') && getenv('SUPABASE_SERVICE_ROLE_KEY'));
    }

    public static function url(string $folder, string $name): string
    {
        if (!self::enabled()) {
            return rtrim(BASE_URL, '/') . '/public/uploads/' . $folder . '/' . rawurlencode($name);
        }
        return rtrim(getenv('SUPABASE_URL'), '/') . '/storage/v1/object/public/'
            . rawurlencode(getenv('SUPABASE_STORAGE_BUCKET') ?: 'car-rent-images')
            . '/' . rawurlencode($folder) . '/' . rawurlencode($name);
    }

    public static function upload(string $path, string $folder, string $name, string $mime): bool
    {
        $data = file_get_contents($path);
        if ($data === false) {
            return false;
        }
        return self::request('POST', $folder . '/' . $name, $data, $mime);
    }

    public static function delete(string $folder, string $name): void
    {
        if (!self::enabled()) {
            $dir = $folder === 'cars' ? CAR_UPLOAD_DIR : PROFILE_UPLOAD_DIR;
            $path = $dir . basename($name);
            if (is_file($path)) {
                unlink($path);
            }
            return;
        }
        $bucket = rawurlencode(getenv('SUPABASE_STORAGE_BUCKET') ?: 'car-rent-images');
        $url = rtrim(getenv('SUPABASE_URL'), '/') . '/storage/v1/object/' . $bucket;
        self::send('DELETE', $url, json_encode(['prefixes' => [$folder . '/' . $name]]), 'application/json');
    }

    private static function request(string $method, string $key, string $body, string $mime): bool
    {
        $bucket = rawurlencode(getenv('SUPABASE_STORAGE_BUCKET') ?: 'car-rent-images');
        $url = rtrim(getenv('SUPABASE_URL'), '/') . '/storage/v1/object/' . $bucket . '/'
            . implode('/', array_map('rawurlencode', explode('/', $key)));
        return self::send($method, $url, $body, $mime);
    }

    private static function send(string $method, string $url, string $body, string $mime): bool
    {
        $key = getenv('SUPABASE_SERVICE_ROLE_KEY');
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $key,
                'apikey: ' . $key,
                'Content-Type: ' . $mime,
            ],
        ]);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        if ($result === false || $status < 200 || $status >= 300) {
            error_log('Supabase Storage request failed (HTTP ' . $status . ')');
            return false;
        }
        return true;
    }
}
