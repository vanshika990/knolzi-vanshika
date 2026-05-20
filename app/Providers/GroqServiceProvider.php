<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class GroqServiceProvider extends ServiceProvider
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('GROQ_API_URL');
        $this->apiKey = env('GROQ_API_KEY');
    }

    function generateScore($question, $userAnswer, $model = 'llama3-8b-8192', $maxTokens = 1024, $temperature = 1)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are an assistant that helps with grading user answers. With provided answer and user answer compare both answers and with user answer give score 0 to 100 in percentage. No need to give explaination just score.'],
                ['role' => 'user', 'content' => $question],
                ['role' => 'user', 'content' => $userAnswer],
            ],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
            'frequency_penalty' => 0.2,
            'presence_penalty' => 0.1
        ]);

        dd($response);
        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
