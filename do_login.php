<?php

require_once __DIR__.'/boot.php';

// проверяем наличие пользователя с указанным логином
$stmt = pdo()->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $_POST['username']]);

if (!$stmt->rowCount()) {
    flash('Такого пользователя нет в системе!');
    header('Location: login.php');
    die;
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// проверяем пароль
if (password_verify($_POST['password'], $user['password'])) {
    // Проверяем, не нужно ли использовать более новый алгоритм
    // или другую алгоритмическую стоимость
    // Например, если вы поменяете опции хеширования
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = pdo()->prepare('UPDATE users SET password = :password WHERE `username` = :username');
        $stmt->execute([
            'username' => $_POST['username'],
            'password' => $newHash,
        ]);
    }
    $_SESSION['user_id'] = $user['id'];
    header('Location: /');
    die;
}

flash('Пароль неверен');
header('Location: login.php');