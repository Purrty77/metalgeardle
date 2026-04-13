-- Add lore-accurate codec frequencies to characters
-- Only official in-game codec/radio frequencies are assigned.
-- Characters without a direct official frequency keep NULL.

ALTER TABLE characters
    ADD COLUMN codec_frequency VARCHAR(10) NULL AFTER role_type;

UPDATE characters SET codec_frequency = NULL;

UPDATE characters SET codec_frequency = '141.80' WHERE id = 1;  -- Solid Snake / Iroquois Pliskin
UPDATE characters SET codec_frequency = '120.85' WHERE id = 2;  -- Big Boss
UPDATE characters SET codec_frequency = '141.80' WHERE id = 3;  -- The Boss (Virtuous Mission)
UPDATE characters SET codec_frequency = '141.23' WHERE id = 4;  -- Revolver Ocelot
UPDATE characters SET codec_frequency = '141.12' WHERE id = 5;  -- Otacon
UPDATE characters SET codec_frequency = '145.38' WHERE id = 6;  -- Kazuhira Miller
UPDATE characters SET codec_frequency = '141.80' WHERE id = 7;  -- Raiden
UPDATE characters SET codec_frequency = '140.15' WHERE id = 8;  -- Meryl Silverburgh
UPDATE characters SET codec_frequency = '141.80' WHERE id = 9;  -- Liquid Snake ("Master Miller")
UPDATE characters SET codec_frequency = '142.52' WHERE id = 11; -- EVA
UPDATE characters SET codec_frequency = '145.73' WHERE id = 12; -- Para-Medic
UPDATE characters SET codec_frequency = '148.41' WHERE id = 13; -- Sigint
UPDATE characters SET codec_frequency = '140.85' WHERE id = 14; -- Major Zero
UPDATE characters SET codec_frequency = '000.00' WHERE id = 16; -- The End
UPDATE characters SET codec_frequency = '144.75' WHERE id = 19; -- The Sorrow / Groznyj Grad cell door sequence
UPDATE characters SET codec_frequency = '146.74' WHERE id = 24; -- Huey Emmerich
UPDATE characters SET codec_frequency = '142.52' WHERE id = 25; -- Strangelove
UPDATE characters SET codec_frequency = '140.89' WHERE id = 26; -- Cecile Cosima Caminades
UPDATE characters SET codec_frequency = '145.66' WHERE id = 27; -- Amanda Valenciano Libre
UPDATE characters SET codec_frequency = '140.96' WHERE id = 28; -- Chico
UPDATE characters SET codec_frequency = '140.11' WHERE id = 29; -- Paz Ortega Andrade
UPDATE characters SET codec_frequency = '141.12' WHERE id = 32; -- Sniper Wolf
UPDATE characters SET codec_frequency = '140.27' WHERE id = 36; -- Gray Fox
UPDATE characters SET codec_frequency = '140.85' WHERE id = 37; -- Naomi Hunter
UPDATE characters SET codec_frequency = '140.96' WHERE id = 38; -- Mei Ling
UPDATE characters SET codec_frequency = '140.85' WHERE id = 39; -- Roy Campbell
UPDATE characters SET codec_frequency = '140.96' WHERE id = 40; -- Rosemary
UPDATE characters SET codec_frequency = '140.48' WHERE id = 44; -- Olga Gurlukovich / Mr. X
UPDATE characters SET codec_frequency = '141.52' WHERE id = 45; -- Emma Emmerich
UPDATE characters SET codec_frequency = '148.93' WHERE id = 47; -- Drebin 893
