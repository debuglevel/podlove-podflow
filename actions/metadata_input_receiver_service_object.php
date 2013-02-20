<?php
namespace Podlove\Modules\Podflow\Actions;

class Metadata_Input_Receiver_Service_Object implements \ezcWorkflowServiceObject {
	public function __construct() {
	}

	public function execute(\ezcWorkflowExecution $execution) {
		$execution_id = $execution -> getVariable('execution_id');

		if (isset($_REQUEST['metadata'])) {
			$execution -> setVariable('episode_title', $_REQUEST['title']);
			$execution -> setVariable('episode_subtitle', $_REQUEST['subtitle']);
			$execution -> setVariable('episode_summary', $_REQUEST['summary']);

			echo '<p>Debug: I received metadata: The episode is called <strong>' . $_REQUEST['title'] . '</strong> and its subtitle is <strong>' . $_REQUEST['subtitle'] . '</strong>. The summary is: <strong>' . $_REQUEST['summary'] . '</strong>';
			return true;
		} else {
			echo '<p>Debug: I received no metadata.</p>';
			return false;
		}

	}

	public function __toString() {
		return "Metadata_Input_Receiver_Service_Object";
	}

}
