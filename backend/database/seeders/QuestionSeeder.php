<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->firstOrFail();

        $subject = Subject::create([
            'name' => 'Programming',
        ]);

        $question = $user->questions()->create([
            'question_type' => 'choice',
            'question_text' => 'Do you like Laravel?',
            'code' => 'abcde',
            'is_active' => true,
            'subject_id' => $subject->id,
        ]);
        $question->choices()->createMany([
            ['choice_text' => 'Yes'],
            ['choice_text' => 'No'],
        ]);

        $question = $user->questions()->create([
            'question_type' => 'open',
            'question_text' => 'What is your favorite programming language?',
            'code' => '12345',
            'is_active' => true,
            'subject_id' => $subject->id,
        ]);

        $user2 = User::where('email', 'user2@example.com')->firstOrFail();
        $question = $user2->questions()->create([
            'question_type' => 'choice',
            'question_text' => 'Do you like PHP?',
            'code' => '67890',
            'is_active' => true,
            'subject_id' => $subject->id,
        ]);
        $question->choices()->createMany([
            ['choice_text' => 'Yes'],
            ['choice_text' => 'No'],
        ]);



    }
}
