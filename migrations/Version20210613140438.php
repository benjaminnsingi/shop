<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210613140438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_size');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_size (product_id INT NOT NULL, size_id INT NOT NULL, INDEX IDX_7A2806CB4584665A (product_id), INDEX IDX_7A2806CB498DA827 (size_id), PRIMARY KEY(product_id, size_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_size ADD CONSTRAINT FK_7A2806CB4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_size ADD CONSTRAINT FK_7A2806CB498DA827 FOREIGN KEY (size_id) REFERENCES size (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
