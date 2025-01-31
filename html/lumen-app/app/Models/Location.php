<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations'; // Eğer farklı bir tablo ismi kullanıyorsanız.
    protected $fillable = ['latitude', 'longitude', 'name', 'marker_color'];
}
