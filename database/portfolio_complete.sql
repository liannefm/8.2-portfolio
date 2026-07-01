-- ============================================================
-- Portfolio database — volledig schema + data
-- Gebruik: importeer in phpMyAdmin of mysql CLI
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ============================================================
-- 1. BESTAANDE TABELLEN (profile, contact_links, skills, languages)
--    Alleen aanmaken als ze nog niet bestaan
-- ============================================================

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `bio_nl` text NOT NULL,
  `bio_en` text NOT NULL,
  `avatar_url` varchar(255) NOT NULL,
  `greeting_nl` varchar(255) NOT NULL,
  `greeting_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `contact_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `label` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_contact_profile` (`profile_id`),
  CONSTRAINT `fk_contact_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description_nl` varchar(255) NOT NULL,
  `description_en` varchar(255) NOT NULL,
  `color` varchar(20) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_skills_profile` (`profile_id`),
  CONSTRAINT `fk_skills_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `level_nl` varchar(50) NOT NULL,
  `level_en` varchar(50) NOT NULL,
  `proficiency` tinyint(4) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_languages_profile` (`profile_id`),
  CONSTRAINT `fk_languages_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 2. NIEUWE TABELLEN
-- ============================================================

-- Opleiding
CREATE TABLE `education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `year_start` year NOT NULL,
  `year_end` year DEFAULT NULL,
  `degree_nl` varchar(150) NOT NULL,
  `degree_en` varchar(150) NOT NULL,
  `organization` varchar(150) NOT NULL,
  `description_nl` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_education_profile` (`profile_id`),
  CONSTRAINT `fk_education_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Werkervaring
CREATE TABLE `experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `year_start` year NOT NULL,
  `year_end` year DEFAULT NULL,
  `position_nl` varchar(150) NOT NULL,
  `position_en` varchar(150) NOT NULL,
  `company` varchar(150) NOT NULL,
  `description_nl` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_experience_profile` (`profile_id`),
  CONSTRAINT `fk_experience_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Projecten
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `title_nl` varchar(150) NOT NULL,
  `title_en` varchar(150) NOT NULL,
  `description_nl` text NOT NULL,
  `description_en` text NOT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `date_nl` varchar(50) DEFAULT NULL,
  `date_en` varchar(50) DEFAULT NULL,
  `role_nl` varchar(50) DEFAULT NULL,
  `role_en` varchar(50) DEFAULT NULL,
  `type_nl` varchar(50) DEFAULT NULL,
  `type_en` varchar(50) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `intro_nl` text DEFAULT NULL,
  `intro_en` text DEFAULT NULL,
  `outcome_nl` varchar(255) DEFAULT NULL,
  `outcome_en` varchar(255) DEFAULT NULL,
  `live_url` varchar(255) DEFAULT NULL,
  `source_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_projects_profile` (`profile_id`),
  CONSTRAINT `fk_projects_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tags per project (HTML, CSS, React, etc.)
CREATE TABLE `project_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_tags_project` (`project_id`),
  CONSTRAINT `fk_tags_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bullet points per project ("Wat ik deed")
CREATE TABLE `project_highlights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `text_nl` varchar(255) NOT NULL,
  `text_en` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_highlights_project` (`project_id`),
  CONSTRAINT `fk_highlights_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Media per project: foto's (kind='photo') en code-screenshots (kind='code')
