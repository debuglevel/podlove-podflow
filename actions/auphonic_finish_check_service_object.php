<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Auphonic;
use \Podlove\Modules\Podflow\Lib\Logger;

include dirname(__FILE__) . '/../lib/guzzle.phar';

class Auphonic_Finish_Check_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        $uuid = $execution->getVariable('episode_auphonic_uuid');

        sleep(30); //just for testing purposes

        $productioninfo = Auphonic::get_productioninfo($uuid);

        $status = Auphonic::get_status($productioninfo);
        
        if ($status == 'Done')
        {
            Logger::log('Auphonic is done producing the episode.');
            return true;
        }
        else
        {
            Logger::log('Auphonic is not done yet producing the episode.');
            return false;
        }
    }

    public function __toString()
    {
        return "Auphonic_Finish_Check_Service_Object";
    }

}
