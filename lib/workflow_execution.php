<?php

namespace Podlove\Modules\Podflow\Lib;

class Workflow_Execution
{

    public function get_execution_id(\ezcWorkflowExecution $execution)
    {
        return $execution->getVariable('execution_id');
    }

    public function get_workflow_database_execution($execution_id = null)
    {
        $dbHandler = Database::get_database_handler();

        $options = new \ezcWorkflowDatabaseOptions;
        $options->prefix = Database::get_table_prefix();

        return new \ezcWorkflowDatabaseExecution($dbHandler, $execution_id,
                $options);
    }

    public function create_workflow_execution($workflow_name)
    {
        $execution = Workflow_Execution::get_workflow_database_execution();

        $dbHandler = Database::get_database_handler();
        Database::setup_tables($dbHandler); //TODO:
        Workflow::setup_workflows($dbHandler); //TODO: does not logically belong here. should really be moved to a installation hook 

        $execution->workflow = Workflow::get_workflow($workflow_name, $dbHandler);

        return $execution;
    }

    public function get_workflow_execution($execution_id)
    {
        $execution = Workflow_Execution::get_workflow_database_execution($execution_id);

        return $execution;
    }

    public function get_all_workflow_executions()
    {
        $dbHandler = Database::get_database_handler();

        $q = $dbHandler->createSelectQuery();
        $q->select('execution_id')->from(Database::get_table_prefix() . 'execution')
                ->orderBy('execution_id');
        $stmt = $q->prepare(); // $stmt is a normal PDOStatement
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $executions = array();

        foreach ($rows as $row)
        {
            $execution_id = (int) $row['execution_id']; // XXX: returns string instead of integer. I wonder if it is normal in PHP.
            $executions[] = Workflow_Execution::get_workflow_database_execution($execution_id);
        }

        return $executions;
    }

    public function get_all_workflow_executions_info()
    {
        $executions = Workflow_Execution::get_all_workflow_executions();

        $infos = array();

        foreach ($executions as $execution)
        {
            $info['workflow_execution'] = $execution;
            $info['id'] = $execution->getId();
            $info['name'] = $execution->workflow->name;

            if ($execution->isCancelled())
            {
                $info['state'] = 'cancelled';
            }
            else if ($execution->hasEnded())
            {
                $info['state'] = 'ended';
            }
            else if ($execution->isResumed())
            {
                $info['state'] = 'resumed';
            }
            else if ($execution->isSuspended())
            {
                $info['state'] = 'suspended';
            }
            else
            {
                $info['state'] = 'unknown';
            }

            $info['waitingfor'] = var_export($execution->getWaitingFor(), true);

            $info['variables'] = var_export($execution->getVariables(), true);

            $infos[] = $info;
        }

        return $infos;
    }

}

?>
