<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answare extends Model
{
    use HasFactory;

    protected $table = 'selected_answers';

    protected $fillable = ['user_id', 'category_id', 'question_id', 'answer', 'nilai'];

    public function question()
    {
        return $this->belongsTo(Questions::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
