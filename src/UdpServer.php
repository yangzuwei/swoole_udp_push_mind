<?php
namespace UDPPush;

require_once __DIR__."/../vendor/autoload.php";//方便单独测试不用phpunit

class UdpServer
{
    protected $serv;
    protected $table;

    protected $config = [
            'worker_num' => 1,   //工作进程数量
            'daemonize' => false, //是否作为守护进程
    ];

    public function __construct()
    {
        $this->serv = new \Swoole\Server("0.0.0.0", 9505,SWOOLE_PROCESS,SWOOLE_SOCK_UDP);
        $this->serv->set($this->config);
        $this->setRegisterTable();        
    }

    protected function setRegisterTable()
    {
        //具体说明见swoole官方文档memory  使用table作为注册表用来存放客户端ip和port
        $this->table = new \Swoole\Table(1024);
        $this->table->column('ip', \Swoole\Table::TYPE_STRING, 64);       //1,2,4,8
        $this->table->column('port', \Swoole\Table::TYPE_INT, 10);
        $this->table->create();        
    }


    //此处是UDP的 这个方法没用
    public function onConnect($serv, $fd)
    {
        echo "Client:Connect.\n";
    }

    public function onReceive($serv, $client_ip, $port, $data)
    {
        $ip = toIp4($client_ip);//地址转换
        var_dump($ip,$port,$data);

        //此处的table key 可以根据实际业务中的客户端唯一标识来设置 目前随便设置为Ip 验证注册逻辑也可以放在这里
        $this->table->set($client_ip,['ip'=>$ip,'port'=>$port]);

        //群发消息 除了自己
        foreach($this->table as $p){
            if($ip != $p['ip']){
                $serv->sendto($p['ip'],$p['port'], 'Swoole: '.$data);
            }
        }        
    }

    //此处是UDP的 这个方法没用
    public function onClose()
    {
        echo "Client: Close.\n";
    }

    public function run()
    {
        $this->serv->on("receive",[$this,"onReceive"]);
        // $this->serv->on("connect",[$this,"onConnect"]);
        // $this->serv->on("close",[$this,"onClose"]);
        $this->serv->start();
    }
}