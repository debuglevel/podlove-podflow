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

        $username = Auphonic::get_username();
        $password = Auphonic::get_password();
        
        $preset = get_option('podflow_auphonic_preset', null);  // TODO: user should be able to choose (if more than one prsets available)

        $productioninfo = Auphonic::start_production($episode_temp_path, $episode_title, $preset, $username, $password);
        
        $uuid = $this->set_auphonic_uuid($productioninfo);

        Logger::log('The episode I pushed to Auphonic got the UUID <strong>' . $uuid . '</strong></p>');

        return true;
    }

    public function __toString()
    {
        return "Auphonic_Production_Service_Object";
    }

}
