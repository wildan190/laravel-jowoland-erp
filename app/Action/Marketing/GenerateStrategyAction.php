<?php

namespace App\Action\Marketing;

// Alternatively, use curl as below

class GenerateStrategyAction
{
    public function execute(string $prompt): string
    {
        $apiKey = env('GEMINI_API_KEY'); // Set this in .env with the provided key: AIzaSyDbO4BxqpE2lIccLBS33EcXQ3CGOApe1S4

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topP' => 0.95,
                'topK' => 40,
                'responseMimeType' => 'text/plain',
            ],
        ];

        // Using curl for the request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-goog-api-key: '.$apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No content generated.';
    }
}
