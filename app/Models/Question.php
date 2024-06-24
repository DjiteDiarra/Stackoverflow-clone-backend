<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Answer;
use App\Models\User;

class Question extends Model
{
    use HasFactory;

     // Attributs pouvant être remplis en masse
     protected $fillable = [
        'title', 'body', 'user_id'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

public function answers()
{
    return $this->hasMany(Answer::class);
}


}
