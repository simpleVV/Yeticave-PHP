<main>

    <?= $navigation ?>

    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= $option ?></span>»</h2>

            <?php if (empty($lots)) : ?>
                <p>Ничего не найдено по вашему запросу</p>
            <?php else : ?>

                <ul class="lots__list">
                    <?php foreach ($lots as $lot) : ?>
                        <?= include_template('lots-item.php', ['lot' => $lot]); ?>
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>

        </section>

        <?php if (($lots_num / SEARCH_PAGE_ITEMS) > DEFAULT_CURRENT_PAGE) : ?>
            <?= $pagination ?>
        <?php endif; ?>

    </div>
</main>
