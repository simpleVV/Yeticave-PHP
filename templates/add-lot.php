<main>

    <?= $navigation ?>

    <?php $classname_error = $errors ? 'form--invalid' : ''  ?>

    <form class="form form--add-lot container <?= $classname_error; ?>" action="../add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">

            <?php $classname_error = isset($errors['lot-name'])
                ? 'form__item--invalid'
                : '';
            ?>

            <div class="form__item <?= $classname_error; ?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота">

                <?php if (isset($errors['lot-name'])) : ?>
                    <span class="form__error"><?= $errors['lot-name']; ?></span>
                <?php endif; ?>

            </div>

            <?php $classname_error = isset($errors['category'])
                ? 'form__item--invalid'
                : '';
            ?>

            <div class="form__item <?= $classname_error; ?>">
                <label for=" category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value=<?= $category['id'] ?> <?php if ($category['id'] == get_post_value('category')) : ?> selected <?php endif; ?>>
                            <?= $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if (isset($errors['category'])) : ?>
                    <span class="form__error"><?= $errors['category']; ?></span>
                <?php endif; ?>

            </div>
        </div>

        <?php $classname_error = isset($errors['description'])
            ? 'form__item--invalid'
            : '';
        ?>

        <div class="form__item <?= $classname_error; ?> form__item--wide">
            <label for="description">Описание <sup>*</sup></label>
            <textarea id="description" name="description" placeholder="Напишите описание лота"></textarea>

            <?php if (isset($errors['message'])) : ?>
                <span class="form__error"><?= $errors['message']; ?></span>
            <?php endif; ?>

        </div>

        <?php $classname_error = isset($errors['file'])
            ? 'form__item--invalid'
            : '';
        ?>

        <div class="form__item <?= $classname_error; ?> 'form__item--invalid' form__item--file">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
                <label for="lot-img">
                    Добавить
                </label>

                <?php if (isset($errors['file'])) : ?>
                    <span class="form__error"><?= $errors['file']; ?></span>
                <?php endif; ?>

            </div>
        </div>
        <div class="form__container-three">

            <?php $classname_error = isset($errors['lot-rate'])
                ? 'form__item--invalid'
                : '';
            ?>

            <div class="form__item <?= $classname_error; ?> form__item--small">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" placeholder="0">

                <?php if (isset($errors['lot-rate'])) : ?>
                    <span class="form__error"><?= $errors['lot-rate']; ?></span>
                <?php endif; ?>

            </div>

            <?php $classname_error = isset($errors['lot-step'])
                ? 'form__item--invalid'
                : '';
            ?>

            <div class="form__item <?= $classname_error; ?> form__item--small">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" placeholder="0">

                <?php if (isset($errors['lot-step'])) : ?>
                    <span class="form__error"><?= $errors['lot-step']; ?></span>
                <?php endif; ?>

            </div>

            <?php $classname_error = isset($errors['lot-date'])
                ? 'form__item--invalid'
                : ''; ?>

            <div class="form__item <?= $classname_error; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД">

                <?php if (isset($errors['lot-date'])) : ?>
                    <span class="form__error"><?= $errors['lot-date']; ?></span>
                <?php endif; ?>

            </div>
        </div>

        <?php if (count($errors)) : ?>
            <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <?php else : ?>
            <span class="form__error form__error--bottom"></span>
        <?php endif; ?>

        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
