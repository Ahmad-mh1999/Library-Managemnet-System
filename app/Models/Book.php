<?php

namespace App\Models;

use App\Models\User;
use App\Models\Auther;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ManageBorrowOperation;
use App\Http\Traits\ModelChangeLoggerTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory,SoftDeletes,ManageBorrowOperation,ModelChangeLoggerTrait;

    protected $fillable = [
        'title',
        'available',
        'amount',
        'date_of_publication',
    ];

    public function authers(){
        return $this->belongsToMany(Auther::class,'auther_book');
    }

    public function reviews() {
        return $this->morphMany(Review::class,'reviewable');
    }

    public function users(){
        return $this->belongsToMany(User::class,'user_book')->withTimestamps();;
    }

    protected static function boot(){

        parent::boot();
        
        static::saving(function($book){
            $book->available = $book->amount > 0;
        });
    }
}

