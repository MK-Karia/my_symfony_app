<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531220039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates User table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
        CREATE TABLE user
        (
            `user_id`     INT          NOT NULL AUTO_INCREMENT,
            `first_name`  VARCHAR(255) NOT NULL,
            `last_name`   VARCHAR(255) NOT NULL,
            `middle_name` VARCHAR(255) DEFAULT NULL,
            `gender`      VARCHAR(255) NOT NULL,
            `birth_date`  DATETIME     NOT NULL,
            `email`       VARCHAR(255) NOT NULL,
            `phone`       VARCHAR(255) DEFAULT NULL,
            `avatar_path` VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY (`user_id`),
            UNIQUE INDEX `email_idx` (`email`),
            UNIQUE INDEX `phone_idx` (`phone`)
        )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE user");
    }
}
