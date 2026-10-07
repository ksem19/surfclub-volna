-- База данных клуба серфинга «Волна»
-- Импортировать в phpMyAdmin

CREATE DATABASE IF NOT EXISTS surfclub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE surfclub;

-- Пользователи
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fio VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    birth_date DATE DEFAULT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Форматы обучения
CREATE TABLE IF NOT EXISTS training_formats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    duration VARCHAR(50),
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    level ENUM('начинающий', 'средний', 'продвинутый', 'все уровни') DEFAULT 'начинающий',
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Заявки на обучение
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    format_id INT NOT NULL,
    preferred_date DATE DEFAULT NULL,
    payment_method ENUM('наличные', 'карта', 'перевод') DEFAULT 'карта',
    status ENUM('Новая', 'Идет обучение', 'Обучение завершено') DEFAULT 'Новая',
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (format_id) REFERENCES training_formats(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Отзывы
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    application_id INT DEFAULT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    text TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Админ: логин Admin26, пароль Demo20
INSERT INTO users (login, password, fio, email, phone, role) VALUES
('Admin26', '$2y$10$blgBRzuMBffs9XkpyJZYgOttJN8IMQnMk/SvdtZKhEWE8.BCHBeVa', 'Администратор Клуба', 'admin@volna-surf.ru', '+7 (4212) 00-00-00', 'admin');

-- Форматы обучения
INSERT INTO training_formats (title, description, duration, price, level) VALUES
('Пробное занятие', 'Знакомство с доской, основы баланса и безопасности на воде. Подходит для тех, кто никогда не стоял на доске.', '1,5–2 часа', 3500.00, 'начинающий'),
('Курс для начинающих (4 занятия)', 'Полный базовый курс: техника гребли, выход на волну, стойка, падения и подъём. Подготовка к первой самостоятельной волне.', '4 занятия по 2 часа', 12000.00, 'начинающий'),
('Индивидуальная тренировка', 'Персональные занятия с инструктором под ваши цели и уровень. Максимальный прогресс.', '2 часа', 5500.00, 'все уровни'),
('Подготовка к океану', 'Интенсив перед выездом на Камчатку, Сахалин или Бали. Отработка навыков в условиях, близких к океанским.', '6 занятий', 18000.00, 'средний'),
('Групповой серф-кемп (выходные)', 'Двухдневный кемп: тренировки, теория, общение с единомышленниками. Питание и прокат включены.', '2 дня', 15000.00, 'начинающий'),
('Продвинутый уровень', 'Работа с манёврами, выбор линии, работа с разными типами волн. Для тех, кто уже уверенно катается.', 'по запросу', 6000.00, 'продвинутый');

-- Тестовый пользователь: логин surfer1, пароль user123
INSERT INTO users (login, password, fio, email, phone, role) VALUES
('surfer1', '$2y$10$cN0s69PVNeRREneHp1Z3Ougjx1MQ.RhFiwwSC/Z8e5MjQqsBJICHi', 'Иванов Алексей Петрович', 'alex@mail.ru', '+7 (914) 123-45-67', 'user');
