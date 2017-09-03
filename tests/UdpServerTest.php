<?php
use PHPUnit\Framework\TestCase;
use UDPPush\UdpServer;

class UdpServerTest extends TestCase
{
    public function testServer()
    {
    	$ser = new UdpServer();
    	$ser->run();
    }

    public function testIpV4()
    {
    	$this->assertEquals('127.0.0.1',toIp4(16777343));
    }
}
