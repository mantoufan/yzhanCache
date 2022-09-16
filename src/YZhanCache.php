<?php
class YZhanCache {
  private $engine;
  public function __construct(string $engineName, array $options) {
    $className = 'YZhanCache\\Engine\\' . $engineName . 'Engine';
    $this->engine = new $className($options);
  }
  public function set(string $key, string $val, int $maxAge) {
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
}