<?php

/* 
 * Copy the file to deviceList.php, setup your device list here!
 */


// some may need different paths
$pingCommand = '/bin/ping';
$arpCommand = '/bin/arp';

// loads udhcpd.conf and parses for static leases
$useUdhcpdConf = true;
$udhcpdConfFileLocation = '/etc/udhcpd.conf';
$udhcpdRegExpPattern = '/static_lease +([0-9a-fA-F\:]{17}) +([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+) *#(.*)/';

// loads /etc/dnsmasq.conf and /etc/dnsmasq/hosts/hosts and parses for static leases
$useDnsmasqConf = true;
$dnsmasqConfFileLocation = '/etc/dnsmasq.conf';
$hostsFileLocation = '/etc/dnsmasq/hosts/hosts';


// my additional device list
$devices = array(

	array(
		'mac' => '12:34:56:78:90:ab',
		'ip' => '127.0.0.1',
		'name' => 'localhost'),

	array(
		'mac' => '12:34:56:78:90:ab',
		'ip' => '192.168.1.1',
		'name' => 'router'),

	array(
		'mac' => '12:34:56:78:90:ab',
		'ip' => '192.168.1.2',
		'name' => 'server'),

/*	array(
		'mac' => '12:34:56:78:90:ab',
		'ip' => '192.168.1.10',
		'name' => 'computer'),

	array(
		'mac' => '12:34:56:78:90:ab',
		'ip' => '192.168.1.30',
		'name' => 'phone'),

	//	etc
*/

);