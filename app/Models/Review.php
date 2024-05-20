<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ModelChangeLoggerTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory,SoftDeletes,ModelChangeLoggerTrait;
    
    protected $fillable = [
        'review',
        'user_id'
    ];
    
    
    public function reviewable(){
        return $this->morphTo();
    }

    public function user() {
        $this->belongsTo(User::class);
    }

    protected static function boot() {
        parent::boot();

        static::creating(function($review){
            $review->user_id = Auth::user()->id;
        });
    }
}
