<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Farmer extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function documents(){
        return $this->hasMany(Document::class,
            'farmer_id');
    }

}
