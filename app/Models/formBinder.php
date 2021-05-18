<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class formBinder extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function feedbackForm(){
        return $this->hasMany(FeedbackForm::class);
    }

    use HasFactory;
}
