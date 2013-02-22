<?php

namespace Podlove\Modules\Podflow\Lib;

class Logger
{

    public function log($msg)
    {
        echo '<p style="color: gray; font-size: smaller;">Debug: ' . $msg . '</p>';
    }

}

?>