CREATE TABLE `project_media` (
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

-- Moodboard items (foto's en notities)
CREATE TABLE `mood_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `type` enum('photo','note') NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `caption_nl` varchar(100) DEFAULT NULL,
  `caption_en` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_mood_profile` (`profile_id`),
  CONSTRAINT `fk_mood_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Persoonlijke feitjes
CREATE TABLE `personality_facts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL,
  `text_nl` varchar(100) NOT NULL,
  `text_en` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_facts_profile` (`profile_id`),
  CONSTRAINT `fk_facts_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 3. DATA INSERTEN
-- ============================================================

-- Profile
INSERT INTO `profile` (`id`, `name`, `role`, `bio_nl`, `bio_en`, `avatar_url`, `greeting_nl`, `greeting_en`) VALUES
(1, 'Lianne', 'front-end developer',
 'Een goed ontwerp verdient een goede uitvoering. Daarom zet ik ideeën om in snelle, toegankelijke en interactieve websites waar gebruikers met plezier doorheen klikken.',
 'A good design deserves proper execution. That''s why I turn ideas into fast, accessible, and interactive websites that people enjoy browsing through.',
 'lianne-character.png',
 'Welkom! Ik ben Lianne ✨',
 'Welcome! I am Lianne ✨');

-- Contact links
INSERT INTO `contact_links` (`profile_id`, `platform`, `url`, `label`, `sort_order`) VALUES
(1, 'email', 'mailto:lianne@example.com', 'lianne@example.com', 1),
(1, 'linkedin', 'https://linkedin.com/in/lianne-dev', 'linkedin.com/in/lianne-dev', 2),
(1, 'github', 'https://github.com/lianne-dev', 'github.com/lianne-dev', 3),
(1, 'instagram', 'https://instagram.com/lianne.codes', '@lianne.codes', 4);

-- Skills
INSERT INTO `skills` (`profile_id`, `name`, `description_nl`, `description_en`, `color`, `sort_order`) VALUES
(1, 'HTML', 'Semantische, toegankelijke structuur voor elke pagina.', 'Semantic, accessible structure for every page.', 'var(--pink)', 1),
(1, 'CSS', 'Responsive layouts, animaties en dat net even strakke detail.', 'Responsive layouts, animation and that extra-crisp detail.', 'var(--blue)', 2),
(1, 'React', 'Componenten en interactie voor dynamische interfaces.', 'Components and interaction for dynamic interfaces.', 'var(--lime)', 3),
(1, 'PHP', 'Server-side logica en formulieren die gewoon werken.', 'Server-side logic and forms that just work.', 'var(--yellow)', 4),
(1, 'SQL', 'Data opslaan, koppelen en ophalen uit databases.', 'Storing, linking and querying data in databases.', 'var(--pink)', 5);

-- Languages
INSERT INTO `languages` (`profile_id`, `name`, `level_nl`, `level_en`, `proficiency`, `sort_order`) VALUES
(1, 'Nederlands', 'Moedertaal', 'Native', 5, 1),
(1, 'Engels', 'Vloeiend · C1', 'Fluent · C1', 4, 2);

-- Education
INSERT INTO `education` (`profile_id`, `year_start`, `year_end`, `degree_nl`, `degree_en`, `organization`, `sort_order`) VALUES
(1, 2024, 2028, 'Creative Software Developer', 'Creative Software Developer', 'Grafisch Lyceum Utrecht', 1),
(1, 2019, 2024, 'VMBO-TL', 'VMBO-TL', 'Revius Lyceum Wijk bij Duurstede', 2);

-- Experience
INSERT INTO `experience` (`profile_id`, `year_start`, `year_end`, `position_nl`, `position_en`, `company`, `description_nl`, `description_en`, `sort_order`) VALUES
(1, 2024, NULL, 'Front-end Developer', 'Front-end Developer', 'Bedrijfsnaam (voorbeeld)',
 'Bouwen en onderhouden van responsive web-interfaces in React. Samenwerken met design en backend.',
 'Building and maintaining responsive web interfaces in React. Working closely with design and backend.', 1),
(1, 2023, 2024, 'Stage Front-end Developer', 'Front-end Developer Intern', 'Bedrijfsnaam (voorbeeld)',
 'Meegebouwd aan klantprojecten met HTML, CSS en PHP. Eerste ervaring met databases (SQL).',
 'Helped build client projects with HTML, CSS and PHP. First experience with databases (SQL).', 2);

-- Projects
INSERT INTO `projects` (`id`, `profile_id`, `title_nl`, `title_en`, `description_nl`, `description_en`, `thumbnail_url`, `date_nl`, `date_en`, `role_nl`, `role_en`, `type_nl`, `type_en`, `duration`, `intro_nl`, `intro_en`, `outcome_nl`, `outcome_en`, `sort_order`) VALUES
(1, 1, 'YOUR own webpage', 'YOUR own webpage',
 'Mijn eerste statische webpagina — de basis van HTML & CSS in de praktijk gebracht.',
 'My very first static web page — putting the basics of HTML & CSS into practice.',
 'work-own-webpage.png', 'dec 2024', 'Dec 2024',
 'Soloproject', 'Solo project', 'Statische website', 'Static website', '± 1 week',
 'Mijn allereerste webpagina, helemaal vanaf nul opgebouwd met pure HTML en CSS. Een klein project waarin ik de basis van semantische structuur, typografie en kleurgebruik onder de knie kreeg.',
 'My very first web page, built completely from scratch with pure HTML and CSS. A small project where I got to grips with semantic structure, typography and colour.',
 'De basis waarop al mijn latere projecten verder bouwen.',
 'The foundation all my later projects build on.', 1),

(2, 1, 'Webshop', 'Webshop',
 'Een werkende webshop met productoverzicht, winkelmandje en afrekenflow.',
 'A working webshop with a product overview, shopping cart and checkout flow.',
 'work-webshop.png', 'mei 2025', 'May 2025',
 'Schoolproject', 'School project', 'Webshop', 'Webshop', '± 4 weken',
 'Een volledig werkende webshop met productoverzicht, winkelmandje en afrekenflow. Producten en bestellingen worden opgeslagen in een database en aangestuurd met PHP.',
 'A fully working webshop with a product overview, shopping cart and checkout flow. Products and orders are stored in a database and driven by PHP.',
 'Mijn eerste echte kennismaking met back-end en databases.',
 'My first real taste of back-end and databases.', 2),

(3, 1, 'WordPress website', 'WordPress website',
 'Een volledig ingerichte website gebouwd in WordPress — met thema, paginabeheer en aanpasbare content.',
 'A fully built website made in WordPress — with a theme, page management and customisable content.',
 'work-wordpress.png', 'sep 2025', 'Sep 2025',
 'Soloproject', 'Solo project', 'CMS-website', 'CMS website', '± 3 weken',
 'Een volledig ingerichte konijnen-blog gebouwd in WordPress, met een eigen thema, paginabeheer en aanpasbare content zodat de eigenaar zelf blogs kan plaatsen.',
 'A fully built rabbit blog made in WordPress, with a custom theme, page management and editable content so the owner can post blogs themselves.',
 'Een site die de eigenaar volledig zelf kan onderhouden.',
 'A site the owner can fully maintain on their own.', 3),

(4, 1, 'Vuurmeisje & Watermeisje game', 'Fireboy & Watergirl game',
 'Een browserspel waarin twee spelers samenwerken — geïnspireerd op het bekende co-op concept.',
 'A browser game where two players cooperate — inspired by the well-known co-op concept.',
 'work-firewater.png', 'jan 2026', 'Jan 2026',
 'Duoproject', 'Duo project', 'Browsergame', 'Browser game', '± 5 weken',
 'Een co-op browsergame waarin twee spelers samenwerken om door een winters level te komen — geïnspireerd op het bekende Fireboy & Watergirl-concept, volledig speelbaar in de browser.',
 'A co-op browser game where two players work together to get through a wintry level — inspired by the well-known Fireboy & Watergirl concept, fully playable in the browser.',
 'Een speelbaar spel waar je samen doorheen puzzelt.',
 'A playable game you puzzle through together.', 4),

(5, 1, 'Kiosk', 'Kiosk',
 'Een bestel-kiosk voor een vegetarische zaak. Touch-vriendelijke interface om zelf te bestellen en af te rekenen.',
 'A self-order kiosk for a vegetarian eatery. Touch-friendly interface to order and pay yourself.',
 'work-kiosk.png', 'apr 2026', 'Apr 2026',
 'Stageproject', 'Internship project', 'Bestel-kiosk', 'Order kiosk', '± 8 weken',
 'Een touch-vriendelijke bestel-kiosk voor een vegetarische zaak. Gebruikers stellen zelf hun bestelling samen en rekenen af. Gebouwd in React met TypeScript.',
 'A touch-friendly order kiosk for a vegetarian eatery. Users put together their own order and pay. Built in React with TypeScript.',
 'Een vlotte selfservice-ervaring van menu tot afrekenen.',
 'A smooth self-service experience from menu to checkout.', 5),

(6, 1, 'Waarderingsapp', 'Rating app',
 'Een app waarmee gebruikers studenten kunnen beoordelen. Scores en feedback worden opgeslagen en teruggekoppeld.',
 'An app that lets users rate students. Scores and feedback are stored and reported back clearly.',
 'work-rating.png', 'mei 2026', 'May 2026',
 'Schoolproject', 'School project', 'Webapp', 'Web app', '± 6 weken',
 'Een app waarmee gebruikers studenten kunnen beoordelen. Scores en feedback worden opgeslagen en overzichtelijk teruggekoppeld. Gebouwd met Vue en Nuxt.',
 'An app that lets users rate students. Scores and feedback are stored and reported back clearly. Built with Vue and Nuxt.',
 'Inzicht in feedback, in één overzichtelijke app.',
 'Insight into feedback, in one clear app.', 6);

-- Voorbeeld-link naar GitHub (pas per project aan in de admin)
UPDATE `projects` SET `source_url` = 'https://github.com/lianne-dev' WHERE `profile_id` = 1;

-- Project tags
INSERT INTO `project_tags` (`project_id`, `tag`, `sort_order`) VALUES
(1, 'HTML', 1), (1, 'CSS', 2), (1, 'JS', 3),
(2, 'PHP', 1), (2, 'CSS', 2), (2, 'SQL', 3),
(3, 'WordPress', 1), (3, 'PHP', 2), (3, 'CSS', 3), (3, 'SQL', 4),
(4, 'HTML', 1), (4, 'PHP', 2), (4, 'CSS', 3), (4, 'JS', 4),
(5, 'React', 1), (5, 'SCSS', 2), (5, 'TypeScript', 3), (5, 'JSON', 4), (5, 'SQL', 5),
(6, 'Vue', 1), (6, 'Nuxt', 2), (6, 'SQL', 3), (6, 'TypeScript', 4);

-- Project highlights (bullets)
INSERT INTO `project_highlights` (`project_id`, `text_nl`, `text_en`, `sort_order`) VALUES
(1, 'Pagina opgebouwd met semantische HTML', 'Built the page with semantic HTML', 1),
(1, 'Layout en kleuren volledig in CSS gestyled', 'Styled the full layout and colours in CSS', 2),
(1, 'Responsive gemaakt voor mobiel en desktop', 'Made it responsive for mobile and desktop', 3),

(2, 'Productoverzicht uit een MySQL-database geladen', 'Loaded the product overview from a MySQL database', 1),
(2, 'Winkelmandje met sessies in PHP', 'Shopping cart with sessions in PHP', 2),
(2, 'Afrekenflow met formuliervalidatie', 'Checkout flow with form validation', 3),

(3, 'Eigen WordPress-thema opgezet en gestyled', 'Set up and styled a custom WordPress theme', 1),
(3, 'Pagina- en blogstructuur ingericht', 'Arranged the page and blog structure', 2),
(3, 'Content beheerbaar gemaakt voor de klant', 'Made content manageable for the client', 3),

(4, 'Spellogica en collision-detection in JavaScript', 'Game logic and collision detection in JavaScript', 1),
(4, 'Besturing voor twee spelers op één toetsenbord', 'Two-player controls on a single keyboard', 2),
(4, 'Levels, obstakels en doelen ontworpen', 'Designed levels, obstacles and goals', 3),

(5, 'Touch-interface ontworpen voor zelf bestellen', 'Designed a touch interface for self-ordering', 1),
(5, 'Menu en bestellingen via een API gekoppeld', 'Connected menu and orders through an API', 2),
(5, 'State-beheer met React en TypeScript', 'State management with React and TypeScript', 3),

(6, 'Inloggen met rollen (student / docent)', 'Login with roles (student / teacher)', 1),
(6, 'Beoordelingen opslaan in een database', 'Stored ratings in a database', 2),
(6, 'Resultaten visueel teruggekoppeld', 'Reported results back visually', 3);

-- Mood items
INSERT INTO `mood_items` (`profile_id`, `type`, `key_name`, `caption_nl`, `caption_en`, `image_url`, `sort_order`) VALUES
(1, 'photo', 'me', 'dit ben ik :)', 'this is me :)', NULL, 1),
(1, 'photo', 'weekend', 'maak kennis met mijn konijnen', 'meet the rabbits', NULL, 2),
(1, 'photo', 'setup', 'mijn setup', 'my setup', NULL, 3),
(1, 'photo', 'coffee', 'dit ben ik nog een keer :)', 'this is me again :)', NULL, 4),
(1, 'photo', 'creative', 'mijn gezin', 'my family', NULL, 5),
(1, 'photo', 'friends', 'met vriendinnen', 'with friends', NULL, 6),
(1, 'note', 'music', 'altijd muziek aan terwijl ik code 🎧', 'always music on while I code 🎧', NULL, 7),
(1, 'note', 'todo', 'to-do: alle konijnen aaien die ik tegenkom', 'to-do: pet all the bunnies I meet', NULL, 8),
(1, 'note', 'quote', '"maak het simpel, maar bijzonder."', '"keep it simple, but special."', NULL, 9);

-- Personality facts
INSERT INTO `personality_facts` (`profile_id`, `text_nl`, `text_en`, `sort_order`) VALUES
(1, 'koffieliefhebber', 'coffee lover', 1),
(1, 'gamen', 'gaming', 2),
(1, 'fotografie', 'photography', 3),
(1, 'muziek 24/7', 'music 24/7', 4),
(1, 'tekenen', 'drawing', 5),
(1, 'reizen', 'travel', 6);

COMMIT;
