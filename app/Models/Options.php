<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'option_url', 'option_description'];

    public function question()
    {
        return $this->belongsTo(Questions::class);
    }


    // protected $fillable = ['question_id', 'option_text'];

    // public function question()
    // {
    //     return $this->belongsTo(Questions::class);
    // }

    // public function selectedAnswers()
    // {
    //     return $this->hasMany(Answare::class);
    // }
}
