<?php

namespace App\Action\CRM\Pipeline;

use App\Models\PipelineStage;

class DeleteStageAction
{
    public function execute(PipelineStage $stage): void
    {
        $stage->delete();
    }
}
