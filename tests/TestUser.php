<?php

namespace Quantumweb\DBEncryption\Tests;

use Illuminate\Database\Eloquent\Model;
use Quantumweb\DBEncryption\Traits\EncryptedAttributes;
use Quantumweb\DBEncryption\Tests\Database\Factories\TestUserFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestUser extends Model
{
    use HasFactory;
    use EncryptedAttributes;

    protected $fillable = ['email', 'name', 'password'];
    protected $encryptable = ['email', 'name'];
    protected $camelcase = ['name'];

    protected static function newFactory()
    {
        return TestUserFactory::new();
    }
}