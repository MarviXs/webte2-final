<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'code' => $this->code,
            'is_active' => $this->is_active,
            'subject' => $this->subject->name,
            'choices' => ChoiceResource::collection($this->choices),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
