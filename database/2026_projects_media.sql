-- ============================================================
-- Migratie: projecten uitbreiden met media, video en links
--   - live_url    : link naar de live website (optioneel)
--   - source_url  : link naar de GitHub-repo
--   - video_url   : link naar een video, bijv. YouTube (optioneel)
--   - project_media : foto's en code-screenshots (met uitleg) per project
-- Importeer in phpMyAdmin of via mysql CLI op database `portfolio`.
-- ============================================================

START TRANSACTION;

-- Nieuwe kolommen op projects (IF NOT EXISTS = veilig om nogmaals te draaien)
ALTER TABLE `projects`
  ADD COLUMN IF NOT EXISTS `live_url`   varchar(255) DEFAULT NULL AFTER `outcome_en`,
  ADD COLUMN IF NOT EXISTS `source_url` varchar(255) DEFAULT NULL AFTER `live_url`,
  ADD COLUMN IF NOT EXISTS `video_url`  varchar(255) DEFAULT NULL AFTER `source_url`;

-- Media per project: foto's (kind='photo') en code-screenshots (kind='code').
-- Bij 'code' is de caption de uitleg bij de code.
CREATE TABLE IF NOT EXISTS `project_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `kind` enum('photo','code') NOT NULL DEFAULT 'photo',
  `image_url` varchar(255) NOT NULL,
  `caption_nl` varchar(255) DEFAULT NULL,
  `caption_en` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_media_project` (`project_id`),
  CONSTRAINT `fk_media_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Voorbeeld-links zodat de GitHub-knop meteen zichtbaar is (pas aan in de admin).
UPDATE `projects` SET `source_url` = 'https://github.com/lianne-dev'
  WHERE `source_url` IS NULL OR `source_url` = '';

COMMIT;
