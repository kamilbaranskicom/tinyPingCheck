<?php
/*
 * tinypingcheck v 1.16 // 2021.08.11
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
 *   background: 1/0 (pings in background - requires temp dir)
 */
tinyPingCheck(
    __DIR__ . '/tpcConf.php',
    isset($_GET['arp']) ? $_GET['arp'] : 1,
    isset($_GET['grep']) ? $_GET['grep'] : 1,
    isset($_GET['background']) ? $_GET['bg'] : 1,
    isset($_GET['dumpleases']) ? $_GET['dumpleases'] : 1
);
