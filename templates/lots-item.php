<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?= $lot['img']; ?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category">
            <?= htmlspecialchars($lot['category']); ?>
        </span>
        <h3 class="lot__title">
            <a class="text-link" href="lot.php?id=<?= $lot['id'] ?>">
                <?= htmlspecialchars($lot['title']); ?>
            </a>
        </h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost">
                    <?= (htmlspecialchars($lot['price'])); ?> ₽
                </span>
            </div>

            <?php $classname_timer = !is_hour_left($lot['duration_time'])
                ? "timer--finishing"
                : ""
            ?>

            <div class="lot__timer timer <?= $classname_timer; ?>">
                <?= implode(": ", $lot['duration_time']); ?>
            </div>
        </div>
    </div>
</li>
