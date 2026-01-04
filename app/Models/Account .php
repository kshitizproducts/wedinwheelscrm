<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;



class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'bank_name',
        'beneficiary_name',
        'account_number_encrypted',
        'ifsc_encrypted',
        'branch',
        'contact',
        'status'
    ];

    // Accessor for decrypted account number (use carefully)
    public function getAccountNumberAttribute()
    {
        if ($this->account_number_encrypted) {
            try {
                return Crypt::decryptString($this->account_number_encrypted);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    // Accessor for masked account number to show in UI
    public function getMaskedAccountNumberAttribute()
    {
        $num = $this->account_number;
        if (!$num) return '';
        $len = strlen($num);
        if ($len <= 4) return str_repeat('*', $len);
        $visible = 4;
        return str_repeat('*', max(0, $len - $visible)) . substr($num, -$visible);
    }

    // IFSC accessor (optionally encrypted)
    public function getIfscAttribute()
    {
        if ($this->ifsc_encrypted) {
            try {
                return Crypt::decryptString($this->ifsc_encrypted);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}
