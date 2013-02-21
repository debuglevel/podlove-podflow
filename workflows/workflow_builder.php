<?php

namespace Podlove\Modules\Podflow\Workflows;

class Workflow_Builder
{

    public $name = "";
    protected $workflow;
    protected $process_steps = array();
    protected $conditions = array();

    public function build_workflow()
    {
        $this->workflow = new \ezcWorkflow($this->name);

        $this->build_process_steps();
        $this->connect_process_steps();

        return $this->workflow;
    }

    protected function build_process_steps()
    {
        // virtual - i.e. this function should be implemented by subclass
    }

    protected function connect_process_steps()
    {
        // virtual - i.e. this function should be implemented by subclass
    }

    // output can be parsed by GraphViz
    // $ dot -Tpng -ograph.png graph.dot
    public function to_graphviz()
    {
        $visitor = new ezcWorkflowVisitorVisualization();
        $this->workflow->accept($visitor);
        
        return (string) $visitor;
    }

    public function write_xml($directory)
    {
        $definitionStorage = new ezcWorkflowDefinitionStorageXml($directory);
        $definitionStorage->save($this->workflow);
    }

}
