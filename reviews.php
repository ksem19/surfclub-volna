<?php
require_once 'config.php';
$pdo = getDB();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $rating = (int)($_POST['rating'] ?? 0);
    $text = trim($_POST['text'] ?? '');

    if ($rating < 1 || $rating > 5) $errors[] = 'Поставьте оценку от 1 до 5';
    if (strlen($text) < 10) $errors[] = 'Текст отзыва не менее 10 символов';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, rating, text) VALUES (?,?,?)");
        $stmt->execute([$_SESSION['user_id'], $rating, $text]);
        $success = true;
    }
}

$reviews = $pdo->query("
    SELECT r.*, u.fio 
    FROM reviews r 
    JOIN users u ON u.id = r.user_id 
    WHERE r.is_approved = 1 
    ORDER BY r.created_at DESC
")->fetchAll();

$pageTitle = 'Отзывы';
require_once 'includes/header.php';
?>

<div class="container">
    <h1 class="section-title">Отзывы учеников</h1>
    <p class="section-sub">Реальные впечатления о занятиях и клубе</p>

    <?php if (isLoggedIn()): ?>
    <div class="form-card" style="margin-bottom: 40px;">
        <h2>Оставить отзыв</h2>
        <?php if ($success): ?>
            <div class="form-success">Спасибо! Ваш отзыв опубликован.</div>
        <?php else: ?>
            <?php if ($errors): ?>
                <div class="alert alert-error"><?= e(implode('. ', $errors)) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label>Оценка</label>
                    <div class="stars-input">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <input type="hidden" name="rating" value="0">
                </div>
                <div class="form-group">
                    <label>Текст отзыва</label>
                    <textarea name="text" required minlength="10" placeholder="Расскажите о занятии..."><?= e($_POST['text'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        <?php endif; ?>
    </div>
    <?php else: ?>
        <p style="text-align:center;margin-bottom:30px">
            <a href="login.php">Войдите</a>, чтобы оставить отзыв.
        </p>
    <?php endif; ?>

    <div class="review-list">
        <?php if (empty($reviews)): ?>
            <p style="text-align:center;color:var(--text-muted)">Пока нет отзывов. Будьте первым!</p>
        <?php else: ?>
            <?php foreach ($reviews as $r): ?>
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author"><?= e($r['fio']) ?></span>
                    <span class="review-stars"><?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']) ?></span>
                    <span class="review-date"><?= date('d.m.Y', strtotime($r['created_at'])) ?></span>
                </div>
                <div class="review-text"><?= nl2br(e($r['text'])) ?></div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
