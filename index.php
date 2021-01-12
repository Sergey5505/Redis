<?php

echo "Выберите нужное меню (введите цифру от 1 до 7):\n";
echo "1. Key-value хранилище строк (запись в Redis даты и время)\n";
echo "2. Key-value хранилище списков\n";
echo "3. Key-value хранилище словарей\n";
echo "4. Операторы: GET и SET. Установка TTL на каждый ключ\n";
echo "5. Оператор KEYS\n";
echo "6. Оператор DEL\n";
echo "7. Удаление всех ключей\n";
echo ">";

$menu=readline();

switch ($menu) {
  case 1:
    key_value_line();
    break;
  case 2:
    key_value_list();
    break;
  case 3:
    key_value_dictionary();
    break;
  case 4:
      key_value_ttl();
      break;
  case 5:
      key_value_keys();
      break;
  case 6:
      key_value_dels();
      break;
  case 7:
      key_value_flushall();
      break;
  default:
    echo "Вы ввели: $menu\n";
    echo "Такого пункта меню нет\n";
    break;
}

// *******************************************************************
// 1. Key-value хранилище строк
function key_value_line() {

$redis = new Redis;
$redis -> connect('127.0.0.1', 6379);

while (true) {
// Запись в базу
$redis->INCR("counter");
$redis->SET('dTime', date('d.m.y H:i:s'));
// Чтение из базы
$timeCounter = $redis->GET('counter');
$timeSet = $redis->GET('dTime');

echo "$timeCounter   $timeSet\n";
sleep(1);
  }
}

// ***************************************************************
// 2. Key-value хранилище списков
function key_value_list() {

$redis = new Redis;
$redis -> connect('127.0.0.1', 6379);

echo "Введите список (разделяйте значения пробелами):\n";
echo ">";

$rPushListIO=readline();

// Добавление значений в список справа
$rPushListIOArray = explode(" ", $rPushListIO);
for($i = 0; $i < count($rPushListIOArray); $i++){
$redis->RPUSH('rPushList', $rPushListIOArray[$i]);
  }
// Получение всех значений из списка
$rPushLists = $redis->LRANGE('rPushList', 0, -1);

for($i = 0; $i < count($rPushLists); $i++){
echo "$rPushLists[$i]\n";
  }
}

// ***************************************************************
// 3. Key-value хранилище словарей
function key_value_dictionary() {

$redis = new Redis;
$redis -> connect('127.0.0.1', 6379);

echo "Введите слово в словарь (после нажмите Enter):\n";
echo ">";
$heshDictionaryIO=readline();

echo "Введите определение значения слова в словарь (после нажмите Enter):\n";
echo ">";
$heshDictionaryIO2=readline();

// Добавление значений в словарь
$redis->HSET('heshDictionary', $heshDictionaryIO, $heshDictionaryIO2);

// Получение всех значений из словаря
$heshDictionarys = $redis->HGETALL('heshDictionary');

var_dump($heshDictionarys);
}

// ***************************************************************
// 4. Операторы: GET и SET. Установка TTL на каждый ключ
function key_value_ttl() {

$redis = new Redis;
$redis -> connect('127.0.0.1', 6379);

echo "Введите строку (после нажмите Enter):\n";
echo ">";
$ttlIO=readline();

echo "Введите TTL (после нажмите Enter):\n";
echo ">";
$ttlIO2=readline();

// Добавление строки
$redis->SET('ttlTime', $ttlIO);
// Добавление оставшееся времени жизни строки
$redis->EXPIRE('ttlTime', $ttlIO2);

// Получение строки
$value = $redis->GET('ttlTime');
// Получение оставшееся время жизни строки
$ttls = $redis->TTL('ttlTime');

echo "Введеная Вами трока: $value\n";
echo "Оставшееся время жизни строки: $ttls\n";
}

// ***************************************************************
// 5. Оператор KEYS
function key_value_keys() {

$redis = new Redis;
$redis -> connect('127.0.0.1', 6379);

$allKeys = $redis->KEYS('*');

for($i = 0; $i < count($allKeys); $i++){
echo "$allKeys[$i]\n";
  }
}

// ***************************************************************
// 6. Оператор DEL
function key_value_dels() {

$redis = new Redis;
$redis -> connect('127.0.0.1', 6379);

echo "Введите строку (после нажмите Enter):\n";
echo ">";
$delIO=readline();

// Добавление строки
$redis->SET($delIO, $delIO);

// Все ключи
$allKeys = $redis->KEYS('*');
echo"\n";
echo "  Ключи:\n";
for($i = 0; $i < count($allKeys); $i++){
echo "$allKeys[$i]\n";
  }

echo "\n";
echo "Подождите 5 сек...\n";
sleep(5);

// Удаление ключей
$dels = $redis->DEL($delIO);

// Все ключи
$allKeys = $redis->KEYS('*');
echo"\n";
echo "  Ключи после DEL:\n";
for($i = 0; $i < count($allKeys); $i++){
echo "$allKeys[$i]\n";
  }
}

// ***************************************************************
// 7. Удаление всех ключей
function key_value_flushall() {

$redis = new Redis;
$redis -> connect('127.0.0.1', 6379);

$allFlushAll = $redis->FLUSHALL('');

echo "База очищенна\n";
}
