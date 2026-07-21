<?php

namespace App\Http\Helpers\Help;

use App\Http\Helpers\ZKT_KALPER;

class Time
{
    /**
     * Set the time on the ZKT_KALPER device.
     *
     * @param ZKT_KALPER $self The instance of the ZKT_KALPER class.
     * @param string $t The time to set in the format "Y-m-d H:i:s".
     * @return bool|mixed Returns true if the time is set successfully, false otherwise.
     */
    static public function set(ZKT_KALPER $self, $t)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_SET_TIME;
        $command_string = pack('I', Util::encodeTime($t));

        return $self->_command($command, $command_string);
    }

    /**
     * Get the current time from the ZKT_KALPER device.
     *
     * @param ZKT_KALPER $self The instance of the ZKT_KALPER class.
     * @return bool|mixed Returns the current time if successful, false otherwise.
     */
    static public function get(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_GET_TIME;
        $command_string = '';

        $ret = $self->_command($command, $command_string);

        if ($ret) {
            return Util::decodeTime(hexdec(Util::reverseHex(bin2hex($ret))));
        } else {
            return false;
        }
    }
}
