<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Form;
use \Podlove\Modules\Podflow\Lib\Workflow_Execution;
use \Podlove\Modules\Podflow\Lib\Episode_Assistant_Compensation;

class Metadata_Input_Form_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $execution_id = Workflow_Execution::get_execution_id($execution);
        
        $next_number_guess = Episode_Assistant_Compensation::guess_next_episode_id_for_show();
        $slug = Episode_Assistant_Compensation::slug();
        
        $title_guess = $slug.$next_number_guess.' ';

        $form_vars = array('execution_id' => $execution_id, 'title_guess' => $title_guess);
        Form::show_form('metadata_input.php', $form_vars);

        // Return true to signal that the service object has finished executing.
        return true;
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
