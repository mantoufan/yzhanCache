<?php
use PHPUnit\Framework\TestCase;
use YZhanCache\YZhanCache;
use YZhanCache\Tool\TimeTool;
class YZhanCacheTest extends TestCase {
  public function testConstruct() {
    $yzhanCacheByFile = new YZhanCache('File');
    $this->assertEquals($yzhanCacheByFile->getEngineName(), 'File');
    return $yzhanCacheByFile;
  }
  public function dataProvider(): array {
    return array(
      array('max age is not set', '1', null, '1', true),
      array('no expired data', array(1, 2, 3), 2, array(1, 2, 3), true),
      array('expired data', '3', 1, null, false),
    );
  }
  /** 
   * @depends testConstruct
   * @dataProvider dataProvider
   */
  public function testSet(string $key, $val, $maxAge, $_, $__, &$yzhanCacheByFile) {
    TimeTool::SetNow(0);
    $this->assertEquals($yzhanCacheByFile->set($key, $val, $maxAge), $yzhanCacheByFile);
  }
  /** 
   * @depends testConstruct
   * @dataProvider dataProvider
   */
  public function testGet(string $key, $_, $__, $val = null, $___, &$yzhanCacheByFile) {
    TimeTool::SetNow(2);
    $this->assertEquals($yzhanCacheByFile->get($key), $val);
  }
  /** 
   * @depends testConstruct
   * @dataProvider dataProvider
   */
  public function testHas(string $key, $_, $__, $___, bool $has, &$yzhanCacheByFile) {
    $this->assertEquals($yzhanCacheByFile->has($key), $has);
  }
  /** 
   * @depends testConstruct
   * @dataProvider dataProvider
   */
  public function testDelete(string $key, $_, $__, $___, $____, &$yzhanCacheByFile) {
    $this->assertEquals($yzhanCacheByFile->delete($key), $yzhanCacheByFile);
    $this->assertFalse($yzhanCacheByFile->has($key));
  }
  /** 
   * @depends testConstruct
   * @dataProvider dataProvider
   */
  public function testClear(string $key, $val, $maxAge, $_, $__, &$yzhanCacheByFile) {
    $this->assertEquals($yzhanCacheByFile->set($key, $val, $maxAge), $yzhanCacheByFile);
    $this->assertEquals($yzhanCacheByFile->clear(), $yzhanCacheByFile);
    $this->assertFalse($yzhanCacheByFile->has($key));
  }
}