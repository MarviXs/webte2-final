<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasUuids;

    protected $fillable = [
        'owner_id',
        'question_text',
        'question_type',
        'code',
        'is_active',
        'subject_id',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function closures(): HasMany
    {
        return $this->hasMany(VoteClosure::class);
    }

    public static function generateCode($length = 5): string
    {
        do {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $code = $randomString;
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public static function prepareResultQuery(Question $question, $startTime = null, $endTime = null)
    {
        if ($question->question_type === 'choice') {
            $query = $question->choices()
                ->leftJoin('answers', function ($join) use ($question, $startTime, $endTime) {
                    $join->on('answers.choice_id', '=', 'choices.id')
                        ->where('answers.question_id', '=', $question->id);
                    if ($startTime) {
                        $join->where('answers.created_at', '>', $startTime);
                    }
                    if ($endTime) {
                        $join->where('answers.created_at', '<=', $endTime);
                    }
                })
                ->select('choices.choice_text as answer')
                ->selectRaw('COUNT(answers.id) as count')
                ->groupBy('choices.id');
        } else {
            $query = $question->answers()
                ->select('open_answer as answer')
                ->selectRaw('COUNT(*) as count')
                ->when($startTime, fn($query) => $query->where('answers.created_at', '>', $startTime))
                ->when($endTime, fn($query) => $query->where('answers.created_at', '<=', $endTime))
                ->groupBy('open_answer');
        }

        return $query;
    }
}
