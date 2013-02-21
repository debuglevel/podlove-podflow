<?php

namespace Podlove\Modules\Podflow\Lib;

class Misc
{

    public function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

}

?>
