ALTER TABLE characters
    RENAME COLUMN image TO image_small;

ALTER TABLE characters
    ADD COLUMN image_large VARCHAR(255) DEFAULT NULL AFTER image_small;
