<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Главная страница</title>
</head>

<body>
  <header>
    <p>Меню</p>
    <ul>
      <li><a>Главная страница (текущая)</a></li>
      <li><a href="/example/page">Внутренняя страница</a></li>
    </ul>
  </header>
  <h1>Пример подключения:</h1>
  <pre>&lt;?php require './spawn-time/get-html-spawn.php'; ?&gt;</pre>

  <h2>Результат:</h2>
  <?php require './spawn-time/get-html-spawn.php'; ?>
</body>

</html>