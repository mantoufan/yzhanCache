# yzhanCache
Simple, effient cache class for PHP.  
简单、高效的 PHP 缓存类  
## Install 安装
```php
composer require mantoufan/yzhancache
```
## Use 使用
```php
$yzhanCache = new YZhanCache('File'); // Default: File Engine 
```
### File Engine Only: Change Cache Dir
```php
$yzhanCache = new YZhanCache('File', array(
  'dir' => sys_get_temp_dir() . '/yzhancache' // Default Cache Dir
));
```
### Set 设置  
```php
$yzhanCache->set($key, $val, $maxAge = null); // Unit of $maxAge is seconds
$yzhanCache->set('1', 'a', 1)->set('2', array(1, 2, 3)) // Chain call
```
### Get 获取
```php
$yzhanCache->get($key);
$yzhanCache->get('1'); // a
$yzhanCache->get('2'); // [1, 2, 3]
$yzhanCache->get('1'); // null, expires over $maxAge
```
### Has 是否存在
```php
$yzhanCache->has($key); // true / false
```
### Delete 删除
```php
$yzhanCache->delete($key);
$yzhanCache->set('1', 'a')->delete('1'); // Chain call
```
### Clear 清空
```php
$yzhanCache->clear();
$yzhanCache->clear()->set('1', 'a')->delete('1'); // Chain call
```