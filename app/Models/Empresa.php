<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'rut'];

    public function boletas()
    {
        return $this->hasMany(Boleta::class);
    }
}