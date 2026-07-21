<?php

namespace App\Http\Helpers\Help;

use App\Http\Helpers\ZKT_KALPER;

class SerialNumber
{
    /**
     * Get the serial number of the ZKT_KALPER device.
     *
     * @param ZKT_KALPER $self The instance of the ZKT_KALPER class.
     * @return bool|mixed Returns the serial number if successful, false otherwise.
     */
    static public function get(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~SerialNumber';

        return $self->_command($command, $command_string);
    }
}
