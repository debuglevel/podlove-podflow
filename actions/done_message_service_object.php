<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Form;
use \Podlove\Modules\Podflow\Lib\Workflow_Execution;

class Done_Message_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $execution_id = Workflow_Execution::get_execution_id($execution);
        
        $form_vars = array('execution_id' => $execution_id, 'site_url' => site_url());
        Form::show_form('done_message.php', $form_vars);

        return true;
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
