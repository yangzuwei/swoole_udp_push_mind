<?php

//连接服务器
class UdpClient
{
	protected $handle;

	public function __construct($ip,$port)
	{
		$this->handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr);
		//错误日志处理……
	}

	public function sendMsg($msg)
	{    		  
    	fwrite($this->handle, $msg."\n");
	}

	//此处可以放到后台进程中
	public function recvMsg()
	{
		while(true){
    		$result = fread($this->handle, 1024);
    		var_dump($result);
		}
	}

}

$server = new UdpClient('182.92.180.29',9505);
$server->sendMsg("hello");
$server->recvMsg();
$server->sendMsg("hello world");
$server->sendMsg("hello everyone");