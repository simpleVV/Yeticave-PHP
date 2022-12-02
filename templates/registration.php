<main>

    <?= $navigation ?>

    <?php $classname_error = $errors ? 'form--invalid' : '' ?>

    <form class="form container <?= $classname_error; ?>" action="reg.php" method="post" autocomplete="off">
        <h2>Регистрация нового аккаунта</h2>

        <?php $classname_error = isset($errors['email'])
            ? 'form__item--invalid'
            : '';
        ?>

        <div class="form__item <?= $classname_error; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $user_data['email'] ?? '' ?>">

            <?php if (isset($errors['email'])) : ?>
                <span class="form__error"><?= $errors['email']; ?></span>
            <?php endif; ?>

        </div>

        <?php $classname_error = isset($errors['password'])
            ? 'form__item--invalid'
            : '';
        ?>

        <div class="form__item <?= $classname_error; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">

            <?php if (isset($errors['password'])) : ?>
                <span class="form__error"><?= $errors['password']; ?></span>
            <?php endif; ?>

        </div>

        <?php $classname_error = isset($errors['name'])
            ? 'form__item--invalid'
            : '';
        ?>

        <div class="form__item <?= $classname_error; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= $user_data['name'] ?? '' ?>">

            <?php if (isset($errors['name'])) : ?>
                <span class="form__error"><?= $errors['name']; ?></span>
            <?php endif; ?>

        </div>

        <?php $classname_error = isset($errors['message'])
            ? 'form__item--invalid'
            : '';
        ?>

        <div class="form__item <?= $classname_error; ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= $user_data['message'] ?? '' ?></textarea>

            <?php if (isset($errors['message'])) : ?>
                <span class="form__error"><?= $errors['message']; ?></span>
            <?php endif; ?>

        </div>

        <?php if (count($errors)) : ?>
            <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <?php else : ?>
            <span class="form__error form__error--bottom"></span>
        <?php endif; ?>

        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>
