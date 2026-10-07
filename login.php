<?php
require_once 'config.php';
if (isLoggedIn()) redirect('cabinet.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = 'Введите логин и пароль';
    } else {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['fio'] = $user['fio'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] === 'admin') {
                redirect('admin.php');
            }
            redirect('cabinet.php');
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}

$pageTitle = 'Вход';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="form-card">
        <h2>Вход в аккаунт</h2>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" value="<?= e($_POST['login'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Войти</button>
        </form>
        <p class="form-hint">Ещё не зарегистрированы? <a href="register.php">Регистрация</a></p>
        <p class="form-hint" style="font-size:0.85rem;margin-top:8px">Демо: Admin26 / Demo20 или surfer1 / user123</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
