<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Question;

class Answer extends Model
{
    use HasFactory;

    // Attributs pouvant être remplis en masse
       protected $fillable = [
        'body', 'question_id', 'user_id', 'is_validated'
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

public function question()
{
    return $this->belongsTo(Question::class);
}

  // Attributs à caster en types natifs
  protected $casts = [
    'is_validated' => 'boolean',
];

}
