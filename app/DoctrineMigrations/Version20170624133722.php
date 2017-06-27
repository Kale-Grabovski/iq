<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170624133722 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE rate (id INT NOT NULL, currency_id INT NOT NULL, created_at DATE NOT NULL, value NUMERIC(8, 4) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE currency (id INT NOT NULL, code VARCHAR(3) NOT NULL, name text NOT NULL, nominal INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE SEQUENCE rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE UNIQUE INDEX uniq_currency_code ON currency (code)');
        $this->addSql('CREATE UNIQUE INDEX uniq_rates_currency_id_created_at ON rate (currency_id, created_at)');
        $this->addSql('ALTER TABLE rate ADD FOREIGN KEY (currency_id) REFERENCES currency(id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE currency');
    }
}
