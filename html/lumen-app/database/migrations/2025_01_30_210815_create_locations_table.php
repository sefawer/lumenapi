<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 7); // Latitude (örneğin: 40.712776)
            $table->decimal('longitude', 10, 7); // Longitude (örneğin: -74.005974)
            $table->string('marker_color');
            $table->timestamps(); // Eğer timestamps kullanıyorsanız
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
