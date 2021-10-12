<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormBinder extends Model
{
    protected $guarded =[];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function feedbackForms(){
        return $this->hasMany(FeedbackForm::class);
    }

    use HasFactory;
}
