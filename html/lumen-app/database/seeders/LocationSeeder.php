<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        // Faker kullanarak 10 adet dummy data ekle
        Location::factory()->count(10)->create();
    }
}
