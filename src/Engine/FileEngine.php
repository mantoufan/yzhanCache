<?php
namespace YZhanCache\Engine;
use YZhanCache\Tool\TimeTool;
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
  private function getDirGenerator(string $dir): iterable {
    if (is_readable($dir)) {
      $dirHanlder = opendir($dir);
      while (($file = readdir($dirHanlder)) !== false) {
        if ($file == '.' || $file == '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
          $dirGenerator = $this->getDirGenerator($path);
          while ($dirGenerator->valid()) {
            yield $dirGenerator->current();
            $dirGenerator->next();
          }
          yield array('dir', $path);
        } else {
          yield array('file', $path);
        }
      }
    }
  }
  public function set(string $key, $val, int $maxAge = null) {
    $data = array('val' => $val);
    if ($maxAge !== null) $data['expires'] = TimeTool::Now() + $maxAge;
    file_put_contents($this->getPath($key), serialize($data));
    return $this;
  }
  public function has(string $key): bool {
    return $this->get($key) !== null;
  }
  public function get(string $key) {
    $path = $this->getPath($key);
    if (file_exists($path) === false) return null;
    $data = unserialize(file_get_contents($this->getPath($key)));
    if (empty($data['expires']) === false && TimeTool::Now() > $data['expires']) {
      unlink($path);
      return null;
    }
    return $data['val'];
  }
  public function delete(string $key): bool {
    $path = $this->getPath($key);
    if (file_exists($path) === false) return false;
    unlink($path);
    return true;
  }
  public function clear() {
    $dirGenerator = $this->getDirGenerator($this->params['dir']);
    while ($dirGenerator->valid()) {
      list($type, $path) = $dirGenerator->current();
      if ($type === 'dir') rmdir($path);
      else unlink($path);
      $dirGenerator->next();
    }
    return $this;
  }
}
?>