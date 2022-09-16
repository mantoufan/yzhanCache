<?php
namespace YZhanCache\Tool;
class TimeTool {
  static private $now = null;
  static public function Now() {
    return self::$now !== null ? self::$now : time();
  }
  static public function SetNow(int $now) {
    self::$now = $now;
  }
}