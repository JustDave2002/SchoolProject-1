<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackForm extends Model
{
    protected $guarded =[];
    use HasFactory;

    public function formBinders(){
        return $this->belongsTo(formBinder::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function answerForms(){
        return $this->hasMany(AnswerForm::class);
    }
}
