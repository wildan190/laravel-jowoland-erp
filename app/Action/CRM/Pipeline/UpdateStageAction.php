<?php

namespace App\Action\CRM\Pipeline;

use App\Models\PipelineStage;

class UpdateStageAction
{
    public function execute(PipelineStage $stage, array $data)
    {
        $stage->update($data);
    }
}
