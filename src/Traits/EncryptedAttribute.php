<?php
/**
 * src/Traits/EncryptedAttribute.php.
 *
 */

namespace Quantumweb\DBEncryption\Traits;

use Quantumweb\DBEncryption\Builders\EncryptionEloquentBuilder;
use Quantumweb\DBEncryption\Encrypter;

trait EncryptedAttribute
{
    public function fromEncryptedString($value)
    {
        try {
            if ($value) {
                return Encrypter::decrypt($value) ?: $value;
            }

            return $value;
        } catch (\Throwable $e) {
            return $value;
        }
    }

    protected function castAttributeAsEncryptedString($key, $value)
    {
        try {
            if ($value) {
                return Encrypter::encrypt($value) ?: $value;
            }

            return $value;
        } catch (\Throwable $e) {
            return $value;
        }
    }

    // Extend EncryptionEloquentBuilder
    public function newEloquentBuilder($query)
    {
        return new EncryptionEloquentBuilder($query);
    }
}
