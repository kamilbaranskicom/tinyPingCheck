<?php
/*
 * tinypingcheck v 1.10 // 2021.01.25
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
 *   devices:
 *      array(
 *          array(
 *              'ip'=>'192.168.1.1',
 *              'name'=>'router cisco',
 *              'mac'=>'12:34:56:78:90:ab'
 *              ),
 *          ...
 *       )
 *   arp: 1/0 (shows arp list)
 *   grep: 1/0 (exlude (incomplete) arps)
 *   useUdhcpdconf: true/false
 *   udhcpdConfFileLocation: '/etc/udhcpd.conf'
 *   udhcpdRegExpPattern: '/static_lease +([0-9a-fA-F\:]{17}) +([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+) *#(.*)/'
 */
tinyPingCheck(
    $devices,
    isset($_GET['arp']) ? $_GET['arp'] : 1,
    isset($_GET['grep']) ? $_GET['grep'] : 1,
    $useUdhcpdConf,
    $udhcpdConfFileLocation,
    $udhcpdRegExpPattern
);
