<?php

namespace App\Models;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Farmer;

class Document extends Model  implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile();
       

        $this->addMediaCollection('files');
    
        
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

}
