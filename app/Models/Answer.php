<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded =[];
    use HasFactory;

    public function questions(){
        return $this->belongsTo(Question::class);
    }
    public function guests(){
        return $this->belongsTo(Guest::class);
    }
    public function answerForm(){
        return $this->belongsTo(answerForm::class);
    }
}
