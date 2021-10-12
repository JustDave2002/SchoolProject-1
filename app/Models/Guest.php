<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $guarded =[];
    use HasFactory;
    public function answerForm(){
        return $this->hasOne(AnswerForm::class);
    }
    public function role(){
        return $this->belongsTo(Role::class);
    }

}
