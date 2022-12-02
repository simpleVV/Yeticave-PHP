<main>

    <?= $navigation ?>

    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«<?= $category ?>»</span></h2>

            <ul class="lots__list">

                <?php foreach ($lots as $lot) : ?>
                    <?= include_template('lots-item.php', ['lot' => $lot]); ?>
                <?php endforeach; ?>

            </ul>

        </section>

        <?php if (($lots_num / SEARCH_PAGE_ITEMS) > DEFAULT_CURRENT_PAGE) : ?>
            <?= $pagination ?>
        <?php endif; ?>

    </div>
</main>
