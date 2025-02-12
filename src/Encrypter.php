<?php
/**
 * src/Encryption.php.
 *
 */

namespace Quantumweb\DBEncryption;

class Encrypter
{
    /**
     * @param  string  $value
     *
     * @return string
     */
    public static function encrypt($value)
    {
        return openssl_encrypt($value, config('database-encryption.encryption_method'), self::getKey(), 0, $iv = '');
    }

    /**
     * @param  string  $value
     *
     * @return string
     */
    public static function decrypt($value)
    {
        return openssl_decrypt($value, config('database-encryption.encryption_method'), self::getKey(), 0, $iv = '');
    }

    /**
     * Get app key for encryption key
     *
     * @return string
     */
    protected static function getKey()
    {
        $salt = substr(hash('sha256', config('database-encryption.encryption_key')), 0, 16);

        return $salt;
    }
}