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
        Schema::create('kosarica', function (Blueprint $table) {
    $table->id();
    $table->foreignId('korisnik_id')->constrained('kupac');
    $table->foreignId('proizvod_id')->constrained('proizvod');
    $table->integer('kolicina')->default(1);
    $table->timestamp('datum_dodavanja')->useCurrent();
    $table->unique(['korisnik_id', 'proizvod_id']);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosarica');
    }
};
