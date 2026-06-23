<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'precio_unitario'];

    public function boletas()
    {
        return $this->belongsToMany(Boleta::class)->withPivot('cantidad', 'subtotal')->withTimestamps();
    }
}