<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = "employees";

    protected $fillable = [
        'dispatcher_id',
        'name',
        'email',
        'phone',
        'position',
        'ssn_tax_id',
        'created_at',
        'updated_at'
    ];
    
    /**
     * Relacionamento com Dispatcher
     */
    public function dispatcher()
    {
        return $this->belongsTo(\App\Models\Dispatcher::class);
    }

}

