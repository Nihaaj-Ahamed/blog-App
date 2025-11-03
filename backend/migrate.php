<?php
// Simple migration helper for local development.
// Run this once by visiting http://localhost/Assignment/backend/migrate.php
// It will create `users` table (if missing) and add `user_id` to `posts` (if missing).
require_once __DIR__ . '/dp.php';
header('Content-Type: text/html; charset=utf-8');
echo "<h2>Migration</h2>";
try {
    // Create users table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
      `id` INT NOT NULL AUTO_INCREMENT,
      `username` VARCHAR(100) NOT NULL UNIQUE,
      `password_hash` VARCHAR(255) NOT NULL,
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "<p>Ensured `users` table exists</p>";

    // Check if posts.user_id exists
    $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'posts' AND COLUMN_NAME = 'user_id'");
    $stmt->execute();
    $has = $stmt->fetchColumn();
    if (!$has) {
        // Add column
        $pdo->exec("ALTER TABLE posts ADD COLUMN user_id INT NULL AFTER content;");
        echo "<p>Added column `posts.user_id`</p>";
        // add index
        $pdo->exec("ALTER TABLE posts ADD INDEX (user_id);");
        echo "<p>Added index on `posts.user_id`</p>";
        // Attempt to add FK if possible
        try {
            $pdo->exec("ALTER TABLE posts ADD CONSTRAINT posts_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;");
            echo "<p>Added foreign key posts.user_fk -> users(id)</p>";
        } catch (Exception $e) {
            echo "<p>Could not add foreign key (ok on some setups): " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p>`posts.user_id` already exists</p>";
    }

    // Optional: ensure sample posts exist
    $count = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO posts (title, content) VALUES ('Welcome','This is your first post.'), ('Second','Second post sample')");
        echo "<p>Inserted sample posts</p>";
    } else {
        echo "<p>Posts already present: {$count}</p>";
    }

        // Ensure `user` and `blogPost` (requested schema) exist
        $pdo->exec("CREATE TABLE IF NOT EXISTS `user` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(100) NOT NULL UNIQUE,
            `email` VARCHAR(255) DEFAULT NULL,
            `password` VARCHAR(255) NOT NULL,
            `role` VARCHAR(50) DEFAULT 'user',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "<p>Ensured `user` table exists</p>";

        $pdo->exec("CREATE TABLE IF NOT EXISTS `blogPost` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `user_id` INT NULL,
            `title` VARCHAR(255) NOT NULL,
            `content` TEXT NOT NULL,
            `image` LONGTEXT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id_idx` (`user_id`),
            CONSTRAINT `blogpost_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        echo "<p>Ensured `blogPost` table exists</p>";
        
        // Add image column if missing
        $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'blogPost' AND COLUMN_NAME = 'image'");
        $stmt->execute();
        $hasImage = $stmt->fetchColumn();
        if (!$hasImage) {
            $pdo->exec("ALTER TABLE blogPost ADD COLUMN image LONGTEXT NULL AFTER content;");
            echo "<p>Added column `blogPost.image`</p>";
        } else {
            echo "<p>`blogPost.image` already exists</p>";
        }
        // If `user` table is empty but `users` has data, migrate users
        $uCount = $pdo->query("SELECT COUNT(*) FROM `user`")->fetchColumn();
        $oldUCount = 0;
        try { $oldUCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); } catch (Exception $e) { $oldUCount = 0; }
        if ($uCount == 0 && $oldUCount > 0) {
            // Copy users -> user (map password_hash -> password)
            $pdo->exec("INSERT INTO `user` (id, username, email, password, role, created_at)
                SELECT id, username, NULL, password_hash, 'user', created_at FROM users");
            echo "<p>Migrated {$oldUCount} rows from `users` to `user`</p>";
        } else {
            echo "<p>`user` rows: {$uCount} (old users: {$oldUCount})</p>";
        }

        // Migrate posts -> blogPost if needed
        $bCount = $pdo->query("SELECT COUNT(*) FROM blogPost")->fetchColumn();
        $oldPCount = 0;
        try { $oldPCount = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn(); } catch (Exception $e) { $oldPCount = 0; }
        if ($bCount == 0 && $oldPCount > 0) {
            $pdo->exec("INSERT INTO blogPost (id, user_id, title, content, created_at, updated_at)
                SELECT id, user_id, title, content, created_at, created_at FROM posts");
            echo "<p>Migrated {$oldPCount} rows from `posts` to `blogPost`</p>";
        } else {
            echo "<p>`blogPost` rows: {$bCount} (old posts: {$oldPCount})</p>";
        }

        echo "<p>Migration completed.</p>";
} catch (PDOException $e) {
    echo "<pre>Migration error: " . htmlspecialchars($e->getMessage()) . "</pre>";
}

echo '<p><a href="/Assignment/frontend/index.html">Open frontend</a></p>';

?>
