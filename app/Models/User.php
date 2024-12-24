<?php

namespace App\Models;
use Filament\Panel;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Farmer;
use App\Models\Location;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;

use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\FilamentUser;
class User extends Authenticatable implements FilamentUser, HasName , HasMedia {

    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use InteractsWithMedia;



    public const FARMER = 'Farmer';
    public const BUYER = 'Buyer';
    public const ADMIN = 'Admin';


    public const ROLE_OPTIONS = [
        self::FARMER => self::FARMER,
        self::BUYER => self::BUYER,
        self::ADMIN => self::ADMIN,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
        // return match($panel->getId()){
        //     'admin'=> $this->hasAnyRole(['Admin']),
        //     'clinic'=> $this->hasAnyRole(['Admin','Veterenarian']),
        //     'client'=> $this->hasAnyRole(['Admin','Client','Veterenarian']),
        // };
    }
    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function isAdmin(): bool
    {
        return $this->role === self::ADMIN;
    }

    public function isBuyer(): bool
    {
        return $this->role === self::BUYER;
    }

    public function isFarmer(): bool
    {
        return $this->role === self::FARMER;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        
    }




    

public function getImage()
{
    if ($this->hasMedia()) {
        return $this->getFirstMediaUrl();
    }

    return url('images/placeholder-image.jpg');


}

public function farmer()
{
    return $this->hasOne(Farmer::class);
}

public function locations()
{
    return $this->hasMany(Location::class);
}

// has many orders
public function orders()
{
    return $this->hasMany(Order::class);
}


// has many carts
public function carts()
{
    return $this->hasMany(Cart::class);
}

// has many notifications
public function notifications()
{
    return $this->hasMany(Notification::class);
}

}