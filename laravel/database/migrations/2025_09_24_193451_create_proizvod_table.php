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
        Schema::create('proizvod', function (Blueprint $table) {
    $table->id('Proizvod_ID');
    $table->bigInteger('sifra');
    $table->string('Naziv', 100);
    $table->text('Opis')->nullable();
    $table->decimal('Cijena', 10, 2);
    $table->foreignId('kategorija')->constrained('kategorija')->cascadeOnDelete()->cascadeOnUpdate();
    $table->integer('StanjeNaSkladistu');
    $table->string('Slika', 5000)->nullable();
    $table->foreignId('tip_id')->nullable()->constrained('tip_proizvoda');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proizvod');
    }
};
