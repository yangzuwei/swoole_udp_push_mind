<?php
//具体说明见swoole官方文档memory  使用table作为注册表用来存放客户端ip和port
$table = new swoole_table(1024);
$table->column('ip', swoole_table::TYPE_STRING, 64);       //1,2,4,8
$table->column('port', swoole_table::TYPE_INT, 10);
$table->create();

$serv = new swoole_server("0.0.0.0", 9505,SWOOLE_PROCESS,SWOOLE_SOCK_UDP);
$serv->set(array(
    'worker_num' => 1,   //工作进程数量
    'daemonize' => false, //是否作为守护进程
));
$serv->on('connect', function ($serv, $fd){
    echo "Client:Connect.\n";
});

$serv->on('receive', function ($serv, $client_ip, $port, $data)use($table) {
    $ip = toIp4($client_ip);//地址转换
    var_dump($ip,$port,$data);

    //此处的table key 可以根据实际业务中的客户端唯一标识来设置 目前随便设置为时间戳 验证注册逻辑也可以放在这里
    $table->set(time().'',['ip'=>$ip,'port'=>$port]);

    //群发消息
    foreach($table as $p){
         $serv->sendto($p['ip'],$p['port'], 'Swoole: '.$data);
    }

});

$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

$serv->start();

//此处接收到的是int类型的ip地址 需要转化成点四分格式的string类型的ip地址 注意网络字节序和主机字节序
function toIp4($ipInt)
{
	$toHex = dechex($ipInt);
	$len = strlen($toHex);
	for($i = $len;$i>0;$i -= 2){
		$rs[] = hexdec(substr($toHex,$i-2,2));
	}
	return implode('.',$rs);	
}
