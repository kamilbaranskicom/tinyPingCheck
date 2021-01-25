<?php

/* 
 *
 * Copy the file to tpcConf.php, setup your device list here!
 * 
 * 
 ****************************************************************************
 * troubleshooting?
 * 
 * you might need to "chmod u+s /bin/ping" to allow ping by non-ROOT users.
 * (but don't do this if /bin/ping is a link to busybox! - security breach!)
 * same for arp.
 * 
 * there's also debugCheck() with some potentially helping output.
 * (your system can use different ping then /bin/ping).
 * If so, just remove the "//" before debugCheck(); below.
 */


// debugCheck();


// some may need different paths
$pingCommand = '/bin/ping';
$arpCommand = '/usr/sbin/arp';


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