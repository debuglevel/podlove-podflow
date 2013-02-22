<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Form;
use \Podlove\Modules\Podflow\Lib\Workflow_Execution;

class Metadata_Input_Form_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $execution_id = Workflow_Execution::get_execution_id($execution);
        
        $next_number_guess = \Podlove\Modules\EpisodeAssistant\Episode_Assistant::instance()->guess_next_episode_id_for_show();
        $slug = \Podlove\Model\Podcast::get_instance()->slug;
        
        $title_guess = $slug.$next_number_guess.' ';

        $form_vars = array('execution_id' => $execution_id, 'title_guess' => $title_guess);
        Form::show_form('metadata_input.php', $form_vars);

        // Return true to signal that the service object has finished executing.
        return true;
    }

    public function __toString()
    {
        return "Metadata_Input_Form_Service_Object";
    }

}
