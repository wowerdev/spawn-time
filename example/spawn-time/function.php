<?php

require __DIR__ . '/config.php';

if (!extension_loaded('mysqli')) {
  die("Необходимо подключить модуль mysqli для PHP.");
}

/**
 * Функция форматирования времени
 */
function formatSpawnTime($seconds)
{
  $days = floor($seconds / (24 * 3600));
  $seconds %= 24 * 3600;
  $hours = floor($seconds / 3600);
  $seconds %= 3600;
  $minutes = floor($seconds / 60);
  $seconds %= 60;

  $result = '';
  if ($days > 0) {
    $result .= $days . ' д. ';
  }
  if ($hours > 0) {
    $result .= $hours . ' ч. ';
  }
  if ($minutes > 0) {
    $result .= $minutes . ' м. ';
  }
  if ($seconds > 0 || $result === '') {
    $result .= $seconds . ' с.';
  }

  return trim($result);
}


/**
 * Возвращает данные о мобах в формате JSON
 *
 * @return string Данные мобов, где каждый элемент содержит:
 * - name: Имя моба
 * - respTime: Время до респавна в секундах
 * - isLive: Статус моба (true если жив, false если мертв)
 */
function getJsonSpawnData()
{
  global $spawn_servername;
  global $spawn_username;
  global $spawn_password;
  global $spawn_dbname;
  global $spawn_mobs;

  $conn = new mysqli($spawn_servername, $spawn_username, $spawn_password, $spawn_dbname);

  // Проверка соединения
  if ($conn->connect_error) {
    die("Ошибка соединения с БД, проверьте данные в config.php: " . $conn->connect_error);
  }
  // Текущие время в формате Unix timestamp
  $currentTime = time();

  $results = [];

  foreach ($spawn_mobs as $mob) {
    $mobId = $mob[0];
    $mobName = $mob[1];

    // SQL-запрос
    $sql = "SELECT respawnTime FROM respawn WHERE spawnId = $mobId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Получение данных
      $row = $result->fetch_assoc();
      $respawnTime = $row['respawnTime'];

      if ($respawnTime === null || $respawnTime <= $currentTime) {
        $results[] = ['name' => $mobName, 'respTime' => 0, 'respFormatTime' => 0, 'isLive' => true];
      } else {
        $timeToRespawn = $respawnTime - $currentTime;
        $results[] = ['name' => $mobName, 'respTime' => $timeToRespawn, 'respFormatTime' => formatSpawnTime($timeToRespawn), 'isLive' => false];
      }
    } else {
      $results[] = ['name' => $mobName, 'respTime' => 0, 'respFormatTime' => 0, 'isLive' => true];
    }
  }

  $conn->close();
  return json_encode($results);
}
