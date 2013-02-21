<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Logger;

class Auphonic_Preset_Input_Receiver_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        if (isset($_REQUEST['preset']))
        {
            $preset_uuid = $_REQUEST['preset_uuid'];
            
            $execution->setVariable('episode_auphonic_preset', $preset_uuid);

            Logger::log('I received the preset uuid <strong>' . $preset_uuid . '</strong>');
            return true;
        }
        else
        {
            Logger::log('I received no preset uuid.');
            return false;
        }
    }

    public function __toString()
    {
        return "Auphonic_Preset_Input_Receiver_Service_Object";
    }

}
