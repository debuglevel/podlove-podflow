<?php

namespace Podlove\Modules\Podflow\Lib;

use \Podlove\Modules\Podflow\Lib\Database;
use \Podlove\Modules\Podflow\Lib\Workflow;

class Workflow
{

    private function setup_workflows($dbHandler)
    {
        $definitionStorage = new \ezcWorkflowDatabaseDefinitionStorage($dbHandler);
        $definitionStorage->options['prefix'] = Database::get_table_prefix();

        $auphonic_workflow_builder = new \Podlove\Modules\Podflow\Workflows\Auphonic_Workflow_Builder();
        $auphonic_workflow_definition = $auphonic_workflow_builder->build_workflow();
        $definitionStorage->save($auphonic_workflow_definition);
    }

    public function get_workflow($workflow_name, $dbHandler)
    {
        // Set up workflow definition storage (database).
        $definitionStorage = new \ezcWorkflowDatabaseDefinitionStorage($dbHandler);
        $definitionStorage->options['prefix'] = Database::get_table_prefix();

        $workflow = $definitionStorage->loadByName($workflow_name);
        return $workflow;
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

}

?>
