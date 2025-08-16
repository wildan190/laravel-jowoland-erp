<?php

namespace App\Action\Marketing;

class GenerateSocialPostAction
{
    // Similar to GenerateStrategyAction, but tailored for social posts
    public function execute(string $prompt): string
    {
        // Reuse the same logic as above, just change the prompt in controller
        $action = new GenerateStrategyAction; // Reuse for simplicity

        return $action->execute($prompt);
    }
}
