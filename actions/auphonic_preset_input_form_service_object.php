<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Form;
use \Podlove\Modules\Podflow\Lib\Auphonic;
use \Podlove\Modules\Podflow\Lib\Logger;
use \Podlove\Modules\Podflow\Lib\Workflow_Execution;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Preset_Input_Form_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $execution_id = Workflow_Execution::get_execution_id($execution);

        $presets = Auphonic::get_presets_simple();

        $form_vars = array('execution_id' => $execution_id, 'presets' => $presets);
        Form::show_form('auphonic_preset_input.php', $form_vars);

        // Return true to signal that the service object has finished executing.
        return true;
    }

    public function __toString()
    {
        return "Auphonic_Finish_Check_Service_Object";
    }

}
