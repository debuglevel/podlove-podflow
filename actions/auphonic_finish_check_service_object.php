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

        do
        {
            Logger::log('Sleeping for 10s before bothering Auphonic about the production.');
            sleep(10);

            $productioninfo = Auphonic::get_productioninfo($uuid);
            $status = Auphonic::get_status($productioninfo);

            if ($status == 'Done')
            {
                Logger::log('Auphonic is done producing the episode.');
                $done = true;
            }
            else
            {
                Logger::log('Auphonic is not done yet producing the episode.');
                $done = false;
            }
        } while ($done === false);

        return true;
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
