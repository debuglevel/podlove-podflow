<?php
namespace Podlove\Modules\Podflow\Workflows;

class Auphonic_Workflow_Builder extends Workflow_Builder {
	public function __construct() {
		$this -> name = "Auphonic";
	}

	protected function build_process_steps() {
		$this -> process_steps['get_execution_id'] = new \ezcWorkflowNodeInput(array('execution_id' => new \ezcWorkflowConditionIsInteger));
		$this -> process_steps['episode_upload_form'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Episode_Upload_Form_Service_Object'));
		$this -> process_steps['episode_upload_receiver'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Episode_Upload_Receiver_Service_Object'));
		$this -> process_steps['episode_upload_wait'] = new \ezcWorkflowNodeInput(array('episode_temp_url' => new \ezcWorkflowConditionIsString, 'episode_temp_path' => new \ezcWorkflowConditionIsString));
		$this -> process_steps['metadata_input'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Metadata_Input_Form_Service_Object'));
		$this -> process_steps['metadata_receiver'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Metadata_Input_Receiver_Service_Object'));
		$this -> process_steps['metadata_wait'] = new \ezcWorkflowNodeInput(array('episode_title' => new \ezcWorkflowConditionIsString, 'episode_subtitle' => new \ezcWorkflowConditionIsString, 'episode_summary' => new \ezcWorkflowConditionIsString));
		$this -> process_steps['auphonic_production'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Auphonic_Production_Service_Object'));
		$this -> process_steps['auphonic_wait'] = new \ezcWorkflowNodeInput(array('episode_auphonic_uuid' => new \ezcWorkflowConditionIsString));
		$this -> process_steps['auphonic_finishcheck'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Auphonic_Finish_Check_Service_Object'));
		$this -> process_steps['auphonic_download'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Auphonic_Download_Service_Object'));
		//$this -> process_steps['fileserver_move'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Fileserver_Move_Service_Object'));
		$this -> process_steps['podlove_publish'] = new \ezcWorkflowNodeAction( array('class' => '\Podlove\Modules\Podflow\Actions\Podlove_Publish_Service_Object'));
	}

	protected function connect_process_steps() {
		// $this -> workflow -> startNode -> addOutNode($this -> process_steps['episode_upload']);
		$this -> process_steps['get_execution_id'] -> addInNode($this -> workflow -> startNode);
		$this -> process_steps['episode_upload_form'] -> addInNode($this -> process_steps['get_execution_id']);
		$this -> process_steps['episode_upload_receiver'] -> addInNode($this -> process_steps['episode_upload_form']);
		$this -> process_steps['episode_upload_wait'] -> addInNode($this -> process_steps['episode_upload_receiver']);
		$this -> process_steps['metadata_input'] -> addInNode($this -> process_steps['episode_upload_wait']);
		$this -> process_steps['metadata_receiver'] -> addInNode($this -> process_steps['metadata_input']);
		$this -> process_steps['metadata_wait'] -> addInNode($this -> process_steps['metadata_receiver']);
		$this -> process_steps['auphonic_production'] -> addInNode($this -> process_steps['metadata_wait']);
		$this -> process_steps['auphonic_wait'] -> addInNode($this -> process_steps['auphonic_production']);
		$this -> process_steps['auphonic_finishcheck'] -> addInNode($this -> process_steps['auphonic_wait']);
		$this -> process_steps['auphonic_download'] -> addInNode($this -> process_steps['auphonic_finishcheck']);
		//$this -> process_steps['fileserver_move'] -> addInNode($this -> process_steps['auphonic_download']);
		$this -> process_steps['podlove_publish'] -> addInNode($this -> process_steps['auphonic_download']);
		$this -> workflow -> endNode -> addInNode($this -> process_steps['podlove_publish']);
	}

}
