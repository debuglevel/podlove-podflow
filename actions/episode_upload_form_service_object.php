<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Logger;
use \Podlove\Modules\Podflow\Lib\Workflow_Execution;
use \Podlove\Modules\Podflow\Lib\Form;

class Episode_Upload_Form_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $execution_id = Workflow_Execution::get_execution_id($execution);
        
        Logger::log('This is workflow instance <strong>' . $execution_id .'</strong>');

        $form_vars = array('execution_id' => $execution_id);
        Form::show_form('episode_upload.php', $form_vars);

        // Return true to signal that the service object has finished executing.
        return true;
    }

    public function __toString()
    {
        return "Episode_Upload_Form_Service_Object";
    }

}
