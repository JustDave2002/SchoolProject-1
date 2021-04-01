<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded =[];
    use HasFactory;
    public function feedbackForms(){
        return $this->belongsTo(FeedbackForm::class);
    }
    public function answers(){
        return $this->hasMany(Answer::class);
    }
}
