<?php
namespace UDPPush;
//连接服务器 这个可以放在远端客户端来试验  本例子中以此例程为windows客户端
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

$server = new UdpClient('远端服务器地址',9505);
$server->sendMsg("hello I am a remote client from windows");
$server->recvMsg();