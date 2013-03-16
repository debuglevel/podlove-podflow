<?php

namespace Podlove\Modules\Podflow\Lib;

// Podlove removed the Episode Assistent Module, on which Podflow relied - this is a rather stupid replacement

class Episode_Assistant_Compensation
{

    public function slug()
    {
        return "XX";
    }

    public function guess_next_episode_id_for_show()
    {
        $number = 1;

        $episodes = \Podlove\Model\Episode::all();
        foreach ($episodes as $episode)
        {
            if (preg_match("/\d+/", $episode->slug, $matches))
            {
                if ((int) $matches[0] > $number)
                {
                    $number = (int) $matches[0] + 1;
                }
            }
        }
        $number = "$number";

        // add leading zeros
        $leading_zeros = 3; //$this->get_module_option('leading_zeros', 3);
        if ($leading_zeros !== 'no')
        {
            while (strlen($number) < $leading_zeros)
            {
                $number = "0$number";
            }
        }

        return $number;
    }

}

?>
