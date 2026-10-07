<?php
require_once 'config.php';
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'apply.php';
    redirect('login.php');
}

$pdo = getDB();
$formats = $pdo->query("SELECT * FROM training_formats WHERE is_active = 1 ORDER BY title")->fetchAll();
$preselect = (int)($_GET['format_id'] ?? 0);
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $format_id = (int)($_POST['format_id'] ?? 0);
    $preferred_date = $_POST['preferred_date'] ?? null;
    $payment = $_POST['payment_method'] ?? 'карта';
    $comment = trim($_POST['comment'] ?? '');

    if (!$format_id) $errors[] = 'Выберите формат обучения';
    if ($preferred_date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $preferred_date)) {
        $errors[] = 'Некорректная дата';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO applications (user_id, format_id, preferred_date, payment_method, comment) VALUES (?,?,?,?,?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $format_id,
            $preferred_date ?: null,
            $payment,
            $comment ?: null
        ]);
        $success = true;
    }
}

$pageTitle = 'Запись на обучение';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="form-card" style="max-width:520px">
        <h2>Заявка на обучение</h2>
        <?php if ($success): ?>
            <div class="form-success">Заявка отправлена! Статус можно отслеживать в <a href="cabinet.php">личном кабинете</a>.</div>
        <?php else: ?>
            <?php if ($errors): ?>
                <div class="alert alert-error"><?= e(implode('. ', $errors)) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label>Формат обучения *</label>
                    <select name="format_id" required>
                        <option value="">— выберите —</option>
                        <?php foreach ($formats as $f): ?>
                            <option value="<?= (int)$f['id'] ?>" <?= ($preselect === (int)$f['id'] || (isset($_POST['format_id']) && (int)$_POST['format_id'] === (int)$f['id'])) ? 'selected' : '' ?>>
                                <?= e($f['title']) ?> (<?= number_format($f['price'], 0, '', ' ') ?> ₽)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Желаемая дата начала</label>
                    <input type="date" name="preferred_date" value="<?= e($_POST['preferred_date'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Способ оплаты</label>
                    <select name="payment_method">
                        <option value="карта">Банковская карта</option>
                        <option value="наличные">Наличные</option>
                        <option value="перевод">Перевод</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Комментарий</label>
                    <textarea name="comment" placeholder="Пожелания по времени, уровню..."><?= e($_POST['comment'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">Отправить заявку</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
