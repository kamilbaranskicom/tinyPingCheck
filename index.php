<?php
/*
 * tinypingcheck v 1.12 // 2021.01.25
 * (c) kamilbaranski.com
 * nothing guaranteed:)
 *
 * copy the deviceList.sample.php to deviceList.php and set your device list there.
 * 
 * troubleshooting?
 *   you might need to "chmod u+s /bin/ping" before to allow ping by non-ROOT users. same for arp.
 *   (but don't do this if /bin/ping is a link to busybox! - security breach!)
 *   there's also debugCheck() with some potentially helping output.
 */

require_once(__DIR__ . '/tinypingcheck.php');

/**
 * params:
 *   deviceListFileLocation: __DIR__ . '/deviceList.php'
 *   arp: 1/0 (shows arp list)
 *   grep: 1/0 (exlude (incomplete) arps)
 */
tinyPingCheck(
    __DIR__ . '/deviceList.php',
    isset($_GET['arp']) ? $_GET['arp'] : 1,
    isset($_GET['grep']) ? $_GET['grep'] : 1
);
