<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AnswerForm extends Model
{
    protected $guarded =[];
    use HasFactory;

    public function feedbackForm(){
        return $this->belongsTo(FeedbackForm::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function guest(){
        return $this->belongsTo(Guest::class);
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }
}
