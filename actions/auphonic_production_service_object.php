<?php
namespace Podlove\Modules\Podflow\Actions;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Production_Service_Object implements \ezcWorkflowServiceObject {
	public function __construct() {
	}

	public function execute(\ezcWorkflowExecution $execution) {
		$execution_id = $execution -> getVariable('execution_id');
		$episode_temp_path = $execution -> getVariable('episode_temp_path');

		$username = get_option('podflow_auphonic_username', null);
		$password = get_option('podflow_auphonic_password', null);
		$preset = get_option('podflow_auphonic_preset', null);

		$client = new \Guzzle\Http\Client('https://auphonic.com/api/simple', array('ssl.certificate_authority' => false));
		//XXX: ignoring ssl certs is a rather bad idea

		$request = $client -> post('productions.json', null, array('preset' => $preset, 'title' => 'megatitle', 'action' => 'start', 'input_file' => '@' . $episode_temp_path)) -> setAuth($username, $password);

		$response = $request -> send();

		$data = $response -> json();
		$uuid = $data['data']['uuid'];
		$execution -> setVariable('episode_auphonic_uuid', $uuid);
		
		echo "<p>Debug: The episode I pushed to Auphonic got the UUID <strong>" . $uuid . "</strong></p>";

		return true;
	}

	public function __toString() {
		return "Auphonic_Production_Service_Object";
	}

}
