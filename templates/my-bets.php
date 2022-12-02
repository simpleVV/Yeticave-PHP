<main>

    <?= $navigation ?>

    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">

            <?php foreach ($bets as $bet) : ?>
                <?php
                $rates_end = is_time_up($bet['duration_time'])
                    ? 'rates__item--end'
                    : '';

                $rates_win = is_bet_win($owner, $bet['winner_id'])
                    ? 'rates__item--win'
                    : '';

                $rate_class = $rates_end && $rates_win
                    ? $rates_win
                    : $rates_end;
                ?>

                <tr class="rates__item <?= $rate_class ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src=<?= $bet['image'] ?> width="54" height="40" alt="Сноуборд">
                        </div>

                        <?php if ($rate_class == $rates_win) : ?>
                            <div>
                                <h3 class="rates__title"><a href="lot.php?id=<?= $bet['lot_id'] ?>"><?= $bet['title'] ?></a></h3>
                                <p><?= $bet['contacts'] ?></p>
                            </div>
                        <?php else : ?>
                            <h3 class="rates__title"><a href="lot.php?id=<?= $bet['lot_id'] ?>"><?= $bet['title'] ?></a></h3>
                        <?php endif; ?>

                    </td>
                    <td class="rates__category">
                        <?= $bet['category'] ?>
                    </td>

                    <?php
                    $timer_fin = !is_hour_left($bet['duration_time'])
                        ? 'timer--finishing'
                        : '';

                    $timer_end = is_time_up($bet['duration_time'])
                        ? 'timer--end'
                        : '';

                    $timer_win = is_bet_win($owner, $bet['winner_id'])
                        ? 'timer--win'
                        : '';

                    $timer_class = $timer_fin;
                    $value = implode(': ', $bet['duration_time']);

                    if ($timer_end) {
                        $timer_class = $timer_end;
                        $value = 'Торги окончены';
                    }

                    if ($timer_win && $timer_end) {
                        $timer_class = $timer_win;
                        $value = 'Ставка выиграла';
                    }
                    ?>


                    <td class="rates__timer">
                        <div class="timer <?= $timer_class ?>">
                            <?= $value ?>
                        </div>
                    </td>
                    <td class="rates__price">
                        <?= $bet['price'] ?> р
                    </td>
                    <td class="rates__time">
                        <?= $bet['time'] ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        </table>
    </section>
</main>
