<?php 
namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;

trait ModelChangeLoggerTrait
{
    public static function bootModelChangeLogger()
    {
        static::created(function ($model) {
            $model->logChange('created');
        });

        static::updated(function ($model) {
            $model->logChange('updated');
        });

        static::deleted(function ($model) {
            $model->logChange('deleted');
        });
    }
        protected function logChange($action)
    {
        Log::info("A model has been {$action}", [
            'model_id' => $this->id,
            'model_type' => get_class($this),
        ]);
    }
}