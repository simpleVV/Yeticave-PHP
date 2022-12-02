<main>

    <?= $navigation ?>

    <?php $classname_error = $errors ? 'form--invalid' : '' ?>

    <form class="form container <?= $classname_error; ?>" action="login.php" method="post">
        <h2>Вход</h2>

        <?php if (!empty($errors)) : ?>
            <span class="form__error form__error--bottom"><?= $errors['login']; ?></span>
        <?php endif; ?>

        <?php $classname_error = isset($errors['email'])
            ? 'form__item--invalid'
            : '';
        ?>

        <div class="form__item <?= $classname_error; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $form['email'] ?? '' ?>">

            <?php if (isset($errors['email'])) : ?>
                <span class="form__error"><?= $errors['email']; ?></span>
            <?php endif; ?>

        </div>

        <?php $classname_error = isset($errors['password'])
            ? 'form__item--invalid'
            : '';
        ?>

        <div class="form__item form__item--last <?= $classname_error; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">

            <?php if (isset($errors['password'])) : ?>
                <span class="form__error"><?= $errors['password']; ?></span>
            <?php endif; ?>

        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>

</div>
