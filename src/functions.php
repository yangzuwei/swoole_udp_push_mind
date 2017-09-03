<?php

//此处接收到的是int类型的ip地址 需要转化成点四分格式的string类型的ip地址 注意网络字节序和主机字节序 
//以下是两种实现方式f1很清楚的能看出字节序的转换过程 f2使用了php内建的函数

function toIp4($ipInt)
{
    $toHex = dechex($ipInt);
    $len = strlen($toHex);
    if($len<8){
        $toHex = '0'.$toHex;
    }
    for($i = 1;$i<5;$i++){
        $rs[] = hexdec(substr($toHex,-2*$i,2));
    }
    return implode('.',$rs);    
}

function toIp($ipInt)
{
    $bin = pack('L',$ipInt);//L -- 无符号长整数 (32位，主机字节序)
    return inet_ntop($bin);
}