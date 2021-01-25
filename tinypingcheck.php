<?php

/*
 * tinypingcheck v 1.12 // 2021.01.25
 * (c) kamilbaranski.com
 * nothing guaranteed:)
 *
 * you might need to "chmod u+s /bin/ping" before to allow ping by non-ROOT users. same for arp.
 * copy the deviceList.sample.php to deviceList.php and set your device list there.
 */

function tinyPingCheck(
	$deviceListFileLocation,
	$arp = true,
	$grep = true
) {
	// turnOnErrorReporting();
	turnOffOutputBuffering();
	turnOffCache();
	sendTopHTML();
	require_once($deviceListFileLocation);
	if ($useUdhcpdConf) {
		$devices = array_merge($devices, getUdhcpdDevices($udhcpdConfFileLocation, $udhcpdRegExpPattern));
	};
	if ($useDnsmasqConf) {
		$devices = array_merge($devices, getDnsmasqDevices($dnsmasqConfFileLocation, $hostsFileLocation));
	};
	pingHostsAndEchoList($devices, $pingCommand);
	changeHeader();
	if ($arp) {
		showArpResults($grep, $arpCommand);
	};
	sendBottomHTML($arp);
};

function turnOnErrorReporting() {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	ini_set("track_errors", 1);
};

function turnOffOutputBuffering() {
	ini_set("output_buffering", 0);
	// if (ob_get_contents()) {						// ob_end_clean is necessary for turning off output buffering, but might throw a notice,
	// 												// that the buffer is empty. As error_reporting might be on (&& E_NOTIFY), this should be fixed.
	ob_end_clean(); // disable output buffer
	// };
	ob_implicit_flush(); // call flush() automatically after every output
	header('X-Accel-Buffering: no');
};

function turnOffCache() {
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
};

function sendTopHTML() {
	echo '<html><head>
<style>
LI { color: #a0a0a0; }
.exists { color: black; font-weight: bold; }
.hidden { display: none; }
</style>
</head><body>';
	echo '<h1 id="header">wait...</h1>';
};

function pingHostsAndEchoList($devices, $pingCommand) {
	echo '<ul>' . "\n";
	foreach ($devices as $device) {
		$deviceIP = $device['ip'];
		$deviceName = $device['name'];
		$pingResult = shell_exec($pingCommand.' -c 1 -w 1 ' . $deviceIP . ' 2>&1');		// 2>&1 means STDERR to STDOUT.
		if (strpos($pingResult, ' 100%') !== false) {
			$class = '';
			// "0% packet loss" or "100% packet loss". 100% means the device is not there.
		} else {
			$class = ' class="exists"';
		};
		echo '<li title="' . $pingResult . '"' . $class . '>' . $deviceIP . ': ' . $deviceName . "\n";
	}
	echo '</ul><hr>' . "\n";
};

function showArpResults($grep, $arpCommand) {
	// todo: should use $devices list here someday.
	echo '<div class="hidden" id="arpDiv"><h1>[' . gethostname() . ':~]$ arp';
	if ($grep) {
		// we do grep the better way.
		echo "<span id=\"grepCaption\" onclick=\"document.querySelector('#incomplete').classList.remove('hidden');";
		echo "this.classList.add('hidden');\" title=\"Press for all records (including incomplete)\">";
		echo ' | grep -v "(incomplete)"</span>';
	};
	echo '</h1>' . "\n<pre>";
	$arpResults = array_filter(explode("\n", shell_exec($arpCommand)), 'strlen');
	echo join("\n", array_filter($arpResults, 'isComplete'));
	echo "\n" . '<span id="incomplete" class="hidden">';
	echo join("\n", array_filter($arpResults, function ($line) {
		return !isComplete($line);
	}));
	echo '</span>';
	echo '<hr></pre></div>';
};

function isComplete($line) {
	return (strpos($line, '(incomplete)') === false);
};

function changeHeader() {
	echo "<script>
	document.querySelector('#header').innerText = 'Lista:';
</script>";
};

function sendBottomHTML($arp) {
	if ($arp) {
		echo "<a onclick=\"document.querySelector('#arpDiv').classList.remove('hidden');this.classList.add('hidden');\" title=\"Press for arp results\">[arp]</a> ";
	};
	echo 'dziękuję, do kasy. / &copy; <a href="http://kamilbaranski.com/">kb</a> 2021' . "\n";
	echo '</body></html>';
};

function getUdhcpdDevices($udhcpdConfFileLocation) {
	return array_values(array_filter(array_map(
		function ($var) {
			global $udhcpdRegExpPattern;
			// return strpos($var,'static_lease')!==false;
			if (preg_match($udhcpdRegExpPattern, $var, $matches)) {
				return array(
					'mac' => $matches[1],
					'ip' => $matches[2],
					'name' => $matches[3]
				);
			};
			return false;
		},
		preg_split('/\r\n|\r|\n/', file_get_contents($udhcpdConfFileLocation))
	)));
};

function getDnsmasqDevices(
	$dnsmasqConfFileLocation,
	$hostsFileLocation
) {

	$dnsmasqConfDevices = array_values(array_filter(array_map(
		function ($var) {
			$dnsmasqConfRegExpPattern = '/dhcp-host=([0-9a-fA-F\:]{17}),([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/';
			if (preg_match($dnsmasqConfRegExpPattern, $var, $matches)) {
				return array(
					'mac' => $matches[1],
					'ip' => $matches[2]
				);
			};
			return false;
		},
		preg_split('/\r\n|\r|\n/', file_get_contents($dnsmasqConfFileLocation))
	)));

	$hostsDevices = array_values(array_filter(array_map(
		function ($var) {
			$hostsPattern = '/([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+) *(.*)/';
			if (preg_match($hostsPattern, $var, $matches)) {
				return array(
					'ip' => $matches[1],
					'name' => $matches[2]
				);
			};
			return false;
		},
		preg_split('/\r\n|\r|\n/', file_get_contents($hostsFileLocation))
	)));

	$dnsmasqDeviceList = array();
	foreach ($dnsmasqConfDevices as $dnsmasqConfDevice) {
		$findHostsLine = array_find(
			$hostsDevices,
			function ($var, $drugi) {
				return ($var['ip'] == $drugi);
			},
			$dnsmasqConfDevice['ip']
		);
		if ($findHostsLine) {
			array_push(
				$dnsmasqDeviceList,
				array(
					'ip' => $dnsmasqConfDevice['ip'],
					'mac' => $dnsmasqConfDevice['mac'],
					'name' => $findHostsLine['name']
				)
			);
		} else {
			// we have the ip and the mac, but we don't have the name
			array_push(
				$dnsmasqDeviceList,
				array(
					'ip' => $dnsmasqConfDevice['ip'],
					'mac' => $dnsmasqConfDevice['mac'],
					'name' => 'anonymous.'
				)
			);
		}
	}
	// todo: shell we do something with omitted hosts entries? we should. someday.


	return $dnsmasqDeviceList;
}

function array_find($array, $functionName, ...$parameters) {
	// that's why: https://stackoverflow.com/questions/14224812/elegant-way-to-search-an-php-array-using-a-user-defined-function
	foreach ($array as $element) {
		if (call_user_func_array($functionName, array_merge(array($element), $parameters)) === true)
			return $element;
	}
	return null;
}

function debugCheck() {
	var_dump(shell_exec('whoami'));
	var_dump(shell_exec('which ping'));
	var_dump(shell_exec('ping -c 1 -w 1 192.168.50.2 2>&1'));
	var_dump(shell_exec('which arp'));
	var_dump(shell_exec('arp'));
}