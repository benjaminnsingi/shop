<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210613141916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE size ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE size ADD CONSTRAINT FK_F7C0246A4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_F7C0246A4584665A ON size (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE size DROP FOREIGN KEY FK_F7C0246A4584665A');
        $this->addSql('DROP INDEX IDX_F7C0246A4584665A ON size');
        $this->addSql('ALTER TABLE size DROP product_id');
    }
}
