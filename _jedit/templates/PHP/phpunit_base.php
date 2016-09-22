<?php

require_once('PHPUnit/Framework.php'); // the framework
require_once(dirname(__FILE__).'/../MiniCache.php');// your class

class MiniCacheTest extends PHPUnit_Framework_TestCase {
    // test metods begin with 'test'
    public function testSetGet() {
        $this->assertEquals($getResult, $v);
    }

    public function testIsExpired() {
        $this->assertEquals(MiniCache::isExpired(2, 1), TRUE);
    }

    public function testExpiration() {
        $this->assertEquals($afterExpiration, $beforeExpiration);
    }
}

