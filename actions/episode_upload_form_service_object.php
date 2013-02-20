<?php

namespace Podlove\Modules\Podflow\Actions;

class Episode_Upload_Form_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $execution_id = $execution->getVariable('execution_id');

        echo '<p>Debug: This is workflow instance ' . $execution_id . '</p>';

        include dirname(__FILE__) . '/../forms/episode_upload.php';

        // Return true to signal that the service object has finished executing.
        return true;
    }

    public function __toString()
    {
        return "Episode_Upload_Form_Service_Object";
    }

}
