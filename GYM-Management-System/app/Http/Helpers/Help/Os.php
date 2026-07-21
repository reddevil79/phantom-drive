<?php

namespace App\Http\Helpers\Help;

use App\Http\Helpers\ZKT_KALPER;

class Os
{
    /**
     * Get the operating system information of the ZKT_KALPER device.
     *
     * @param ZKT_KALPER $self The instance of the ZKT_KALPER class.
     * @return bool|mixed Returns the operating system information if successful, false otherwise.
     */
    static public function get(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~OS';

        return $self->_command($command, $command_string);
    }
}
