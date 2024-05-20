<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\ModelChangeLoggerTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory,SoftDeletes,ModelChangeLoggerTrait;
    
    protected $fillable = [
        'title',
        'message',
        'type',
    ];


    public function users(){
        return $this->belongsToMany(User::class,'user_notification')->withPivot('is_read');
    }
}
