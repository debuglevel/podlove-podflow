<?php

namespace Podlove\Modules\Podflow\Lib;

class Logger
{

    public function log($msg)
    {
        if (get_option('podflow_logging_enabled', false) == true)
        {
            echo '<p style="color: gray; font-size: smaller;">Debug: ' . $msg . '</p>';
        }
    }

}

?>
