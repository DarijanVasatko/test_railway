<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use App\Models\Proizvod;

class ProizvodFactory extends Factory
{
    protected $model = Proizvod::class;

    public function definition(): array
    {
        // Get all images from uploads/products
        $imageDir = public_path('uploads/products');
        $images = File::exists($imageDir) ? File::files($imageDir) : [];

        // Pick a random one or use a fallback
        $randomImage = count($images) > 0
            ? 'uploads/products/' . basename($this->faker->randomElement($images))
            : 'uploads/products/default.jpg';

        // Generate a fake short description
        $shortDesc = $this->faker->sentence(8);

        return [
            'sifra' => strtoupper($this->faker->bothify('PRD-###??')),
            'Naziv' => ucfirst($this->faker->words(3, true)),
            'Opis' => $this->faker->paragraph(5),
            'KratkiOpis' => $shortDesc,
            'Cijena' => $this->faker->randomFloat(2, 10, 2000),
            'kategorija' => $this->faker->numberBetween(1, 6), // assumes you have 6 categories
            'tip_id' => $this->faker->numberBetween(1, 3), // adjust to your actual tip IDs
            'StanjeNaSkladistu' => $this->faker->numberBetween(1, 100),
            'Slika' => $randomImage,
        ];
    }
}
