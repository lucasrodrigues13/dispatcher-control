<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrier_id',
        'name',
        'phone',
        'ssn_tax_id',
        'email',
    ];

    /**
     * Cada Carrier pertence a um User
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    /**
     * Cada Carrier pertence a um User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
