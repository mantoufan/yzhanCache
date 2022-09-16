<?php
namespace YZhanCache\Engine;
class FileEngine {
  private $params;
  public function __construct(array $params) {
    $this->params = array_merge(array(
      'dir' => sys_get_temp_dir() . '/yzhancache',
    ), $params);
    if (is_dir($this->params['dir']) === false) mkdir($this->params['dir']);
  }
  private function getPath(string $key) {
    return $this->params['dir'] . '/' . $key . '.txt';
  }
  private function getDirPath(string $dir): object {
    if (is_readable($dir)) {
      $dirHanlder = opendir($dir);
      while (($file = readdir($dirHanlder)) !== false) {
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
          $subDir = $this->getDirPath($path);
          while ($subDir->vaild()) {
            yield $subDir->current();
            $subDir->next();
          }
        } else {
          yield $path;
        }
      }
    }
  }
  public function set(string $key, string $val, int $maxAge = null) {
    $data = array('val' => $val);
    if ($maxAge) $data['expires'] = time() + $maxAge;
    file_put_contents($this->getPath($key), serialize($data));
    return $this;
  }
  public function has(string $key): bool {
    return $this->get($key) !== null;
  }
  public function get(string $key) {
    $path = $this->getPath($key);
    if (file_exists($path) === false) return null;
    $data = unserialize($this->getPath($key));
    if (empty($data['expires']) === false && time() > $data['expires']) return null;
    return $data['val'];
  }
  public function delete(string $key): bool {
    $path = $this->getPath($key);
    if (file_exists($path) === false) return false;
    return true;
  }
  public function clear() {
    $dir = $this->getDirPath($this->params['dir']);
    while ($dir->valid()) {
      unlink($dir->current());
      $dir->next();
    }
    return $this;
  }
}
?>