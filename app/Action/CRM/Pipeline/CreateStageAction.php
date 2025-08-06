<?php

namespace App\Action\CRM\Pipeline;

use App\Models\PipelineStage;

class CreateStageAction
{
    public function execute(array $data): PipelineStage
    {
        return PipelineStage::create($data);
    }
}
