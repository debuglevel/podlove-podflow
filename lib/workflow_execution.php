<?php

namespace Podlove\Modules\Podflow\Lib;

class Workflow_Execution
{

    public function get_execution_id(\ezcWorkflowExecution $execution)
    {
        return $execution->getVariable('execution_id');
    }

}

?>
