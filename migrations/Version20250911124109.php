<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250911124109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change idclient_id to NOT NULL in location table safely';
    }

    public function up(Schema $schema): void
    {
        // Supprimer la contrainte existante
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB67F0C0D4');

        // Modifier la colonne
        $this->addSql('ALTER TABLE location CHANGE idclient_id idclient_id INT NOT NULL');

        // Réajouter la contrainte
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB67F0C0D4 FOREIGN KEY (idclient_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la contrainte
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB67F0C0D4');

        // Revenir à l’état précédent de la colonne
        $this->addSql('ALTER TABLE location CHANGE idclient_id idclient_id INT DEFAULT NULL');

        // Réajouter la contrainte
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB67F0C0D4 FOREIGN KEY (idclient_id) REFERENCES client (id)');
    }
}
