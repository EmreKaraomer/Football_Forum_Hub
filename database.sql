CREATE DATABASE IF NOT EXISTS football_forum
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE football_forum;

CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    username    VARCHAR(50)     NOT NULL UNIQUE,
    email       VARCHAR(150)    NOT NULL UNIQUE,
    password    VARCHAR(255)    NOT NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS posts (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED    NOT NULL,
    title       VARCHAR(255)    NOT NULL,
    content     TEXT            NOT NULL,
    category    VARCHAR(100)    NOT NULL DEFAULT 'Genel',
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_posts_users
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (username, email, password) VALUES
('futbol_sever', 'futbol@ornek.com', '$2y$12$UXqRJOoI6e/fYV0v7MJxwekJsPb5tBZVVRz2AKmAh3/s.MMJK1/BO'),
('golkral',      'golkral@ornek.com', '$2y$12$UXqRJOoI6e/fYV0v7MJxwekJsPb5tBZVVRz2AKmAh3/s.MMJK1/BO');

INSERT INTO posts (user_id, title, content, category) VALUES
(1, 'Bu sezonun en iyi transferi hangisi?', 'Bence bu sezon yapilan transferler arasinda en etkileyici olan... sizin gorusleriniz neler?', 'Transfer'),
(2, 'Sampiyonlar Ligi finale kimin gidecegini dusunuyorsunuz?', 'Bu sezon Sampiyonlar Ligi cok çekismeliydi. Finale hangi takimlar cikacak?', 'Sampiyonlar Ligi'),
(1, 'Milli Takim icin beklentileriniz', 'Yaklasan turnuvada milli takimdan ne bekliyorsunuz? Kadro secimlerini dogru buluyor musunuz?', 'Milli Takim');
