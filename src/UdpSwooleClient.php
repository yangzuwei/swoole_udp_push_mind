<?php

namespace UDPPush;
//在服务器上测试发一条消息 可以让远程客户端收到

class UdpSwooleClient extends \Swoole\Client
{
    public function __construct()
    {
        parent::__construct(SWOOLE_SOCK_UDP, SWOOLE_SOCK_ASYNC);
    }

    public function onConnect($cli)
    {
        $cli->send("I am a client with in server .hello world\n");
    }

    public function onReceive($cli, $data = "")
    {
        //$data = $cli->recv(); //1.6.10+ 不需要
        if(empty($data)){
            $cli->close();
            echo "closed\n";
        } else {
            echo "received: $data\n";
            sleep(1);
            $cli->send("I am a msg from server client :hello\n");
        }        
    }

    public function onError($cli)
    {
        exit("error\n");
    }

    public function run()
    {
        $this->on("connect",[$this,"onConnect"]);
        $this->on("receive",[$this,"onReceive"]);
        $this->on("error",[$this,"onError"]);
        $this->connect('127.0.0.1', 9505, 0.5);
    }
}
