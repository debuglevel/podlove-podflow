<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Auphonic;
use \Podlove\Modules\Podflow\Lib\Storage;
use \Podlove\Modules\Podflow\Lib\Logger;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Download_Service_Object implements \ezcWorkflowServiceObject
{

    private $execution;

    public function __construct()
    {
        
    }

    private function set_duration($productioninfo)
    {
        $duration = Auphonic::get_duration($productioninfo);
        $this->execution->setVariable('episode_duration', $duration);
    }

    private function set_slug($productioninfo)
    {
        $slug = Auphonic::get_basename($productioninfo);
        $this->execution->setVariable('episode_slug', $slug);
    }

    private function download_outputfile($outputfile)
    {
        $download_url = Auphonic::get_download_url($outputfile);
        $filename = Auphonic::get_filename($outputfile);
        $username = Auphonic::get_username();
        $password = Auphonic::get_password();

        $path = Storage::get_permanent_directory() . $filename;

        Logger::log('Downloading <strong>' . $download_url . '</strong> to <strong>' . $path . '</strong>.');

        $fp = fopen($path, 'w');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $download_url);
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
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

    private function download_outputfiles($output_files)
    {
        foreach ($output_files as $output_file)
        {
            $this->download_outputfile($output_file);
        }
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $this->execution = $execution;

        $uuid = $execution->getVariable('episode_auphonic_uuid');

        $productioninfo = Auphonic::get_productioninfo($uuid);

        $this->set_duration($productioninfo);
        $this->set_slug($productioninfo);

        $this->download_outputfiles($productioninfo['data']['output_files']);

        return true;
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
