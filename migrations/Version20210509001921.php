<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509001921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, ordered BOOLEAN NOT NULL, sended BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_64C19C112469DE2 ON category (category_id)');
        $this->addSql('CREATE TABLE family (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, stock INTEGER NOT NULL, unit_price DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE TABLE item_category (item_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(item_id, category_id))');
        $this->addSql('CREATE INDEX IDX_6A41D10A126F525E ON item_category (item_id)');
        $this->addSql('CREATE INDEX IDX_6A41D10A12469DE2 ON item_category (category_id)');
        $this->addSql('CREATE TABLE member (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE member_family (member_id INTEGER NOT NULL, family_id INTEGER NOT NULL, PRIMARY KEY(member_id, family_id))');
        $this->addSql('CREATE INDEX IDX_8130A3687597D3FE ON member_family (member_id)');
        $this->addSql('CREATE INDEX IDX_8130A368C35E566A ON member_family (family_id)');
        $this->addSql('CREATE TABLE wish (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, member_id INTEGER DEFAULT NULL, cart_id INTEGER DEFAULT NULL, item_id INTEGER DEFAULT NULL, count INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_D7D174C97597D3FE ON wish (member_id)');
        $this->addSql('CREATE INDEX IDX_D7D174C91AD5CDBF ON wish (cart_id)');
        $this->addSql('CREATE INDEX IDX_D7D174C9126F525E ON wish (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_category');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE member_family');
        $this->addSql('DROP TABLE wish');
    }
}
