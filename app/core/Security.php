<?php
/**
 * Herramientas de Seguridad y Encriptación (AES-256 y Sanitización)
 */
class Security {
    
    /**
     * Encripta texto utilizando AES-256-CBC
     */
    public static function encrypt($data) {
        if (empty($data)) return '';
        
        $cipherMethod = 'aes-256-cbc';
        // Generar una clave de 32 bytes robusta basada en la constante
        $key = hash('sha256', AES_KEY, true);
        
        // Obtener longitud del Vector de Inicialización (IV)
        $ivLength = openssl_cipher_iv_length($cipherMethod);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        // Encriptar
        $encryptedRaw = openssl_encrypt($data, $cipherMethod, $key, OPENSSL_RAW_DATA, $iv);
        
        // Unir IV y el texto cifrado, codificar en Base64
        return base64_encode($iv . $encryptedRaw);
    }

    /**
     * Desencripta texto utilizando AES-256-CBC
     */
    public static function decrypt($encryptedData) {
        if (empty($encryptedData)) return '';
        
        $cipherMethod = 'aes-256-cbc';
        $key = hash('sha256', AES_KEY, true);
        
        $raw = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($cipherMethod);
        
        if (strlen($raw) < $ivLength) {
            return false;
        }
        
        // Extraer IV y el mensaje cifrado
        $iv = substr($raw, 0, $ivLength);
        $encryptedRaw = substr($raw, $ivLength);
        
        // Desencriptar
        return openssl_decrypt($encryptedRaw, $cipherMethod, $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * Sanitiza inputs de entrada para prevenir Cross-Site Scripting (XSS)
     */
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } else {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
}
