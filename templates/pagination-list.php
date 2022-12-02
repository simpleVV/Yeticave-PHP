    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">

            <?php if ($current_page == $pages[0]) : ?>
                <a>Назад</a>
            <?php else : ?>
                <a href="<?= $path ?>&page=<?= $prev_page ?>">Назад</a>
            <?php endif; ?>

        </li>

        <?php foreach ($pages as $page) : ?>

            <?php $classname_active = ($page == $current_page) ? 'pagination-item-active' : '' ?>

            <li class="pagination-item <?= $classname_active ?>">
                <a href="<?= $path ?>&page=<?= $page ?>">
                    <?= $page ?>
                </a>
            </li>
        <?php endforeach; ?>

        <li class="pagination-item pagination-item-next">

            <?php if ($current_page == count($pages)) : ?>
                <a>Вперед</a>
            <?php else : ?>
                <a href="<?= $path ?>&page=<?= $next_page ?>">Вперед</a>
            <?php endif; ?>

        </li>
    </ul>
