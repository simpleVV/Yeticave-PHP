<nav class="nav">
    <ul class="nav__list container">

        <?php foreach ($categories as $category) : ?>
            <li class="nav__item">
                <a href="filter.php?filter=<?= $category['name'] ?>">
                    <?= htmlspecialchars($category['name']); ?>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>
</nav>
