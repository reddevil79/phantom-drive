<?php

namespace App\Http\Helpers\Help;

use App\Http\Helpers\ZKT_KALPER;

class Face
{
    /**
     * Turn on the face recognition feature of the device.
     *
     * @param ZKT_KALPER $self The instance of the ZKT_KALPER class.
     * @return bool|mixed Returns true if the face recognition feature is turned on successfully, false otherwise.
     */
    static public function on(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = 'FaceFunOn';

        return $self->_command($command, $command_string);
    }
}
