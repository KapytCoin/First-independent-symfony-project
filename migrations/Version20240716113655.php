<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716113655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roles DROP uuid');
        $this->addSql('ALTER TABLE users DROP uuid');
        $this->addSql('ALTER TABLE video_game_articles DROP uuid');
        $this->addSql('ALTER TABLE video_game_articles DROP average_rating');
        $this->addSql('ALTER TABLE video_game_articles DROP number_of_reviews');
        $this->addSql('ALTER TABLE video_game_articles DROP img_path');
        $this->addSql('ALTER TABLE video_game_reviews DROP uuid');
        $this->addSql('ALTER TABLE video_game_reviews RENAME COLUMN reviews TO grade');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE roles ADD uuid UUID NOT NULL');
        $this->addSql('ALTER TABLE video_game_articles ADD uuid UUID NOT NULL');
        $this->addSql('ALTER TABLE video_game_articles ADD average_rating INT NOT NULL');
        $this->addSql('ALTER TABLE video_game_articles ADD number_of_reviews INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video_game_articles ADD img_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE video_game_reviews ADD uuid UUID NOT NULL');
        $this->addSql('ALTER TABLE video_game_reviews RENAME COLUMN grade TO reviews');
        $this->addSql('ALTER TABLE users ADD uuid UUID NOT NULL');
    }
}
