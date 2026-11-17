
<?php

if (!function_exists('formatVehicleText')) {
    function formatVehicleText($text)
    {
        $text = trim($text);

        // Multiple spaces to single space
        $text = preg_replace('/\s+/', ' ', $text);

        // Remove space before hyphen
        $text = preg_replace('/\s+-/', '-', $text);

        // Remove space after hyphen
        $text = preg_replace('/-\s+/', '-', $text);

        // capitalize all words
        $text = preg_replace_callback(
            '/\b[a-zA-Z]+\b/',
            function ($matches) {
                return ucfirst(strtolower($matches[0]));
            },
            $text
        );

        // Uppercase vehicle codes
        $text = preg_replace_callback(
            '/\b([a-zA-Z]{1,5})(?=[\s-]\d)/',
            function ($matches) {
                return strtoupper($matches[1]);
            },
            $text
        );

        return $text;
    }
}
if (!function_exists('numberConvert')) {
    function numberConvert($number)
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        if (preg_match('/[০-৯]/', $number)) {
            return str_replace($bn, $en, $number);
        }
        return str_replace($en, $bn, $number);
    }
}

if (!function_exists('getRegNumberFontSize')) {
    function getRegNumberFontSize($category, $vehicleNumber)
    {
        $textLength = strlen(formatVehicleText($vehicleNumber ?? ''));

        $fontSizeConfig = [
            'a1' => [23 => '22px', '19px'],
            'a2' => [23 => '21px', '19px'],
            'a3' => [23 => '22px', '19px'],
            'a4' => [23 => '22px', '19px'],
            'A4 Without Staff Gate' => [23 => '22px', '19px'],
            'b1' => [23 => '22px', '19px'],
            'b2' => [23 => '22px', '19px'],
            'b3' => [23 => '20px', '17px'],
            'b4' => [23 => '22px', '19px'],
            'b5' => [23 => '23px', '20px'],
            'd1' => [25 => '21px', '18px'],
            'd2' => [23 => '21px', '18px'],
            'e' => [23 => '23px', '19px'],
            'f' => [23 => '23px', '20px'],
            'm' => [23 => '20px', '18px'],
            'm1' => [23 => '20px', '18px'],
            't' => [23 => '21px', '18px'],
            't1' => [23 => '21px', '18px'],
            'ex' => [23 => '14px', '11px'],
        ];

        $category = strtolower($category);

        // E group handling
        if (in_array($category, ['e', 'e1', 'e2', 'e3', 'e4', 'e5', 'e6', 'e7', 'e8'])) {
            $category = 'e';
        }

        // Get config for this category, or use default
        $config = $fontSizeConfig[$category] ?? [23 => '21px', '17px'];

        // Extract threshold and sizes
        $threshold = array_key_first($config);
        $shortSize = $config[$threshold];
        $longSize = end($config);

        return $textLength <= $threshold ? $shortSize : $longSize;
    }
}
if (!function_exists('getLowerStickerCategory')) {
    function getLowerStickerCategory($category)
    {
        // A4 Without Staff Gate
        if ($category === 'A4 Without Staff Gate') {
            return 'a4-staff';
        }

        // E group
        if (in_array($category, ['E', 'e1', 'e2', 'e3', 'e4', 'e5', 'e6', 'e7', 'e8'])) {
            return 'e';
        }
        // dd($category);
        // default
        return strtolower($category);
    }
}

if (!function_exists('formatStickerNumber')) {
    function formatStickerNumber($app)
    {
        $category = $app->sticker_category;

        // A4 Without Staff Gate → A4
        if ($category === 'A4 Without Staff Gate') {
            $categoryLabel = 'A4';
        }
        // E group → e
        elseif (in_array($category, ['E', 'e1', 'e2', 'e3', 'e4', 'e5', 'e6', 'e7', 'e8'])) {
            $categoryLabel = 'E';
        }
        // default
        else {
            $categoryLabel = strtoupper($category);
        }

        // Sticker number
        $number = $app->sticker_number
            ??  '0552';

        return $categoryLabel . '-' . $number;
    }
}


if (!function_exists('getApplicationCategoryLabel')) {
    function getApplicationCategoryLabel($value)
    {
        $categories = [
            'service_retd' => 'Service/Retd Under Defence Ministry',
            'house_owner' => 'House Owner Inside Dhaka Cantt.',
            'civil_institution' => 'Civil Institution/Establishment Inside Dhaka Cantt.',
            'proyash_school' => 'For Proyash School & College',
            'teacher_official' => 'Teacher/Official of School & College',
            'bus_laguna' => 'Bus/Laguna',
            'motorcycle' => 'Motorcycle',
            'transit_kochukhet' => 'Transit (Kochukhet to Banani) - (10000TK)',
            'transit_matikata' => 'Transit (Matikata to Jahangir Gate) - (10000TK)',
            'special' => 'Special - (25000TK)',
        ];

        return $categories[$value] ?? $value;
    }
}


    /**
     * Derive the AES key from the configured secret using HKDF.
     * A non-empty salt is supplied (CWE-759); the legacy no-salt derivation
     * is only used as a decryption fallback for ids generated before this change.
     */
    function stickerDeriveKey(string $raw, bool $legacy = false): string
    {
        $salt = $legacy ? '' : hash('sha256', 'sta-hq-sticker-salt-v1', true);
        return hash_hkdf('sha256', $raw, 32, 'sticker-encryption', $salt);
    }

    function stickerEncryptId(int $id): string
    {
        $raw = config('app.sticker_encryption_key');
        if (!$raw) {
            throw new \RuntimeException('Sticker encryption key is not configured');
        }
        $key = stickerDeriveKey($raw);
        $iv = openssl_random_pseudo_bytes(12);
        $encrypted = openssl_encrypt((string)$id, 'AES-256-GCM', $key, 0, $iv, $tag);
        $payload = $iv . $encrypted . $tag;
        return rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
    }

    function stickerDecryptId(string $encryptedId): ?int
    {
        $raw = config('app.sticker_encryption_key');
        if (!$raw) {
            throw new \RuntimeException('Sticker encryption key is not configured');
        }
        $decoded = base64_decode(strtr($encryptedId, '-_', '+/'));
        if ($decoded === false || strlen($decoded) < 28) {
            return null;
        }
        $iv = substr($decoded, 0, 12);
        $tag = substr($decoded, -16);
        $ciphertext = substr($decoded, 12, -16);

        // Try the salted key first, then fall back to the legacy unsalted key
        // so ids generated before the salt was added still decrypt.
        foreach ([stickerDeriveKey($raw), stickerDeriveKey($raw, true)] as $key) {
            $decrypted = openssl_decrypt($ciphertext, 'AES-256-GCM', $key, 0, $iv, $tag);
            if ($decrypted !== false && is_numeric($decrypted)) {
                return (int)$decrypted;
            }
        }
        return null;
    }
