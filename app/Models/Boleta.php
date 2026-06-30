<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boleta extends Model
{
    use HasFactory;

    protected $fillable = ['empresa_id', 'vendedor_id', 'total'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot('cantidad', 'subtotal')->withTimestamps();
    }
}