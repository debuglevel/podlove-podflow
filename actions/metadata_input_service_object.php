<?php
namespace Podlove\Modules\Podflow\Actions;

class Metadata_Input_Service_Object implements \ezcWorkflowServiceObject {
	public function __construct() {
	}

	public function execute(\ezcWorkflowExecution $execution) {
		// $execution_id = $execution->getVariable('execution_id');
		include dirname(__FILE__).'/../forms/metadata_input.php';

		// Return true to signal that the service object has finished executing.
		return true;
	}

	public function __toString() {
		return "Metadata_Input_Service_Object";
	}

}
