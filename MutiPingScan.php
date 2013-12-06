#!/opt/local/bin/php55
<?php

if(count($argv) != 4)
{
	echo "ping 8.8.8.1 ~ 8.8.8.10\n";
	echo "usage: php ./MutiPingScan.php 8.8.8 1 10\n";
	exit;
}
$net = $argv[1]; // 8.8.8
$min = $argv[2]; // 1
$max = $argv[3]; // 10



$time_out = 1;
$one_time_limit = 43;

$tmp_file = "/tmp/tmp_ping_".time().rand(0,1000);
echo "$tmp_file\n";
$data='';

$times = 0;
for($i=$min;$i<=$max;$i++)
{
	if(($times % $one_time_limit) == ($one_time_limit-1))
	{
		sleep($time_out+0.5);
	}
	$times++;
	
	$ip = "$net.$i";
	$c_ping='ping -c 1 -t 1 '.$ip.'|tail -n 2';
	
	$pid=pcntl_fork();
	if($pid==-1){
		exit();
	}else if($pid){
		
	}else{
		break;
	}
	
}


if($pid)
{
	$status = null;
	sleep($time_out);
	$ip_info = Array();
	$file = File("$tmp_file");
	foreach($file as $line)
	{
		$ips = explode(".", trim($line), 6);
		if($ips[5] == NULL)
			continue;
		$ip_info[$ips[3]] = $ips[5];
	}
	for($i=$min;$i<=$max;$i++)
	{
		if(isset($ip_info[$i]))
			echo "$net.$i:Yes {$ip_info[$i]} ms\n";
		else
			echo "$net.$i:No\n";
	}
}
else
{
		$c_ping=`$c_ping`;
		$pr=trim(substr($c_ping,strpos($c_ping,'transmitted,')+13,3));
		$tmp=explode('/',$c_ping);
		$ms=$tmp[4];
		$fp = fopen("$tmp_file","a");
		fwrite($fp,$ip.".Yes.$ms\n");
		fclose($fp);
}
?>
