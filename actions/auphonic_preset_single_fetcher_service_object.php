<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Form;
use \Podlove\Modules\Podflow\Lib\Auphonic;
use \Podlove\Modules\Podflow\Lib\Logger;
use \Podlove\Modules\Podflow\Lib\Workflow_Execution;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Preset_Single_Fetcher_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $presets = Auphonic::get_presets_simple();
        
        if (sizeof($presets) == 1)
        {
            $execution->setVariable('episode_auphonic_preset', $presets[0]['uuid']);
        }

        return true;
    }

    public function __toString()
    {
        return "Auphonic_Finish_Check_Service_Object";
    }

}
