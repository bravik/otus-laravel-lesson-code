<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE `posts` (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                text TEXT NOT NULL,
                author_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB;
        SQL
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE `posts`');
    }
};
