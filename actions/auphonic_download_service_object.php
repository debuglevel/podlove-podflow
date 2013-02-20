<?php
namespace Podlove\Modules\Podflow\Actions;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Download_Service_Object implements \ezcWorkflowServiceObject {
	public function __construct() {
	}

	public function execute(\ezcWorkflowExecution $execution) {
		$execution_id = $execution -> getVariable('execution_id');
		$uuid = $execution -> getVariable('episode_auphonic_uuid');

		$client = new \Guzzle\Http\Client('https://auphonic.com/api', array('ssl.certificate_authority' => false));
		//XXX: ignoring ssl certs is a rather bad idea

		$request = $client -> get('production/' . $uuid . '.json') -> setAuth('username', 'password');

		$response = $request -> send();

		$data = $response -> json();
		
		$duration = $data['data']['length_timestring'];
		$execution -> setVariable('episode_duration', $duration);
		
		$output_files = $data['data']['output_files'];
		
		foreach ($output_files as $output_file)
		{
			$download_url = $output_file['download_url'];
			$filename = $output_file['filename'];
			$execution -> setVariable('episode_slug', basename($filename, ".mp3")); // XXX: well, that's really just for testing 
			
		    $path = '/opt/lampp/htdocs/podlovemedia/'.$filename;
		 
		 	echo '<p>Debug: Downloading <strong>'.$download_url.'</strong> to <strong>'.$path.'</strong>.</p>';
		 
		    $fp = fopen($path, 'w');
		 
		    $ch = curl_init($download_url);
		    curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_USERPWD, 'username'.':'.'password'); 
		 
		    $data = curl_exec($ch);
		 
		    curl_close($ch);
		    fclose($fp);
		}

		return true;
	}

	public function __toString() {
		return "Auphonic_Download_Service_Object";
	}

}
