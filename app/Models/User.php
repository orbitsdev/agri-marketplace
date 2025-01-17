<?php

namespace App\Models;
use Filament\Panel;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Farmer;
use App\Models\Location;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Filament\Models\Contracts\HasName;

use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function getFullNameAttribute()
    {
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';
        $middleName = $this->middle_name ?? '';

        return $lastName . ', ' . $firstName.' '.$middleName;
    }
    // make function to get fullname  lower case slug


public function fullNameSlug()
{
    $firstName = $this->first_name ?? '';
    $lastName = $this->last_name ?? '';

    // Combine first name and last name with a dash
    $slug = $firstName . '-' . $lastName;

    // Convert to lowercase and remove special characters
    return Str::slug($slug);
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
    return $this->hasMany(Order::class, 'buyer_id');
}



// has many carts
public function carts()
{
    return $this->hasMany(Cart::class, 'buyer_id',);
}

// has many notifications
public function notifications()
{
    return $this->hasMany(Notification::class);
}
// scope with media
public function scopeWithMedia($query)
{
    return $query->with(['media']);
}


public function getTotalCartItems()
{
    return $this->carts()->count();
}
public function getSelectedCartCount()
{
    return $this->carts()->where('is_selected', true)->count();
}

// get default location

public function scopeDefault($query)
{
    return $query->where('is_default', true);
}

public function getDefaultLocation()
{
    return $this->locations()->default()->first();
}
public function hasDefaultLocation(){
   return self::locations()->hasDefault()->exists();
}


// scope role farmer

public static function scopeIsFarmer($query){
    return $query->where('role', '==', self::FARMER);
}



public function getAddresses()
{
    return $this->locations()->get();
}

}
