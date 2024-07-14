<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240714123610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE video_game_articles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE video_game_reviews_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE roles (id INT NOT NULL, uuid UUID NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, roles_id INT NOT NULL, uuid UUID NOT NULL, nickname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, online BOOLEAN NOT NULL, access VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1483A5E938C751C4 ON users (roles_id)');
        $this->addSql('CREATE TABLE video_game_articles (id INT NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, text VARCHAR(1000) NOT NULL, average_rating INT NOT NULL, number_of_reviews INT DEFAULT NULL, img_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE video_game_reviews (id INT NOT NULL, users_id INT NOT NULL, video_game_articles_id INT NOT NULL, uuid UUID NOT NULL, text VARCHAR(1000) NOT NULL, reviews INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5D4185EF67B3B43D ON video_game_reviews (users_id)');
        $this->addSql('CREATE INDEX IDX_5D4185EF6D65A2D9 ON video_game_reviews (video_game_articles_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E938C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_game_reviews ADD CONSTRAINT FK_5D4185EF67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_game_reviews ADD CONSTRAINT FK_5D4185EF6D65A2D9 FOREIGN KEY (video_game_articles_id) REFERENCES video_game_articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE roles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE video_game_articles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE video_game_reviews_id_seq CASCADE');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E938C751C4');
        $this->addSql('ALTER TABLE video_game_reviews DROP CONSTRAINT FK_5D4185EF67B3B43D');
        $this->addSql('ALTER TABLE video_game_reviews DROP CONSTRAINT FK_5D4185EF6D65A2D9');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE video_game_articles');
        $this->addSql('DROP TABLE video_game_reviews');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
