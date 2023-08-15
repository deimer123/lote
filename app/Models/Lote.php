<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Lote extends Model
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'numero_lote',
        'valor_lote',
        'direccion_lote',          
            
    ];

}
