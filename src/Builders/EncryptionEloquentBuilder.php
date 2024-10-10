<?php
/**
 * src/Builders/EncryptionEloquentBuilder.php.
 *
 */

namespace Quantumweb\DBEncryption\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class EncryptionEloquentBuilder extends Builder
{
    public $salt = null;

    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
        $this->salt = substr(hash('sha256', config('database-encryption.encryption_key')), 0, 16);
    }

    public function orderByEncrypted($column, $direction = 'asc')
    {
        return self::orderByRaw("CONVERT(AES_DECRYPT(FROM_BASE64(`{$column}`), '{$this->salt}') USING utf8mb4) {$direction}");
    }

    public function whereEncrypted($param1, $param2, $param3 = null)
    {
        $filter            = new \stdClass();
        $filter->field     = $param1;
        $filter->operation = isset($param3) ? $param2 : '=';
        $filter->value     = isset($param3) ? $param3 : $param2;

        return self::whereRaw("CONVERT(AES_DECRYPT(FROM_BASE64(`{$filter->field}`), '{$this->salt}') USING utf8mb4) {$filter->operation} ? ", [$filter->value]);
    }

    public function orWhereEncrypted($param1, $param2, $param3 = null)
    {
        $filter            = new \stdClass();
        $filter->field     = $param1;
        $filter->operation = isset($param3) ? $param2 : '=';
        $filter->value     = isset($param3) ? $param3 : $param2;

        return self::orWhereRaw("CONVERT(AES_DECRYPT(FROM_BASE64(`{$filter->field}`), '{$this->salt}') USING utf8mb4) {$filter->operation} ? ", [$filter->value]);
    }

    public function whereEncryptedConcat($param1, $param2, $param3 = null)
    {
        $filter            = new \stdClass();
        $filter->fields    = $param1;
        $filter->operation = isset($param3) ? $param2 : '=';
        $filter->value     = isset($param3) ? $param3 : $param2;

        $concat = 'CONCAT(';
        foreach ($filter->fields as $i => $field) {
            $concat .= "CONVERT(AES_DECRYPT(FROM_BASE64(`{$field}`), '{$this->salt}') USING utf8mb4)";
            if ($i <= count($filter->fields)) {
                $concat .= ", ' ', ";
            }
        }

        $concat .= ")";

        return self::whereRaw("$concat {$filter->operation} ? ", [$filter->value]);
    }

    public function orWhereEncryptedConcat($param1, $param2, $param3 = null)
    {
        $filter            = new \stdClass();
        $filter->fields    = $param1;
        $filter->operation = isset($param3) ? $param2 : '=';
        $filter->value     = isset($param3) ? $param3 : $param2;

        $concat = 'CONCAT(';
        foreach ($filter->fields as $i => $field) {
            $concat .= "CONVERT(AES_DECRYPT(FROM_BASE64(`{$field}`), '{$this->salt}') USING utf8mb4)";
            if ($i <= count($filter->fields)) {
                $concat .= ", ' ', ";
            }
        }

        $concat .= ")";

        return self::orWhereRaw("$concat {$filter->operation} ? ", [$filter->value]);
    }
}