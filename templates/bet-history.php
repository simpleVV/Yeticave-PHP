<div class="history">

    <?php if (!empty($bets)) : ?>
        <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
        <table class="history__list">

            <?php foreach ($bets as $bet) : ?>
                <tr class="history__item">
                    <td class="history__name"><?= htmlspecialchars($bet['user_name']) ?></td>
                    <td class="history__price"><?= htmlspecialchars($bet['price']) ?> р</td>
                    <td class="history__time"><?= htmlspecialchars($bet['time']) ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>
</div>
