<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    //Tabla a la que se aplicara el modelo 
    protected $table = "blogs"; 

    //Campos del modelo que se pueden modificar de la base de datos
    protected $fillable = [
        'title',
        'content',
        'image'
    ];
}
