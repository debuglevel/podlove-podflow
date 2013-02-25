<?php

namespace Podlove\Modules\Podflow\Lib;

use \Podlove\Modules\Podflow\Lib\Database;
use \Podlove\Modules\Podflow\Lib\Workflow;

class Workflow
{

    public function setup_workflows($dbHandler)
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

}

?>
