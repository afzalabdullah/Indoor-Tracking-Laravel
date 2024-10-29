<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    // Define fillable properties including 'user_id'
    protected $fillable = ['email', 'name', 'description', 'image_url', 'user_id'];

    // Define the relationship with Anchor model
    public function anchors()
    {
        return $this->hasMany(Anchor::class);
    }

    // Define the relationship with Assets model
    public function assets()
    {
        return $this->hasMany(Assets::class); // Adjust this according to your Asset model
    }

    // Optionally, if you want to define the relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class); // Assuming you have a User model
    }
}
