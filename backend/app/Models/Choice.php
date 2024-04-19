<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choice extends Model
{
    use HasUuids;

    protected $fillable = [
        'choice_text',
        'question_id',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

}
