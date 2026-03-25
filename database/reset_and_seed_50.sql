SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM daily_challenges;
DELETE FROM character_aliases;
DELETE FROM characters;

ALTER TABLE daily_challenges AUTO_INCREMENT = 1;
ALTER TABLE character_aliases AUTO_INCREMENT = 1;
ALTER TABLE characters AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO characters (name, gender, affiliation, nationality, first_game, era, role_type, image_small, image_large) VALUES
('Solid Snake', 'male', 'FOXHOUND|Philanthropy', 'American', 'Metal Gear', '1995', 'soldier', NULL, NULL),
('Big Boss', 'male', 'MSF', 'American', 'Metal Gear 2: Solid Snake', '1974', 'commander', NULL, NULL),
('The Boss', 'female', 'Cobra Unit', 'American', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', NULL, NULL),
('Revolver Ocelot', 'male', 'Patriots|GRU', 'Soviet|American', 'Metal Gear Solid', '2005', 'spy', NULL, NULL),
('Otacon', 'male', 'Philanthropy', 'American', 'Metal Gear Solid', '2005', 'scientist', NULL, NULL),
('Kazuhira Miller', 'male', 'MSF|Diamond Dogs', 'Japanese|American', 'Metal Gear 2: Solid Snake', '1984', 'support', NULL, NULL),
('Raiden', 'male', 'FOXHOUND', 'Liberian|American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'soldier', NULL, NULL),
('Meryl Silverburgh', 'female', 'FOXHOUND', 'American', 'Metal Gear Solid', '2005', 'soldier', NULL, NULL),
('Liquid Snake', 'male', 'FOXHOUND', 'British|American', 'Metal Gear Solid', '2005', 'commander', NULL, NULL),
('Solidus Snake', 'male', 'Sons of Liberty', 'American|British', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'commander', NULL, NULL),
('EVA', 'female', 'Philosophers', 'Chinese', 'Metal Gear Solid 3: Snake Eater', '1964', 'spy', NULL, NULL),
('Para-Medic', 'female', 'FOX', 'American', 'Metal Gear Solid 3: Snake Eater', '1964', 'support', NULL, NULL),
('Sigint', 'male', 'FOX', 'American', 'Metal Gear Solid 3: Snake Eater', '1964', 'support', NULL, NULL),
('Major Zero', 'male', 'FOX|Patriots', 'British', 'Metal Gear Solid 3: Snake Eater', '1964', 'commander', NULL, NULL),
('Volgin', 'male', 'GRU', 'Soviet', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', NULL, NULL),
('The End', 'male', 'Cobra Unit', 'Hungarian', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', NULL, NULL),
('The Fear', 'male', 'Cobra Unit', 'Unknown', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', NULL, NULL),
('The Fury', 'male', 'Cobra Unit', 'Soviet', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', NULL, NULL),
('The Sorrow', 'male', 'Cobra Unit', 'Russian', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', NULL, NULL),
('The Pain', 'male', 'Cobra Unit', 'Soviet', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', NULL, NULL),
('Quiet', 'female', 'Diamond Dogs|XOF', 'Unknown', 'Metal Gear Solid V: The Phantom Pain', '1984', 'mercenary', NULL, NULL),
('Venom Snake', 'male', 'Diamond Dogs|MSF', 'Unknown', 'Metal Gear Solid V: The Phantom Pain', '1984', 'soldier', NULL, NULL),
('Skull Face', 'male', 'XOF', 'Hungarian', 'Metal Gear Solid V: Ground Zeroes', '1975', 'commander', NULL, NULL),
('Huey Emmerich', 'male', 'MSF|Diamond Dogs', 'American', 'Metal Gear Solid: Peace Walker', '1974', 'scientist', NULL, NULL),
('Strangelove', 'female', 'MSF', 'American', 'Metal Gear Solid: Peace Walker', '1974', 'scientist', NULL, NULL),
('Cecile Cosima Caminades', 'female', 'MSF', 'French', 'Metal Gear Solid: Peace Walker', '1974', 'support', NULL, NULL),
('Amanda Valenciano Libre', 'female', 'Sandinistas', 'Nicaraguan', 'Metal Gear Solid: Peace Walker', '1974', 'soldier', NULL, NULL),
('Chico', 'male', 'Sandinistas', 'Nicaraguan', 'Metal Gear Solid: Peace Walker', '1974', 'support', NULL, NULL),
('Paz Ortega Andrade', 'female', 'Cipher|MSF', 'Costa Rican', 'Metal Gear Solid: Peace Walker', '1974', 'spy', NULL, NULL),
('Code Talker', 'male', 'Diamond Dogs', 'Navajo', 'Metal Gear Solid V: The Phantom Pain', '1984', 'scientist', NULL, NULL),
('Eli', 'male', 'Diamond Dogs', 'British', 'Metal Gear Solid V: The Phantom Pain', '1984', 'soldier', NULL, NULL),
('Sniper Wolf', 'female', 'FOXHOUND', 'Kurdish|Iraqi', 'Metal Gear Solid', '2005', 'boss', NULL, NULL),
('Psycho Mantis', 'male', 'FOXHOUND', 'Russian', 'Metal Gear Solid', '2005', 'boss', NULL, NULL),
('Vulcan Raven', 'male', 'FOXHOUND', 'Inuit', 'Metal Gear Solid', '2005', 'boss', NULL, NULL),
('Decoy Octopus', 'male', 'FOXHOUND', 'Mexican', 'Metal Gear Solid', '2005', 'spy', NULL, NULL),
('Gray Fox', 'male', 'FOXHOUND', 'American', 'Metal Gear 2: Solid Snake', '1999', 'soldier', NULL, NULL),
('Naomi Hunter', 'female', 'FOXHOUND|Patriots', 'British|American', 'Metal Gear Solid', '2005', 'scientist', NULL, NULL),
('Mei Ling', 'female', 'U.S. Army', 'Chinese|American', 'Metal Gear Solid', '2005', 'support', NULL, NULL),
('Roy Campbell', 'male', 'FOXHOUND', 'American', 'Metal Gear 2: Solid Snake', '2005', 'commander', NULL, NULL),
('Rosemary', 'female', 'Army Intelligence', 'American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'support', NULL, NULL),
('Fortune', 'female', 'Dead Cell', 'American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'boss', NULL, NULL),
('Vamp', 'male', 'Dead Cell', 'Romanian', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'mercenary', NULL, NULL),
('Fatman', 'male', 'Dead Cell', 'American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'boss', NULL, NULL),
('Olga Gurlukovich', 'female', 'Gurlukovich Mercenaries|Patriots', 'Russian', 'Metal Gear Solid 2: Sons of Liberty', '2007', 'soldier', NULL, NULL),
('Emma Emmerich', 'female', 'Navy', 'American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'scientist', NULL, NULL),
('Sunny', 'female', 'Philanthropy', 'American', 'Metal Gear Solid 4: Guns of the Patriots', '2014', 'support', NULL, NULL),
('Drebin 893', 'male', 'Drebin Network', 'Liberian', 'Metal Gear Solid 4: Guns of the Patriots', '2014', 'support', NULL, NULL),
('Johnny Sasaki', 'male', 'Rat Patrol 01', 'American', 'Metal Gear Solid 4: Guns of the Patriots', '2014', 'soldier', NULL, NULL),
('Liquid Ocelot', 'male', 'Patriots|Outer Haven', 'Unknown', 'Metal Gear Solid 4: Guns of the Patriots', '2014', 'commander', NULL, NULL),
('Dr. Madnar', 'male', 'Outer Heaven|Zanzibar Land', 'Czech', 'Metal Gear 2: Solid Snake', '1999', 'scientist', NULL, NULL);

INSERT INTO character_aliases (character_id, alias_name) VALUES
(1, 'Snake'),
(1, 'Old Snake'),
(1, 'Iroquois Pliskin'),
(2, 'Naked Snake'),
(2, 'Jack'),
(4, 'Ocelot'),
(4, 'Shalashaska'),
(5, 'Hal Emmerich'),
(6, 'Kaz'),
(6, 'Master Miller'),
(7, 'Jack the Ripper'),
(9, 'Liquid'),
(10, 'George Sears'),
(11, 'Tatyana'),
(14, 'Zero'),
(15, 'Colonel Volgin'),
(22, 'Punished Snake'),
(24, 'Huey'),
(29, 'Pacifica Ocean'),
(31, 'White Mamba'),
(33, 'Mantis'),
(36, 'Frank Jaeger'),
(36, 'Cyborg Ninja'),
(39, 'Colonel Campbell'),
(40, 'Rose'),
(44, 'Olga'),
(47, 'Drebin'),
(48, 'Akiba'),
(49, 'Ocelot');

INSERT INTO daily_challenges (date, character_id, mode) VALUES
(CURDATE(), 1, 'classic');
