<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $fillable = [
        'answer',
        'is_correct'
    ];

    public function questions()
    {
        return $this->belongsTo(Question::class);
    }
}
