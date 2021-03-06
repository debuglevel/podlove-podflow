<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Auphonic;
use \Podlove\Modules\Podflow\Lib\Logger;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Production_Service_Object implements \ezcWorkflowServiceObject
{

    private $execution;
    
    public function __construct()
    {
        
    }

    private function set_auphonic_uuid($productioninfo)
    {
        $uuid = Auphonic::get_production_uuid($productioninfo);
        $this->execution->setVariable('episode_auphonic_uuid', $uuid);
        
        return $uuid;
    }
    
    public function execute(\ezcWorkflowExecution $execution)
    {
        $this->execution = $execution;
        
        $episode_temp_path = $execution->getVariable('episode_temp_path');
        $episode_title = $execution->getVariable('episode_title');
        $preset = $execution->getVariable('episode_auphonic_preset');
        
        $username = Auphonic::get_username();
        $password = Auphonic::get_password();
        
        $productioninfo = Auphonic::start_production($episode_temp_path, $episode_title, $preset, $username, $password);
        
        $uuid = $this->set_auphonic_uuid($productioninfo);

        Logger::log('The episode I pushed to Auphonic got the UUID <strong>' . $uuid . '</strong></p>');

        return true;
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
