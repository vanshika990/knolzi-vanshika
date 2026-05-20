<?php

namespace App\Http\Controllers\Front\SubmitAnswer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\GroqServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class GroqAnswerController extends Controller
{
    protected $apiUrl;
    protected $apiKey;
    protected $model;
    protected $maxRequestsPerMinute = 30;   // Based on your limits
    protected $maxTokensPerMinute = 6000;
    public function __construct()
    {
        // Set your API URL and API Key from environment variables
        $this->apiUrl = env('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions');
        $this->apiKey = env('GROQ_API_KEY');
        $this->model = 'llama3-70b-8192';
    }

    /**
     * Get Score for a user's answer compared to the ideal answer.
     */
    public function getScore($idea_ans, $user_ans, $maxTokens = 1024, $temperature = 1)
    {
        $messageContent = "You are an assistant to a teacher. Your task is to help the teacher in assessing short and long text answers. You have access to the correct answer. You have to give a score between 0 and 100 by comparing the given answer with correct answer. Do not explain the reasoning, just give the number between 0 and 100, with 0 meaning completely different and 100 meaning exact match. Here we go. Given answer is '$user_ans' and Correct answer is '$idea_ans'.";

        Log::info('Sending message to GROQ API: ' . $messageContent);
        // Prepare request to GROQ API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            // 'content' => "You are an assistant that helps with grading user answers. Given the provided answer: 'Velocity is basically speeding in a specific direction. It is a vector quantity, which means we need both magnitude (speed) and direction to define velocity.
                            // ' and the user answer: 'Acceleration is defined as the rate of change of velocity with respect to time. Acceleration is a vector quantity as it has both magnitude and direction.', compare both answers and give a score from 0 to 100.
                            // The system should focus on the following criteria:
                            // Exactness of Content: Prioritize exact matches for key information. If the user's answer is missing or incorrect in critical areas, deduct significant points.
                            // Contextual Accuracy: Evaluate whether the user's answer fits the correct context. If the meaning of the user's answer contradicts the correct context, reduce the score substantially.
                            // Partial Credit: Assign partial scores only if parts of the answer are correct but incomplete. No partial credit should be given if the user's answer doesn't match the context.
                            // Synonyms and Phrasing: Give some leeway for alternative wording or use of synonyms, but maintain strictness on essential concepts. Only award points if the meaning remains identical.
                            // Strict Penalties for Misleading Information: Heavily penalize answers that introduce incorrect or misleading information, reducing the score considerably in these cases.
                            // Respond only with the score, no explanation."
                            "content" => "You are an assistant to a teacher. Your task is to help the teacher in assessing short and long text answers. You have access to the correct answer. You have to give a score between 0 and 100 by comparing the given answer with correct answer. Do not explain the reasoning, just give the number between 0 and 100, with 0 meaning completely different and 100 meaning exact match. Here we go. Given answer is '$user_ans' and Correct answer is '$idea_ans'. "
                        ],
                    ],
                    'max_tokens' => $maxTokens,  // Adjust the max tokens since we only want the score
                    'temperature' => $temperature,  // Set temperature to 0 to ensure deterministic output
                    'frequency_penalty' => 0.0,
                    'presence_penalty' => 0.0,
                ]);

        $statusCode = $response->status();

        if ($statusCode === 200) {
            // Successful response
            $data = $response->json();
            $score = $data['choices'][0]['message']['content'] ?? null;
            $CORRECT_THRESHOLD = 60;

            $isAnsCorrect = (int) $score > $CORRECT_THRESHOLD ? 1 : 0;

            return [
                'score' => (int) $score,
                'status' => $statusCode,
                'answer' => $isAnsCorrect
            ];
        } else if ($statusCode === 429) {
            // Handle error responses
            return [
                'error' => "Please try after some time. Too many requests.",
                'status' => $statusCode,
            ];
        } else {
            Log::info('GROQ API response for false' . $response);
            return false;
        }

        // if ($response->successful()) {
        //     // Parse response and return score
        //     $data = $response->json();
        //     // dd($data);

        //     $score = $data['choices'][0]['message']['content'] ?? null;


        //     $CORRECT_THRESHOLD = 60;

        //     $isAnsCorrect = (int) $score > $CORRECT_THRESHOLD ? 1 : 0;

        //     return $response->status();
        // }



        // if ($response->status() === 429) {
        //     return response()->json(['error' => $response->json()], 429);
        // }

        // return false;
    }
}
