<?php

namespace App\Http\Helpers\Help;

use App\Http\Helpers\ZKT_KALPER;

class Version
{
  /**
   * Retrieves the ZKT_KALPER device version information.
   *
   * This method sends a version command to the ZKT_KALPER device and retrieves the response containing
   * the device's firmware version.
   *
   * @param ZKT_KALPER $self An instance of the ZKT_KALPER class.
   * @return bool|mixed The device version string on success, false on failure.
   */
  static public function get(ZKT_KALPER $self)
  {
    $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

    $command = Util::CMD_VERSION; // Version information command code
    $command_string = ''; // Empty command string (no additional data needed)

    return $self->_command($command, $command_string); // Use internal ZKT_KALPER method to send the command
  }
}
