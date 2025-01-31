<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations'; // Eğer farklı bir tablo ismi kullanıyorsanız.
    protected $fillable = ['latitude', 'longitude', 'name', 'marker_color'];
}
