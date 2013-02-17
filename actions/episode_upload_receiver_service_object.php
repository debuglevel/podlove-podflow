<?php
namespace Podlove\Modules\Podflow\Actions;

class Episode_Upload_Receiver_Service_Object implements \ezcWorkflowServiceObject {
	public function __construct() {
	}

	public function execute(\ezcWorkflowExecution $execution) {
		$execution_id = $execution->getVariable('execution_id');
		
		$upload_dir = wp_upload_dir();
		$target_path = $upload_dir['path'].'/'.$_FILES['userfile']['name'];
		$target_url = $upload_dir['url'].'/'.$_FILES['userfile']['name'];
		move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
		
		$execution_id = $execution->setVariable('episode_temp_path', $target_path);
		$execution_id = $execution->setVariable('episode_temp_url', $target_url);
		
		echo '<p>I received a file called <strong>'.$_FILES['userfile']['name'].'</strong> and moved it to <strong>'.$target_path.'</strong> and it\'s accessible via <strong>'.$target_url.'</strong></p>';

		// Return true to signal that the service object has finished executing.
		return true;
	}

	public function __toString() {
		return "Episode_Upload_Receiver_Service_Object";
	}

}
