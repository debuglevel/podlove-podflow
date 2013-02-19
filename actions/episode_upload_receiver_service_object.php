<?php
namespace Podlove\Modules\Podflow\Actions;

class Episode_Upload_Receiver_Service_Object implements \ezcWorkflowServiceObject {
	public function __construct() {
	}

	public function execute(\ezcWorkflowExecution $execution) {
		$execution_id = $execution->getVariable('execution_id');
		
		if (isset($_FILES['episodefile']))
		{
			$upload_dir = wp_upload_dir();
			$target_path = $upload_dir['path'].'/'.$_FILES['episodefile']['name'];
			$target_url = $upload_dir['url'].'/'.$_FILES['episodefile']['name'];
			move_uploaded_file($_FILES['episodefile']['tmp_name'], $target_path);
			
			$execution->setVariable('episode_temp_path', $target_path);
			$execution->setVariable('episode_temp_url', $target_url);
			
			echo '<p>Debug: I received a file called <strong>'.$_FILES['episodefile']['name'].'</strong> and moved it to <strong>'.$target_path.'</strong> and it\'s accessible via <strong>'.$target_url.'</strong></p>';
			return true;
		}
		else
		{
			echo '<p>Debug: I received no file.</p>';
			return false;	
		}
		
	}

	public function __toString() {
		return "Episode_Upload_Receiver_Service_Object";
	}

}
