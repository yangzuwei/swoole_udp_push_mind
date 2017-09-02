<?php
//在服务器上测试发一条消息 可以让远程客户端收到

$client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_ASYNC); //异步非阻塞

$client->on("connect", function($cli) {
    $cli->send("hello world\n");
});

$client->on("receive", function($cli, $data = ""){
    $data = $cli->recv(); //1.6.10+ 不需要
    if(empty($data)){
        $cli->close();
        echo "closed\n";
    } else {
        echo "received: $data\n";
        sleep(1);
        $cli->send("hello\n");
    }
});

$client->on("error", function($cli){
    exit("error\n");
});

$client->connect('127.0.0.1', 9505, 0.5);