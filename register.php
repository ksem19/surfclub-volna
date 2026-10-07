<?php
require_once 'config.php';
if (isLoggedIn()) redirect('cabinet.php');

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $fio = trim($_POST['fio'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $birth = $_POST['birth_date'] ?? null;

    if (strlen($login) < 6 || !preg_match('/^[a-zA-Z0-9]+$/', $login)) {
        $errors['login'] = 'Логин: латиница и цифры, минимум 6 символов';
    }
    if (strlen($password) < 8) {
        $errors['password'] = 'Пароль не менее 8 символов';
    }
    if ($password !== $password2) {
        $errors['password2'] = 'Пароли не совпадают';
    }
    if (empty($fio)) $errors['fio'] = 'Укажите ФИО';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Некорректный e-mail';

    if (empty($errors)) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $errors['login'] = 'Такой логин уже занят';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (login, password, fio, email, phone, birth_date) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$login, $hash, $fio, $email, $phone ?: null, $birth ?: null]);
            $success = true;
        }
    }
}

$pageTitle = 'Регистрация';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="form-card">
        <h2>Регистрация</h2>
        <?php if ($success): ?>
            <div class="form-success">Аккаунт создан! Теперь вы можете <a href="login.php">войти</a>.</div>
        <?php else: ?>
        <form method="post" novalidate>
            <div class="form-group">
                <label>Логин *</label>
                <input type="text" name="login" value="<?= e($_POST['login'] ?? '') ?>" required minlength="6">
                <?php if (!empty($errors['login'])): ?><div class="form-error"><?= e($errors['login']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>Пароль * (мин. 8 символов)</label>
                <input type="password" name="password" required minlength="8">
                <?php if (!empty($errors['password'])): ?><div class="form-error"><?= e($errors['password']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>Повтор пароля *</label>
                <input type="password" name="password2" required>
                <?php if (!empty($errors['password2'])): ?><div class="form-error"><?= e($errors['password2']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>ФИО *</label>
                <input type="text" name="fio" value="<?= e($_POST['fio'] ?? '') ?>" required>
                <?php if (!empty($errors['fio'])): ?><div class="form-error"><?= e($errors['fio']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>E-mail *</label>
                <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>
                <?php if (!empty($errors['email'])): ?><div class="form-error"><?= e($errors['email']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>Телефон</label>
                <input type="tel" name="phone" value="<?= e($_POST['phone'] ?? '') ?>" placeholder="+7 (...)">
            </div>
            <div class="form-group">
                <label>Дата рождения</label>
                <input type="date" name="birth_date" value="<?= e($_POST['birth_date'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Зарегистрироваться</button>
        </form>
        <p class="form-hint">Уже есть аккаунт? <a href="login.php">Войти</a></p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
