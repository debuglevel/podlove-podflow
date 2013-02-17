<?php
namespace Podlove\Modules\Podflow\Workflows;

class Test02_Workflow_Builder extends Workflow_Builder {
	public function __construct() {
		$this -> name = "TestZwei";
	}

	protected function build_process_steps() {
		$this -> process_steps['start'] = new \ezcWorkflowNodeInput(array('choice' => new \ezcWorkflowConditionIsBool));
		$this -> process_steps['branch'] = new \ezcWorkflowNodeExclusiveChoice;
		$this -> process_steps['true'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Echo_Service_Object', 'arguments' => array('message: TRUE')));
		$this -> process_steps['false'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Echo_Service_Object', 'arguments' => array('message: FALSE')));
		$this -> process_steps['merge'] = new \ezcWorkflowNodeSimpleMerge;
	}

	protected function connect_process_steps() {
		$this -> workflow -> startNode -> addOutNode($this -> process_steps['start']);
		$this -> process_steps['branch'] -> addInNode($this -> process_steps['start']);
		$this -> process_steps['branch'] -> addConditionalOutNode(new \ezcWorkflowConditionVariable('choice', new \ezcWorkflowConditionIsTrue), $this -> process_steps['true']);
		$this -> process_steps['branch'] -> addConditionalOutNode(new \ezcWorkflowConditionVariable('choice', new \ezcWorkflowConditionIsFalse), $this -> process_steps['false']);
		$this -> process_steps['merge'] -> addInNode($this -> process_steps['true']);
		$this -> process_steps['merge'] -> addInNode($this -> process_steps['false']);
		$this -> process_steps['merge'] -> addOutNode($this -> workflow -> endNode);
	}

}
