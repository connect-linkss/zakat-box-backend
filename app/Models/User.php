<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'guid',
        'name',
        'phone',
        'image',
        'user_type',
        'email',
        'active',
        'password',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getImageAttribute($image)
    {
        $randomImages = ["default1.jpg", "default2.jpg", "default3.jpg"];
        if ($image != null && $image != "") {
            return url('') . '/public/images/' . $image;
        } else {
            $randomImage = $randomImages[array_rand($randomImages)];
            return url('') . '/public/default_image/' . $randomImage;
        }
    }
}
