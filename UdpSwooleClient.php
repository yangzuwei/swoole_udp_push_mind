<?php
//在服务器上测试发一条消息 可以让远程客户端收到

function sendMsg($ip,$port,$msg)
{
	$client = new swoole_client(SWOOLE_SOCK_UDP);
	$rs = $client->sendto($ip,$port,"{$msg}\n");
	$client->close();
	return $rs;
}

$ip = "127.0.0.1";
$port = 9505;
$msg = $argv[1];
sendMsg($ip,$port,$msg);
