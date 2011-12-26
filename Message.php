<?php // TaMeR Message
/**
 * @category    RoboTamer
 * @package     Message
 * @copyright   Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license     http://robotamer.com/license.html
 * @link        http://robotamer.com
 *********************************************************/

/**
 * $msg = Singleton::factory('Message');
 *      OR
 * $msg = new Message();
 * $msg->add('Info', 'I');
 * $msg->add('Error','E');
 * $msg->add('Warn', 'W');
 * $msg->add('Success','S');
 * $msg->add('Stop', 'D');
 * echo $msg->get();
 *
 * @category    RoboTamer
 * @package     Message
 * @author      Dennis T Kaplan
 * @todo        Move style to css file
 * @todo        Make class Singleton
 */
class Message {

    public $msgStr = array();
    public $hasMsg = 0;
    public $hasErr = FALSE;

    const success = '<img src="/asset/icon/success.gif" alt="Success: " />';
    const info    = '<img src="/asset/icon/info.gif" alt="Info: " />';
    const warn    = '<img src="/asset/icon/warn.gif" alt="Warning: " />';
    const error   = '<img src="/asset/icon/error.gif" alt="Error: " />';
    const stop    = '<img src="/asset/icon/stop.gif" alt="Stop: " />';
    const red     = '#FF0000';
    const green   = '#008000';
    const yellow  = '#FF9900';
    const blue    = '#0000FF';

    public function __construct(){}

    public function clear()
    {
        $this->msgStr   = array();
        $this->hasMsg   = 0;
    }

    public function get()
    {
        $html = '<ul style="list-style: none;">'.PHP_EOL;
        foreach($this->msgStr as $v)
        {
            $html .=$v;
        }
        $html .= '</ul>'.PHP_EOL;
        return $html;
    }

    public function add($text, $type = 'E')
    {
        $col = $img = $name = '';
        switch ($type):
            case "I":
                $type = 'Info';
                $col = self::blue;
                $img = self::info;
                $this->hasMsg++;
                break;

            case "W":
                $type = 'Warn';
                $col = self::yellow;
                $img = self::warn;
                $this->hasMsg++;
                $this->hasErr = TRUE;
                break;

            case "E":
                $type = 'Error';
                $col = self::red;
                $img = self::error;
                $this->hasMsg++;
                $this->hasErr = TRUE;
                break;

            case "D":
                $type = 'Stop';
                $col = self::red;
                $img = self::stop;
                $this->hasMsg++;
                break;

            case "S":
                $type = 'Success';
                $col = self::green;
                $img = self::success;
                $this->hasMsg++;
                break;

            default:
                $type = 'Error';
                $col = self::red;
                $img = self::error;
                $this->hasMsg++;
                $this->hasErr = TRUE;

        endswitch;

        $html = '<li class="Message'.$type.'" style="background-color: '.$col.';">'.$img.' '.$text.'</li>'.PHP_EOL;
        array_push($this->msgStr, $html);
    }
}
?>
