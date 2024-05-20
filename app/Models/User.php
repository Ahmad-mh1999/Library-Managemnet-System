<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Book;
use App\Models\Role;
use App\Models\Course;
use App\Models\Review;
use App\Models\CourseUser;
use App\Models\Notification;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\ModelChangeLoggerTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // mutator to save email in lowercase
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower($value),
        );
    }



    //relationship between users and roles (Many-to-Many)
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // Check if the user has a specific role
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }


    public function books(){
        return $this->belongsToMany(Book::class,'user_book')->withTimestamps();;
    }

    public function notifications(){
        return $this->belongsToMany(Notification::class,'user_notification')->withPivot('is_read');
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }
}





