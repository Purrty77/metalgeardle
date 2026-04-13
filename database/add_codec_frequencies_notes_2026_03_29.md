# Codec frequency notes

Patch file:
- /Applications/MAMP/htdocs/metalgeardle/database/add_codec_frequencies_2026_03_29.sql

Policy used for this version:
- only official in-game codec/radio frequencies are assigned
- if the character does not have a direct official frequency, `codec_frequency` is left NULL
- no lore-inspired or invented fallback frequencies are used in this version

Primary sources used:
- https://metalgear.fandom.com/wiki/Metal_Gear_Wiki
- https://metalgear.fandom.com/wiki/Radio
- https://metalgear.fandom.com/wiki/Codec
- https://metalgear.fandom.com/wiki/Similarities_between_games

Examples of official mappings kept:
- Big Boss: 120.85
- Solid Snake: 141.80
- Meryl Silverburgh: 140.15
- Otacon: 141.12
- EVA: 142.52
- Para-Medic: 145.73
- Sigint: 148.41
- Gray Fox: 140.27
- Mei Ling: 140.96
- Roy Campbell: 140.85
- Drebin 893: 148.93

Examples intentionally left NULL:
- Volgin
- The Fear
- The Fury
- The Pain
- Quiet
- Venom Snake
- Skull Face
- Code Talker
- Eli
- Psycho Mantis
- Vulcan Raven
- Decoy Octopus
- Fortune
- Vamp
- Fatman
- Johnny Sasaki
- Liquid Ocelot

Implementation note:
- `codec_frequency` is VARCHAR(10) so values keep their original displayed format
- the patch starts by setting every row to NULL, then only official values are filled back in
