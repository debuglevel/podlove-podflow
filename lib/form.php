<?php

namespace Podlove\Modules\Podflow\Lib;

class Form
{

    public function show_form($formfile, array $form_vars)
    {
        include dirname(__FILE__) . '/../forms/' . $formfile;
    }

}

?>
