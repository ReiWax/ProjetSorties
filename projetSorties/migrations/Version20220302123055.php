<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302123055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_3BAE0AA75D83CC1');
        $this->addSql('DROP INDEX IDX_3BAE0AA764D218E');
        $this->addSql('DROP INDEX IDX_3BAE0AA78486F9AC');
        $this->addSql('DROP INDEX IDX_3BAE0AA7876C4DDA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, organizer_id, adress_id, location_id, state_id, name, date_time_start_at, duration, date_limit_registration_at, nb_max_registration, description FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, organizer_id INTEGER NOT NULL, adress_id INTEGER NOT NULL, location_id INTEGER NOT NULL, state_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, date_time_start_at DATETIME NOT NULL, duration INTEGER DEFAULT NULL, date_limit_registration_at DATETIME NOT NULL, nb_max_registration INTEGER NOT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_3BAE0AA7876C4DDA FOREIGN KEY (organizer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3BAE0AA78486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3BAE0AA764D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3BAE0AA75D83CC1 FOREIGN KEY (state_id) REFERENCES state (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, organizer_id, adress_id, location_id, state_id, name, date_time_start_at, duration, date_limit_registration_at, nb_max_registration, description) SELECT id, organizer_id, adress_id, location_id, state_id, name, date_time_start_at, duration, date_limit_registration_at, nb_max_registration, description FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA75D83CC1 ON event (state_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA764D218E ON event (location_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA78486F9AC ON event (adress_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7876C4DDA ON event (organizer_id)');
        $this->addSql('DROP INDEX IDX_5E9E89CB8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__location AS SELECT id, city_id, name, street, lat, long FROM location');
        $this->addSql('DROP TABLE location');
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, lat DOUBLE PRECISION DEFAULT NULL, long DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_5E9E89CB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO location (id, city_id, name, street, lat, long) SELECT id, city_id, name, street, lat, long FROM __temp__location');
        $this->addSql('DROP TABLE __temp__location');
        $this->addSql('CREATE INDEX IDX_5E9E89CB8BAC62AF ON location (city_id)');
        $this->addSql('DROP INDEX IDX_8D93D6498486F9AC');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, adress_id, email, roles, password, pseudo, name, lastname, tel, admin, active, illustration FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, adress_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, tel INTEGER DEFAULT NULL, admin BOOLEAN NOT NULL, active BOOLEAN NOT NULL, illustration VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_8D93D6498486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, adress_id, email, roles, password, pseudo, name, lastname, tel, admin, active, illustration) SELECT id, adress_id, email, roles, password, pseudo, name, lastname, tel, admin, active, illustration FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE INDEX IDX_8D93D6498486F9AC ON user (adress_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP INDEX IDX_D96CF1FF71F7E88B');
        $this->addSql('DROP INDEX IDX_D96CF1FFA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_event AS SELECT user_id, event_id FROM user_event');
        $this->addSql('DROP TABLE user_event');
        $this->addSql('CREATE TABLE user_event (user_id INTEGER NOT NULL, event_id INTEGER NOT NULL, PRIMARY KEY(user_id, event_id), CONSTRAINT FK_D96CF1FFA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D96CF1FF71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_event (user_id, event_id) SELECT user_id, event_id FROM __temp__user_event');
        $this->addSql('DROP TABLE __temp__user_event');
        $this->addSql('CREATE INDEX IDX_D96CF1FF71F7E88B ON user_event (event_id)');
        $this->addSql('CREATE INDEX IDX_D96CF1FFA76ED395 ON user_event (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_3BAE0AA7876C4DDA');
        $this->addSql('DROP INDEX IDX_3BAE0AA78486F9AC');
        $this->addSql('DROP INDEX IDX_3BAE0AA764D218E');
        $this->addSql('DROP INDEX IDX_3BAE0AA75D83CC1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, organizer_id, adress_id, location_id, state_id, name, date_time_start_at, duration, date_limit_registration_at, nb_max_registration, description FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, organizer_id INTEGER NOT NULL, adress_id INTEGER NOT NULL, location_id INTEGER NOT NULL, state_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, date_time_start_at DATETIME NOT NULL, duration INTEGER DEFAULT NULL, date_limit_registration_at DATETIME NOT NULL, nb_max_registration INTEGER NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO event (id, organizer_id, adress_id, location_id, state_id, name, date_time_start_at, duration, date_limit_registration_at, nb_max_registration, description) SELECT id, organizer_id, adress_id, location_id, state_id, name, date_time_start_at, duration, date_limit_registration_at, nb_max_registration, description FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7876C4DDA ON event (organizer_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA78486F9AC ON event (adress_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA764D218E ON event (location_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA75D83CC1 ON event (state_id)');
        $this->addSql('DROP INDEX IDX_5E9E89CB8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__location AS SELECT id, city_id, name, street, lat, long FROM location');
        $this->addSql('DROP TABLE location');
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, lat DOUBLE PRECISION DEFAULT NULL, long DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO location (id, city_id, name, street, lat, long) SELECT id, city_id, name, street, lat, long FROM __temp__location');
        $this->addSql('DROP TABLE __temp__location');
        $this->addSql('CREATE INDEX IDX_5E9E89CB8BAC62AF ON location (city_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX IDX_8D93D6498486F9AC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, adress_id, email, roles, password, pseudo, name, lastname, tel, admin, active, illustration FROM "user"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, adress_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, tel INTEGER DEFAULT NULL, admin BOOLEAN NOT NULL, active BOOLEAN NOT NULL, illustration VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO "user" (id, adress_id, email, roles, password, pseudo, name, lastname, tel, admin, active, illustration) SELECT id, adress_id, email, roles, password, pseudo, name, lastname, tel, admin, active, illustration FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D6498486F9AC ON "user" (adress_id)');
        $this->addSql('DROP INDEX IDX_D96CF1FFA76ED395');
        $this->addSql('DROP INDEX IDX_D96CF1FF71F7E88B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_event AS SELECT user_id, event_id FROM user_event');
        $this->addSql('DROP TABLE user_event');
        $this->addSql('CREATE TABLE user_event (user_id INTEGER NOT NULL, event_id INTEGER NOT NULL, PRIMARY KEY(user_id, event_id))');
        $this->addSql('INSERT INTO user_event (user_id, event_id) SELECT user_id, event_id FROM __temp__user_event');
        $this->addSql('DROP TABLE __temp__user_event');
        $this->addSql('CREATE INDEX IDX_D96CF1FFA76ED395 ON user_event (user_id)');
        $this->addSql('CREATE INDEX IDX_D96CF1FF71F7E88B ON user_event (event_id)');
    }
}
