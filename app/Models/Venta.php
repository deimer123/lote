<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Venta extends Model
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [  
                           
        'User_id',            
        'Lote_id',       
        'cuotas',
        'certificate_image',
        'original_filename',
        'valor_cuota',
        'valor_pagado',
        'valor_deuda',
        'agente_id',  
    ];

    public function user (){
    
    
        return $this->belongsTo(User::class);
         


    }

    public function lote (){
    
    
        return $this->belongsTo(Lote::class); 


    }


    public function agente (){
    
    
        return $this->belongsTo(User::class,"agente_id"); 


    }


}
