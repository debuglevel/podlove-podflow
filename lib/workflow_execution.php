<?php

namespace Podlove\Modules\Podflow\Lib;

class Workflow_Execution
{

    public function get_execution_id(\ezcWorkflowExecution $execution)
    {
        return $execution->getVariable('execution_id');
    }

    private function get_workflow_database_execution($execution_id = null)
    {
        $dbHandler = Database::get_database_handler();

        $options = new \ezcWorkflowDatabaseOptions;
        $options->prefix = Database::get_table_prefix();

        return new \ezcWorkflowDatabaseExecution($dbHandler, $execution_id,
                $options);
    }

    public function create_workflow_execution($workflow_name)
    {
        $execution = Workflow::get_workflow_database_execution();

        $dbHandler = Database::get_database_handler();
        Database::setup_tables($dbHandler); //TODO:
        Workflow::setup_workflows($dbHandler); //TODO: does not logically belong here. should really be moved to a installation hook 

        $execution->workflow = Workflow::get_workflow($workflow_name, $dbHandler);

        return $execution;
    }

    public function get_workflow_execution($execution_id)
    {
        $execution = Workflow::get_workflow_database_execution($execution_id);

        return $execution;
    }

    public function get_all_workflow_executions()
    {
        $dbHandler = Database::get_database_handler();
        
        $q = $dbHandler->createSelectQuery();
        $q->select('execution_id')->from(Database::get_table_prefix().'execution')
                ->orderBy('execution_id');
        $stmt = $q->prepare(); // $stmt is a normal PDOStatement
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $executions = array();
        
        foreach ($rows as $row)
        {
            $execution_id = (int)$row['execution_id']; // XXX: returns string instead of integer. I wonder if it is normal in PHP.
            $executions[] = Workflow_Execution::get_workflow_database_execution($execution_id);
        }

        return $executions;
    }

}

?>
