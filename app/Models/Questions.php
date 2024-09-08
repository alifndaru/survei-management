<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'question_text', 'question_type', 'section'];

    public function options()
    {
        return $this->hasMany(Options::class, 'question_id');
    }

    public function answare()
    {
        return $this->hasMany(Answare::class);
    }

    public function getQuestion() {}
}
