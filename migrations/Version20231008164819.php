<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008164819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD news_article_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9EEB51E5 FOREIGN KEY (news_article_id) REFERENCES news_article (id)');
        $this->addSql('CREATE INDEX IDX_9474526C9EEB51E5 ON comment (news_article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9EEB51E5');
        $this->addSql('DROP INDEX IDX_9474526C9EEB51E5 ON comment');
        $this->addSql('ALTER TABLE comment DROP news_article_id');
    }
}
