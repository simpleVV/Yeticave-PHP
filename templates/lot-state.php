<div class="lot-item__state">

    <?php $classname_timer = !is_hour_left($remain_time)
        ? "timer--finishing"
        : ""
    ?>

    <div class="lot-item__timer timer <?= $classname_timer; ?>">
        <?= implode(": ", $remain_time); ?>
    </div>
    <div class="lot-item__cost-state">
        <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?= htmlspecialchars($price); ?> ₽</span>
        </div>
        <div class="lot-item__min-cost">
            Мин. ставка <span><?= htmlspecialchars($min_bet); ?> р</span>
        </div>
    </div>

    <?php $classname_error = isset($errors['cost'])
        ? 'form__item--invalid'
        : '';
    ?>

    <form class="lot-item__form" action="lot.php?id=<?= $lot_id ?>" method="post" autocomplete="off">
        <p class="lot-item__form-item form__item <?= $classname_error ?>">
            <label for="cost">Ваша ставка</label>
            <input id="cost" type="text" name="cost" placeholder="<?= htmlspecialchars($min_bet); ?>">

            <?php if (isset($errors['cost'])) : ?>
                <span class="form__error"><?= $errors['cost']; ?></span>
            <?php endif; ?>

        </p>
        <button type="submit" class="button">Сделать ставку</button>
    </form>
</div>
