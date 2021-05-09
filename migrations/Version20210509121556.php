<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509121556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, title, ordered, sended FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, family_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, ordered BOOLEAN NOT NULL, sended BOOLEAN NOT NULL, CONSTRAINT FK_BA388B7C35E566A FOREIGN KEY (family_id) REFERENCES family (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart (id, title, ordered, sended) SELECT id, title, ordered, sended FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE INDEX IDX_BA388B7C35E566A ON cart (family_id)');
        $this->addSql('DROP INDEX IDX_64C19C112469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, category_id, title FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_64C19C112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO category (id, category_id, title) SELECT id, category_id, title FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C112469DE2 ON category (category_id)');
        $this->addSql('DROP INDEX IDX_6A41D10A12469DE2');
        $this->addSql('DROP INDEX IDX_6A41D10A126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item_category AS SELECT item_id, category_id FROM item_category');
        $this->addSql('DROP TABLE item_category');
        $this->addSql('CREATE TABLE item_category (item_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(item_id, category_id), CONSTRAINT FK_6A41D10A126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6A41D10A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO item_category (item_id, category_id) SELECT item_id, category_id FROM __temp__item_category');
        $this->addSql('DROP TABLE __temp__item_category');
        $this->addSql('CREATE INDEX IDX_6A41D10A12469DE2 ON item_category (category_id)');
        $this->addSql('CREATE INDEX IDX_6A41D10A126F525E ON item_category (item_id)');
        $this->addSql('DROP INDEX IDX_8130A368C35E566A');
        $this->addSql('DROP INDEX IDX_8130A3687597D3FE');
        $this->addSql('CREATE TEMPORARY TABLE __temp__member_family AS SELECT member_id, family_id FROM member_family');
        $this->addSql('DROP TABLE member_family');
        $this->addSql('CREATE TABLE member_family (member_id INTEGER NOT NULL, family_id INTEGER NOT NULL, PRIMARY KEY(member_id, family_id), CONSTRAINT FK_8130A3687597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8130A368C35E566A FOREIGN KEY (family_id) REFERENCES family (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO member_family (member_id, family_id) SELECT member_id, family_id FROM __temp__member_family');
        $this->addSql('DROP TABLE __temp__member_family');
        $this->addSql('CREATE INDEX IDX_8130A368C35E566A ON member_family (family_id)');
        $this->addSql('CREATE INDEX IDX_8130A3687597D3FE ON member_family (member_id)');
        $this->addSql('DROP INDEX IDX_D7D174C9126F525E');
        $this->addSql('DROP INDEX IDX_D7D174C91AD5CDBF');
        $this->addSql('DROP INDEX IDX_D7D174C97597D3FE');
        $this->addSql('CREATE TEMPORARY TABLE __temp__wish AS SELECT id, member_id, cart_id, item_id, count FROM wish');
        $this->addSql('DROP TABLE wish');
        $this->addSql('CREATE TABLE wish (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, member_id INTEGER DEFAULT NULL, cart_id INTEGER DEFAULT NULL, item_id INTEGER DEFAULT NULL, count INTEGER NOT NULL, CONSTRAINT FK_D7D174C97597D3FE FOREIGN KEY (member_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D7D174C91AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D7D174C9126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO wish (id, member_id, cart_id, item_id, count) SELECT id, member_id, cart_id, item_id, count FROM __temp__wish');
        $this->addSql('DROP TABLE __temp__wish');
        $this->addSql('CREATE INDEX IDX_D7D174C9126F525E ON wish (item_id)');
        $this->addSql('CREATE INDEX IDX_D7D174C91AD5CDBF ON wish (cart_id)');
        $this->addSql('CREATE INDEX IDX_D7D174C97597D3FE ON wish (member_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_BA388B7C35E566A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, title, ordered, sended FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, ordered BOOLEAN NOT NULL, sended BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO cart (id, title, ordered, sended) SELECT id, title, ordered, sended FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('DROP INDEX IDX_64C19C112469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, category_id, title FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO category (id, category_id, title) SELECT id, category_id, title FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C112469DE2 ON category (category_id)');
        $this->addSql('DROP INDEX IDX_6A41D10A126F525E');
        $this->addSql('DROP INDEX IDX_6A41D10A12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item_category AS SELECT item_id, category_id FROM item_category');
        $this->addSql('DROP TABLE item_category');
        $this->addSql('CREATE TABLE item_category (item_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(item_id, category_id))');
        $this->addSql('INSERT INTO item_category (item_id, category_id) SELECT item_id, category_id FROM __temp__item_category');
        $this->addSql('DROP TABLE __temp__item_category');
        $this->addSql('CREATE INDEX IDX_6A41D10A126F525E ON item_category (item_id)');
        $this->addSql('CREATE INDEX IDX_6A41D10A12469DE2 ON item_category (category_id)');
        $this->addSql('DROP INDEX IDX_8130A3687597D3FE');
        $this->addSql('DROP INDEX IDX_8130A368C35E566A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__member_family AS SELECT member_id, family_id FROM member_family');
        $this->addSql('DROP TABLE member_family');
        $this->addSql('CREATE TABLE member_family (member_id INTEGER NOT NULL, family_id INTEGER NOT NULL, PRIMARY KEY(member_id, family_id))');
        $this->addSql('INSERT INTO member_family (member_id, family_id) SELECT member_id, family_id FROM __temp__member_family');
        $this->addSql('DROP TABLE __temp__member_family');
        $this->addSql('CREATE INDEX IDX_8130A3687597D3FE ON member_family (member_id)');
        $this->addSql('CREATE INDEX IDX_8130A368C35E566A ON member_family (family_id)');
        $this->addSql('DROP INDEX IDX_D7D174C97597D3FE');
        $this->addSql('DROP INDEX IDX_D7D174C91AD5CDBF');
        $this->addSql('DROP INDEX IDX_D7D174C9126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__wish AS SELECT id, member_id, cart_id, item_id, count FROM wish');
        $this->addSql('DROP TABLE wish');
        $this->addSql('CREATE TABLE wish (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, member_id INTEGER DEFAULT NULL, cart_id INTEGER DEFAULT NULL, item_id INTEGER DEFAULT NULL, count INTEGER NOT NULL)');
        $this->addSql('INSERT INTO wish (id, member_id, cart_id, item_id, count) SELECT id, member_id, cart_id, item_id, count FROM __temp__wish');
        $this->addSql('DROP TABLE __temp__wish');
        $this->addSql('CREATE INDEX IDX_D7D174C97597D3FE ON wish (member_id)');
        $this->addSql('CREATE INDEX IDX_D7D174C91AD5CDBF ON wish (cart_id)');
        $this->addSql('CREATE INDEX IDX_D7D174C9126F525E ON wish (item_id)');
    }
}
