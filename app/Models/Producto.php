<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['categoria_id', 'nombre', 'talla', 'precio', 'imagen'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}