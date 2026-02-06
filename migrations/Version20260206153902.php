<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206153902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artifact (id INT AUTO_INCREMENT NOT NULL, artifact_name VARCHAR(255) NOT NULL, artifact_description LONGTEXT NOT NULL, artifact_type VARCHAR(255) NOT NULL, language VARCHAR(255) DEFAULT NULL, test_content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, track_id INT DEFAULT NULL, INDEX IDX_48E5602C5ED23C43 (track_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_obtention DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE candidat_certification (certification_id INT NOT NULL, candidat_id INT NOT NULL, INDEX IDX_8D903D8ECB47068A (certification_id), INDEX IDX_8D903D8E8D0EB82 (candidat_id), PRIMARY KEY (certification_id, candidat_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, date_creation DATETIME NOT NULL, dernier_message VARCHAR(255) DEFAULT NULL, statut VARCHAR(255) NOT NULL, user1_id INT DEFAULT NULL, user2_id INT DEFAULT NULL, INDEX IDX_8A8E26E956AE248B (user1_id), INDEX IDX_8A8E26E9441B8B65 (user2_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, duree INT NOT NULL, niveau VARCHAR(255) NOT NULL, competences_visees JSON NOT NULL, est_obligatoire TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cours_mission (cours_id INT NOT NULL, mission_id INT NOT NULL, INDEX IDX_D3619B477ECF78B0 (cours_id), INDEX IDX_D3619B47BE6CAE90 (mission_id), PRIMARY KEY (cours_id, mission_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE entretien (id INT AUTO_INCREMENT NOT NULL, date_entretien DATETIME NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, postulation_id INT DEFAULT NULL, INDEX IDX_2B58D6DAD749FDF1 (postulation_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, commentaire LONGTEXT NOT NULL, note INT NOT NULL, created_at DATETIME NOT NULL, rendu_id INT NOT NULL, UNIQUE INDEX UNIQ_D2294458C974D9ED (rendu_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE file_object (id INT AUTO_INCREMENT NOT NULL, storage_key VARCHAR(255) NOT NULL, public_url VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, file_size INT NOT NULL, uploaded_at DATETIME NOT NULL, artifact_id INT DEFAULT NULL, INDEX IDX_10BA8D53E28B07AC (artifact_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, date_envoi DATETIME NOT NULL, date_modification DATETIME DEFAULT NULL, statut VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, conversation_id INT DEFAULT NULL, expediteur_id INT DEFAULT NULL, destinataire_id INT DEFAULT NULL, INDEX IDX_B6BD307F9AC0396 (conversation_id), INDEX IDX_B6BD307F10335F61 (expediteur_id), INDEX IDX_B6BD307FA4F84F6E (destinataire_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, score_min INT NOT NULL, created_at DATETIME NOT NULL, created_by_id INT NOT NULL, INDEX IDX_9067F23CB03A8386 (created_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE offre_emploi (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, salaire DOUBLE PRECISION NOT NULL, type_contrat VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, date_expiration DATETIME NOT NULL, niveau_qualification VARCHAR(255) NOT NULL, experience_requise INT NOT NULL, competences_requises JSON NOT NULL, secteur_activite VARCHAR(255) NOT NULL, entreprise VARCHAR(255) NOT NULL, contact_recruteur VARCHAR(255) NOT NULL, recruteur_id INT DEFAULT NULL, INDEX IDX_132AD0D1BB0859F1 (recruteur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE postulation (id INT AUTO_INCREMENT NOT NULL, date_postulation DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, motivation_candidature LONGTEXT NOT NULL, candidat_id INT DEFAULT NULL, offre_id INT DEFAULT NULL, INDEX IDX_DA7D4E9B8D0EB82 (candidat_id), INDEX IDX_DA7D4E9B4CC8505A (offre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, objet VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, categorie VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, priorite VARCHAR(255) NOT NULL, utilisateur_id INT DEFAULT NULL, INDEX IDX_CE606404FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rendu_mission (id INT AUTO_INCREMENT NOT NULL, fichier VARCHAR(255) NOT NULL, date_rendu DATETIME NOT NULL, score DOUBLE PRECISION DEFAULT NULL, resultat VARCHAR(255) DEFAULT NULL, mission_id INT NOT NULL, candidat_id INT NOT NULL, INDEX IDX_84BAC7D3BE6CAE90 (mission_id), INDEX IDX_84BAC7D38D0EB82 (candidat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE snapshot (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, is_final TINYINT NOT NULL, created_at DATETIME NOT NULL, track_id INT DEFAULT NULL, author_id INT DEFAULT NULL, INDEX IDX_2C4D15355ED23C43 (track_id), INDEX IDX_2C4D1535F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE snapshot_item (snapshot_id INT NOT NULL, artifact_id INT NOT NULL, file_object_id INT DEFAULT NULL, INDEX IDX_D4BB0637B39395E (snapshot_id), INDEX IDX_D4BB063E28B07AC (artifact_id), INDEX IDX_D4BB063AD22E95D (file_object_id), PRIMARY KEY (snapshot_id, artifact_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE track (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, category VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, visibility VARCHAR(255) NOT NULL, workspace_id INT DEFAULT NULL, INDEX IDX_D6E3F8A682D40A1F (workspace_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE traitement_reclamation (id INT AUTO_INCREMENT NOT NULL, date_traitement DATETIME NOT NULL, reponse_admin LONGTEXT NOT NULL, statut_final VARCHAR(255) NOT NULL, reclamation_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, INDEX IDX_FE317EF32D6BA2D9 (reclamation_id), INDEX IDX_FE317EF3642B8210 (admin_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, is_active TINYINT NOT NULL, last_login_at DATE NOT NULL, created_at DATE NOT NULL, type VARCHAR(255) NOT NULL, headline VARCHAR(255) DEFAULT NULL, bio LONGTEXT DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, visibility VARCHAR(255) DEFAULT NULL, niveau VARCHAR(255) DEFAULT NULL, score_global DOUBLE PRECISION DEFAULT NULL, org_name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, logo_url VARCHAR(255) DEFAULT NULL, profile_pic VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE candidat_mission (candidat_id INT NOT NULL, mission_id INT NOT NULL, INDEX IDX_C96A04D68D0EB82 (candidat_id), INDEX IDX_C96A04D6BE6CAE90 (mission_id), PRIMARY KEY (candidat_id, mission_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE workspace (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, candidat_id INT NOT NULL, INDEX IDX_8D9400198D0EB82 (candidat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE artifact ADD CONSTRAINT FK_48E5602C5ED23C43 FOREIGN KEY (track_id) REFERENCES track (id)');
        $this->addSql('ALTER TABLE candidat_certification ADD CONSTRAINT FK_8D903D8ECB47068A FOREIGN KEY (certification_id) REFERENCES certification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_certification ADD CONSTRAINT FK_8D903D8E8D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E956AE248B FOREIGN KEY (user1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9441B8B65 FOREIGN KEY (user2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cours_mission ADD CONSTRAINT FK_D3619B477ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours_mission ADD CONSTRAINT FK_D3619B47BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DAD749FDF1 FOREIGN KEY (postulation_id) REFERENCES postulation (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458C974D9ED FOREIGN KEY (rendu_id) REFERENCES rendu_mission (id)');
        $this->addSql('ALTER TABLE file_object ADD CONSTRAINT FK_10BA8D53E28B07AC FOREIGN KEY (artifact_id) REFERENCES artifact (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F10335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1BB0859F1 FOREIGN KEY (recruteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE postulation ADD CONSTRAINT FK_DA7D4E9B8D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE postulation ADD CONSTRAINT FK_DA7D4E9B4CC8505A FOREIGN KEY (offre_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendu_mission ADD CONSTRAINT FK_84BAC7D3BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE rendu_mission ADD CONSTRAINT FK_84BAC7D38D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE snapshot ADD CONSTRAINT FK_2C4D15355ED23C43 FOREIGN KEY (track_id) REFERENCES track (id)');
        $this->addSql('ALTER TABLE snapshot ADD CONSTRAINT FK_2C4D1535F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE snapshot_item ADD CONSTRAINT FK_D4BB0637B39395E FOREIGN KEY (snapshot_id) REFERENCES snapshot (id)');
        $this->addSql('ALTER TABLE snapshot_item ADD CONSTRAINT FK_D4BB063E28B07AC FOREIGN KEY (artifact_id) REFERENCES artifact (id)');
        $this->addSql('ALTER TABLE snapshot_item ADD CONSTRAINT FK_D4BB063AD22E95D FOREIGN KEY (file_object_id) REFERENCES file_object (id)');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A682D40A1F FOREIGN KEY (workspace_id) REFERENCES workspace (id)');
        $this->addSql('ALTER TABLE traitement_reclamation ADD CONSTRAINT FK_FE317EF32D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE traitement_reclamation ADD CONSTRAINT FK_FE317EF3642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE candidat_mission ADD CONSTRAINT FK_C96A04D68D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_mission ADD CONSTRAINT FK_C96A04D6BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workspace ADD CONSTRAINT FK_8D9400198D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artifact DROP FOREIGN KEY FK_48E5602C5ED23C43');
        $this->addSql('ALTER TABLE candidat_certification DROP FOREIGN KEY FK_8D903D8ECB47068A');
        $this->addSql('ALTER TABLE candidat_certification DROP FOREIGN KEY FK_8D903D8E8D0EB82');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E956AE248B');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9441B8B65');
        $this->addSql('ALTER TABLE cours_mission DROP FOREIGN KEY FK_D3619B477ECF78B0');
        $this->addSql('ALTER TABLE cours_mission DROP FOREIGN KEY FK_D3619B47BE6CAE90');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DAD749FDF1');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458C974D9ED');
        $this->addSql('ALTER TABLE file_object DROP FOREIGN KEY FK_10BA8D53E28B07AC');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F10335F61');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA4F84F6E');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CB03A8386');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D1BB0859F1');
        $this->addSql('ALTER TABLE postulation DROP FOREIGN KEY FK_DA7D4E9B8D0EB82');
        $this->addSql('ALTER TABLE postulation DROP FOREIGN KEY FK_DA7D4E9B4CC8505A');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404FB88E14F');
        $this->addSql('ALTER TABLE rendu_mission DROP FOREIGN KEY FK_84BAC7D3BE6CAE90');
        $this->addSql('ALTER TABLE rendu_mission DROP FOREIGN KEY FK_84BAC7D38D0EB82');
        $this->addSql('ALTER TABLE snapshot DROP FOREIGN KEY FK_2C4D15355ED23C43');
        $this->addSql('ALTER TABLE snapshot DROP FOREIGN KEY FK_2C4D1535F675F31B');
        $this->addSql('ALTER TABLE snapshot_item DROP FOREIGN KEY FK_D4BB0637B39395E');
        $this->addSql('ALTER TABLE snapshot_item DROP FOREIGN KEY FK_D4BB063E28B07AC');
        $this->addSql('ALTER TABLE snapshot_item DROP FOREIGN KEY FK_D4BB063AD22E95D');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A682D40A1F');
        $this->addSql('ALTER TABLE traitement_reclamation DROP FOREIGN KEY FK_FE317EF32D6BA2D9');
        $this->addSql('ALTER TABLE traitement_reclamation DROP FOREIGN KEY FK_FE317EF3642B8210');
        $this->addSql('ALTER TABLE candidat_mission DROP FOREIGN KEY FK_C96A04D68D0EB82');
        $this->addSql('ALTER TABLE candidat_mission DROP FOREIGN KEY FK_C96A04D6BE6CAE90');
        $this->addSql('ALTER TABLE workspace DROP FOREIGN KEY FK_8D9400198D0EB82');
        $this->addSql('DROP TABLE artifact');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE candidat_certification');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE cours_mission');
        $this->addSql('DROP TABLE entretien');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE file_object');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE offre_emploi');
        $this->addSql('DROP TABLE postulation');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE rendu_mission');
        $this->addSql('DROP TABLE snapshot');
        $this->addSql('DROP TABLE snapshot_item');
        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE traitement_reclamation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE candidat_mission');
        $this->addSql('DROP TABLE workspace');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
