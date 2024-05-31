<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531082620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, delivery_id INT NOT NULL, marque_id INT NOT NULL, model_id INT NOT NULL, gender_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, authenticity LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', enable TINYINT(1) NOT NULL, INDEX IDX_D34A04AD12136921 (delivery_id), INDEX IDX_D34A04AD4827B9B2 (marque_id), INDEX IDX_D34A04AD7975B7E7 (model_id), INDEX IDX_D34A04AD708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12136921');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD4827B9B2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD7975B7E7');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD708A0E0');
        $this->addSql('DROP TABLE product');
    }
}
