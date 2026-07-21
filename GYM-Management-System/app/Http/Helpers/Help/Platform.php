<?php

namespace App\Http\Helpers\Help;

use App\Http\Helpers\ZKT_KALPER;

class Platform
{
    /**
     * Get the platform information of the ZKT_KALPER device.
     *
     * @param ZKT_KALPER $self The instance of the ZKT_KALPER class.
     * @return bool|mixed Returns the platform information if successful, false otherwise.
     */
    static public function get(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~Platform';

        return $self->_command($command, $command_string);
    }

    /**
     * Get the version of the platform on the ZKT_KALPER device.
     *
     * @param ZKT_KALPER $self The instance of the ZKT_KALPER class.
     * @return bool|mixed Returns the platform version if successful, false otherwise.
     */
    static public function getVersion(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~ZKFPVersion';

        return $self->_command($command, $command_string);
    }
}
