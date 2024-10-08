<?php
require __DIR__ . '/function.php';

$data = json_decode(getJsonSpawnData(), true); ?>

<style>
  .spwn__table {
    display: inline-block;
    box-sizing: border-box;
  }

  .spwn__table * {
    box-sizing: border-box;
  }

  .spwn__item {
    display: grid;
    grid-template-columns: 30px minmax(0, 200px) minmax(0, 160px);
  }

  .spwn__item-line {
    display: inline-block;
    padding: 6px;
    border: 1px solid #eee;
  }

  .spwn__item-status {
    position: relative;
  }

  .spwn__item-name,
  .spwn__item-time {
    text-wrap: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .spwn__item-status::before {
    content: '';
    position: relative;
    display: block;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: #ff4d4f;
  }

  .spwn__item-status.spwn__item-status--live::before {
    background-color: #52C41A;
  }
</style>

<script>
  // Скрипт для автообновления даты, без запросов к БД
  document.addEventListener('DOMContentLoaded', () => {
    (() => {

      function formatSpawnTime(seconds) {
        const days = Math.floor(seconds / (24 * 3600));
        seconds %= 24 * 3600;
        const hours = Math.floor(seconds / 3600);
        seconds %= 3600;
        const minutes = Math.floor(seconds / 60);
        seconds %= 60;

        let result = '';
        if (days > 0) {
          result += days + ' д. ';
        }
        if (hours > 0) {
          result += hours + ' ч. ';
        }
        if (minutes > 0) {
          result += minutes + ' м. ';
        }
        if (seconds > 0 || result === '') {
          result += seconds + ' с.';
        }

        return result.trim();
      }

      const spawnTime = document.querySelectorAll('.spwn__item-time-number');
      spawnTime.forEach((item) => {
        let currentIntervalId = setInterval(() => {
          const currentVal = +item.getAttribute('data-time');
          const newVal = currentVal - 1;
          if (newVal <= 0) {
            clearInterval(currentIntervalId);
          } else {
            item.setAttribute('data-time', newVal);
            item.setAttribute('title', formatSpawnTime(newVal));
            item.textContent = formatSpawnTime(newVal);
          }
        }, 1000);
      })


    })();
  })
</script>

<div class="spwn__table">
  <div class="spwn__item">
    <span class="spwn__item-line"></span>
    <span class="spwn__item-line spwn__item-name">Имя:</span>

    <span class="spwn__item-line spwn__item-time"><span>Время до реса:</span></span>

  </div>
  <?php foreach ($data as $mob):
    $isLive = $mob['isLive'];
    $respTime = $mob['respTime'];
    $respFormatTime = $mob['respFormatTime'];
    $name = $mob['name'];
  ?>
    <div class="spwn__item">
      <span class="spwn__item-line spwn__item-status <?= $isLive ? 'spwn__item-status--live' : '' ?>" title="<?= $isLive ? 'Жив' : 'Мёртв' ?>"></span>
      <span class="spwn__item-line spwn__item-name" title="<?= $name ?>"><?= $name ?></span>

      <span class="spwn__item-line spwn__item-time"><span class="spwn__item-time-number" data-time="<?= $respTime ?>" title="<?= $respFormatTime ?>"><?= $respFormatTime ? $respFormatTime : 'Жив' ?></span></span>

    </div>
  <?php endforeach; ?>
</div>