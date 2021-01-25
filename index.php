<?php
/*
 * tinypingcheck v 1.13 // 2021.01.25
 * (c) kamilbaranski.com
 * nothing guaranteed:)
 *
 * copy the tpcConf.sample.php to tpcConf.php and set your device list there.
 */

require_once(__DIR__ . '/tinypingcheck.php');

/**
 * params:
 *   deviceListFileLocation: __DIR__ . '/deviceList.php'
 *   arp: 1/0 (shows arp list)
 *   grep: 1/0 (exlude (incomplete) arps)
 */
tinyPingCheck(
    __DIR__ . '/tpcConf.php',
    isset($_GET['arp']) ? $_GET['arp'] : 1,
    isset($_GET['grep']) ? $_GET['grep'] : 1
);
