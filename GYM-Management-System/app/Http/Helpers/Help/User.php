<?php

namespace App\Http\Helpers\Help;

use App\Http\Helpers\ZKT_KALPER;
use ErrorException;
use Exception;

class User
{
    /**
     * @param ZKT_KALPER $self
     * @param int $uid Unique ID (max 65535)
     * @param int|string $userid (max length = 9, only numbers - depends device setting)
     * @param string $name (max length = 24)
     * @param int|string $password (max length = 8, only numbers - depends device setting)
     * @param int $role Default Util::LEVEL_USER
     * @param int $cardno Default 0 (max length = 10, only numbers)
     * @return bool|mixed
     */
    static public function set(ZKT_KALPER $self, $uid, $userid, $name, $password, $role = Util::LEVEL_USER, $cardno = 0)
    {
        $self->_section = __METHOD__;

        if (
            (int)$uid === 0 ||
            (int)$uid > Util::USHRT_MAX ||
            strlen($userid) > 9 ||
            strlen($name) > 24 ||
            strlen($password) > 8 ||
            strlen($cardno) > 10
        ) {
            return false;
        }

        $command = Util::CMD_SET_USER;
        $byte1 = chr((int)($uid % 256));
        $byte2 = chr((int)($uid >> 8));
        $cardno = hex2bin(Util::reverseHex(dechex($cardno)));

        $command_string = implode('', [
            $byte1,
            $byte2,
            chr($role),
            str_pad($password, 8, chr(0)),
            str_pad($name, 24, chr(0)),
            str_pad($cardno, 4, chr(0)),
            str_pad(chr(1), 9, chr(0)),
            str_pad($userid, 9, chr(0)),
            str_repeat(chr(0), 15)
        ]);
//        die($command_string);
        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKT_KALPER $self
     * @return array [userid, name, cardno, uid, role, password]
     */
    static public function get(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_USER_TEMP_RRQ;
        $command_string = chr(Util::FCT_USER);

        $session = $self->_command($command, $command_string, Util::COMMAND_TYPE_DATA);
        if ($session === false) {
            return [];
        }

        $userData = Util::recData($self);

        $users = [];
        if (!empty($userData)) {
            $userData = substr($userData, 11);

            while (strlen($userData) > 72) {
                $u = unpack('H144', substr($userData, 0, 72));

                $u1 = hexdec(substr($u[1], 2, 2));
                $u2 = hexdec(substr($u[1], 4, 2));
                $uid = $u1 + ($u2 * 256);
                $cardno = hexdec(substr($u[1], 78, 2) . substr($u[1], 76, 2) . substr($u[1], 74, 2) . substr($u[1], 72, 2)) . ' ';
                $role = hexdec(substr($u[1], 6, 2)) . ' ';
                $password = hex2bin(substr($u[1], 8, 16)) . ' ';
                $name = hex2bin(substr($u[1], 24, 74)) . ' ';
                $userid = hex2bin(substr($u[1], 98, 72)) . ' ';

                //Clean up some messy characters from the user name
                $password = explode(chr(0), $password, 2);
                $password = $password[0];
                $userid = explode(chr(0), $userid, 2);
                $userid = $userid[0];
                $name = explode(chr(0), $name, 3);
                $name = utf8_encode($name[0]);
                $cardno = str_pad($cardno, 11, '0', STR_PAD_LEFT);

                if ($name == '') {
                    $name = $userid;
                }

                $users[$userid] = [
                    'uid' => $uid,
                    'userid' => $userid,
                    'name' => $name,
                    'role' => intval($role),
                    'password' => $password,
                    'cardno' => $cardno,
                ];

                $userData = substr($userData, 72);
            }
        }

        return $users;
    }

    /**
     * @param ZKT_KALPER $self
     * @return bool|mixed
     */
    static public function clear(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_DATA;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKT_KALPER $self
     * @return bool|mixed
     */
    static public function clearAdmin(ZKT_KALPER $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_ADMIN;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKT_KALPER $self
     * @param integer $uid
     * @return bool|mixed
     */
    static public function remove(ZKT_KALPER $self, $uid)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DELETE_USER;
        $byte1 = chr((int)($uid % 256));
        $byte2 = chr((int)($uid >> 8));
        $command_string = ($byte1 . $byte2);

        return $self->_command($command, $command_string);
    }

    static public function zkenrolluser(ZKT_KALPER $self, $userid) {
        $command = Util::CMD_STARTENROLL;
        $command_string = pack("a*", $userid);
        $chksum = 0;
        $session_id = $self->_session_id;

        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr( $self->_data_recv, 0, 8) );
        $reply_id = hexdec( $u['h8'].$u['h7'] );

        $buf = Util::createHeader($command, $chksum, $session_id, $reply_id, $command_string);

        socket_sendto($self->_zkclient, $buf, strlen($buf), 0, $self->_ip, $self->_port);

        try {
            @socket_recvfrom($self->_zkclient, $self->_data_recv, 1024, 0, $self->ip, $self->_port);

            $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr( $self->_data_recv, 0, 8 ) );

            $self->_session_id =  hexdec( $u['h6'].$u['h5'] );
            return substr( $self->_data_recv, 8 );
        } catch(ErrorException $e) {
            return FALSE;
        } catch(exception $e) {
            return False;
        }
    }
}
