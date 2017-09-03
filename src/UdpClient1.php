<?php

//连接服务器
class UdpClient
{
	protected $handle;
	protected $nat;

	public function __construct($ip,$port)
	{
		//$this->handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr);
		//错误日志处理……
		$local_address = '0.0.0.0';
		$local_port = 8888;
		$this->dest_address = '182.92.180.29';
		$this->dest_port = 9505;

		$this->sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

		if (socket_bind($this->sock, $local_address, $local_port) === false) {
		    echo "socket_bind() failed:" . socket_strerror(socket_last_error($this->sock)) . "\n";
		}

	}

	//不知道为毛远程主机发不出去，本地主机就可以收到
	public function sendMsg($msg)
	{    
    	return socket_sendto($this->sock, $msg, strlen($msg), 0, $this->dest_address, $this->dest_port);
	}

	//此处可以放到后台进程中
	public function recvMsg()
	{
		while(true){
    		//$result = fread($this->handle, 1024);//此处可以读到服务端或者对方客户端发来的消息
    		$from = '';
			$port = 0;
			socket_recvfrom($this->sock, $buf, 12, 0, $from, $port);
    		var_dump($buf, $from,$port);exit;
    		$this->sendMsg("ping\n");//向服务器发探测包
    		$addr = @json_decode($buf);
    		$myRemoteIp =  stream_socket_get_name($this->handle, true);
    		var_dump($addr,$myRemoteIp);
    		if($addr->ip){
	    		if(!$this->nat){
	    			echo "first reg \n";
	    			$this->nat = stream_socket_client("udp://{$addr->ip}:{$addr->port}", $errno, $errstr);
	    			var_dump($addr->ip,$addr->port,$this->nat,$errno,$errstr);
	    			fwrite($this->nat,"I am your brother! \n");//首次对方网关消息 会被拒收 但是会被注册到已方网关路由表中 允许接受对方发来的消息
	    		}else{
	    			$this->natSend();
	    		}
    		}
		}

	}

	public function natSend()
	{
		while ($this->nat) {
			echo "this is nat logic \n";
			fwrite($this->nat,"I am your brother! \n");
			$bra = fread($this->nat, 1024);
			var_dump($bra);
		}
	}

}

$server = new UdpClient('182.92.180.29',9505);
var_dump($server->sendMsg("hello0000000000000000000000xxxxxxxxxxx"));
//$server->recvMsg();
// $server->sendMsg("hello world");
// $server->sendMsg("hello everyone");