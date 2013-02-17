<?php
namespace Podlove\Modules\Podflow\Actions;

class Echo_Service_Object implements \ezcWorkflowServiceObject {
	private $message;

	public function __construct($message) {
		$this -> message = $message;
	}

	public function execute(\ezcWorkflowExecution $execution) {
		echo $this -> message;

		// Manipulate the workflow.
		// Does not affect the workflow, for illustration only.
		$execution -> setVariable('choice', true);

		// Return true to signal that the service object has finished
		// executing.
		return true;
	}

	public function __toString() {
		return "Echo_Service_Object, message {$this->message}";
	}

}
