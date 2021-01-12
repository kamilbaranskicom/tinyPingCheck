<?php

/*
 * tinypingcheck v 1.01 // 2021.01.12
 * (c) kamilbaranski.com
 * nothing guaranteed:)
 *
 * you might need to "chmod u+s /bin/ping" before to allow ping by non-ROOT users. same for arp.
 * copy the deviceList.sample.php to deviceList.php and set your device list there.
 */

function tinyPingCheck($devices, $arp = true, $grep = true) {
	// turnOnErrorReporting();
	turnOffOutputBuffering();
	turnOffCache();
	sendTopHTML();
	pingHostsAndEchoList($devices);
	changeHeader();
	if ($arp) {
		showArpResults($grep);
	};
	sendBottomHTML($arp);
};

function turnOnErrorReporting() {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	ini_set("display_startup_errors", 1);
	ini_set("track_errors", 1);
}

function turnOffOutputBuffering() {
	ini_set("output_buffering", 0);
	if (ob_get_contents()) {
		ob_end_clean(); // disable output buffer
	};
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
STRONG { color: black; }
.hidden { display: none; }
</style>
</head><body>';
	echo '<h1 id="header">wait...</h1>';
};

function pingHostsAndEchoList($devices) {
	echo '<ul>' . "\n";
	foreach ($devices as $deviceIP => $deviceName) {
		$pingResult = shell_exec('ping -c 1 -w 1 ' . $deviceIP);
		if (strpos($pingResult, ' 100%') !== false) {
			// "0% packet loss" or "100% packet loss". 100% means the device is not there.
			echo '<li>' . $deviceIP . ': ' . $deviceName . "\n";
		} else {
			echo '<li><strong>' . $deviceIP . ': ' . $deviceName . '</strong>' . "\n";
		};
	}
	echo '</ul><hr>' . "\n";
};

function showArpResults($grep) {
	echo '<div class="hidden" id="arpDiv"><h1>['.gethostname().':~]$ arp';
	if ($grep) {
		// we do grep the better way.
		echo '<span id="grepCaption" onclick="'."document.querySelector('#incomplete').classList.remove('hidden');this.classList.add('hidden');".'"> | grep -v "(incomplete)"</span>';
	};
	echo '</h1>' . "\n<pre>";
	$arpResults=array_filter(explode("\n", shell_exec('arp')),strlen);
	echo join("\n",array_filter($arpResults,isComplete));
	echo '<span id="incomplete" class="hidden">';
	echo join("\n",array_filter($arpResults,function ($line) { return !isComplete($line); } ));
	echo '</span>';
	echo '<hr></pre></div>';
};

function isComplete($line) {
	return (strpos($line, '(incomplete)')===false);
};

function changeHeader() {
	echo "<script>
	document.querySelector('#header').innerText = 'Lista:';
</script>";
};

function sendBottomHTML($arp) {
	if ($arp) {
		echo "<a onclick=\"document.querySelector('#arpDiv').classList.remove('hidden');this.classList.add('hidden');\">[more]</a> ";
	};
	echo 'dziękuję, do kasy. / &copy; <a href="http://kamilbaranski.com/">kb</a> 2021' . "\n";
	echo '</body></html>';
};
