<?php
define('BR',"\n");
Locale::setDefault('en-US');

include 'RTCrypt.php';

$Crypter = new RTCrypt();
$key = $Crypter->genKey();
echo BR.'Your Key: '.BR.$Crypter->getStreight().BR.$key.BR.BR;
$Crypter->setScramble2($key);

$str = '/**
 * encode, decode and also serialize when nessesery.
 * Works with anything that php can serialize.
 * string, array, etc.
 *
 * @category     RoboTaMeR
 * @package      RTCrypt
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2011, Dennis T Kaplan
 * @license      http://RoboTamer.com/license.html
 * @link         http://RoboTamer.com
 * @todo         combine this with RTCrypt to one class
 */';
echo 'String before RTCrypt: '.BR.$str.BR.BR;

$str = $Crypter->encrypt($str);
echo 'RTCrpted: '.$str.BR.BR;
echo 'String after RTCrypt: '.BR.$Crypter->decrypt($str);
echo BR.BR;
?>
