<?php
namespace Podlove\Modules\Podflow\Actions;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Download_Service_Object implements \ezcWorkflowServiceObject {
	public function __construct() {
	}

	public function execute(\ezcWorkflowExecution $execution) {
		$execution_id = $execution -> getVariable('execution_id');
		$uuid = $execution -> getVariable('episode_auphonic_uuid');

		$username = get_option('podflow_auphonic_username', null);
		$password = get_option('podflow_auphonic_password', null);

		$client = new \Guzzle\Http\Client('https://auphonic.com/api', array('ssl.certificate_authority' => false));
		//XXX: ignoring ssl certs is a rather bad idea

		$request = $client -> get('production/' . $uuid . '.json') -> setAuth($username, $password);

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
		 
		    $curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $download_url);
		    curl_setopt($curl, CURLOPT_FILE, $fp);
			curl_setopt($curl, CURLOPT_USERPWD, $username.':'.$password); 
		    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		 	// $curl_log = fopen("php://memory", 'rw');
			// curl_setopt($curl, CURLOPT_STDERR, $curl_log);

		    $data = curl_exec($curl);
			
			// $info = curl_getinfo($curl);
			// $content=fread($curl_log,9999);
			// var_dump($data);
			// var_dump($content);
			// var_dump($info);
			// var_dump(curl_error($curl));
		 
		    curl_close($curl);
		    fclose($fp);
		}

		return true;
	}

	public function __toString() {
		return "Auphonic_Download_Service_Object";
	}

}
