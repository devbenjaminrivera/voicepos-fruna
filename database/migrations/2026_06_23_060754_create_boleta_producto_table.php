<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('boleta_producto', function (Blueprint $table) {
        $table->id();
        $table->foreignId('boleta_id')->constrained('boletas')->onDelete('cascade');
        $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
        $table->integer('cantidad');
        $table->integer('subtotal');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boleta_producto');
    }
};
