<?php
/*
 * tinypingcheck v 1.0 // 2021.01.11
 * (c) kamilbaranski.com
 * nothing guaranteed:)
 *
 * you might need to "chmod u+s /bin/ping" before to allow ping by non-ROOT users. same for arp.
 * copy the deviceList.sample.php to deviceList.php and set your device list there.
 */

require_once(__DIR__ . '/tinypingcheck.php');
require_once(__DIR__ . '/deviceList.php');

/**
 * params
 *   devices: array('ip'=>'name','ip2'=>'name2',...)
 *   arp: 1/0 (shows arp list)
 *   grep: 1/0 (exlude (incomplete) arps)
 */
tinyPingCheck(
    $devices,
    isset($_GET['arp']) ? $_GET['arp'] : 1,
    isset($_GET['grep']) ? $_GET['grep'] : 1
);
