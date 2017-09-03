<?php
use PHPUnit\Framework\TestCase;
use UDPPush\UdpSwooleClient;

class UdpSwooleClientTest extends TestCase
{
    public function testClient()
    {
    	$cli = new UdpSwooleClient();
    	$cli->run();
    }
}
