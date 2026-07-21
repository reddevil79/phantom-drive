<?php

namespace App\Http\Helpers\Help;

use App\Http\Helpers\ZKT_KALPER;

class Pin
{
    /**
     * Get the width of the PIN on the ZKT_KALPER device.
     *
     * @param ZKT_KALPER $self The instance of the ZKT_KALPER class.
     * @return bool|mixed Returns the width of the PIN if successful, false otherwise.
     */
    static public function width(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~PIN2Width';

        return $self->_command($command, $command_string);
    }
}
