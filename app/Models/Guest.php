<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $guarded =[];
    use HasFactory;
    public function answer(){
        return $this->hasMany(Answer::class);
    }
}
