<?php

namespace Podlove\Modules\Podflow\Actions;

use \Podlove\Modules\Podflow\Lib\Logger;

class Metadata_Input_Receiver_Service_Object implements \ezcWorkflowServiceObject
{

    public function __construct()
    {
        
    }

    public function execute(\ezcWorkflowExecution $execution)
    {
        if (isset($_REQUEST['metadata']))
        {
            $title = $_REQUEST['title'];
            $subtitle = $_REQUEST['subtitle'];
            $summary = $_REQUEST['summary'];
            
            $execution->setVariable('episode_title', $title);
            $execution->setVariable('episode_subtitle', $subtitle);
            $execution->setVariable('episode_summary', $summary);

            Logger::log('I received metadata: The episode is called <strong>' . $title . '</strong> and its subtitle is <strong>' . $subtitle . '</strong>. The summary is: <strong>' . $summary . '</strong>');
            return true;
        }
        else
        {
            Logger::log('I received no metadata.');
            return false;
        }
    }

    public function __toString()
    {
        return __CLASS__;
    }

}
