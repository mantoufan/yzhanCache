<?php
namespace YZhanCache;
class YZhanCache {
  private $engine;
  private $engineName;
  public function __construct(string $engineName, array $params = array()) {
    $this->engineName = $engineName;
    $className = 'YZhanCache\\Engine\\' . $engineName . 'Engine';
    $this->engine = new $className($params);
  }
  public function set(string $key, $val, int $maxAge = null) {
    $this->engine->set($key, $val, $maxAge);
    return $this;
  }
  public function has(string $key) {
    return $this->engine->has($key);
  }
  public function get(string $key) {
    return $this->engine->get($key);
  }
  public function delete(string $key) {
    $this->engine->delete($key);
    return $this;
  }
  public function clear() {
    $this->engine->clear();
    return $this;
  }
  public function getEngineName() {
    return $this->engineName;
  }
}