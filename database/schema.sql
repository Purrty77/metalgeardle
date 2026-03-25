CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    affiliation VARCHAR(100) NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    first_game VARCHAR(100) NOT NULL,
    era VARCHAR(50) NOT NULL,
    role_type VARCHAR(50) NOT NULL,
    image_small VARCHAR(255) DEFAULT NULL,
    image_large VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE character_aliases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    alias_name VARCHAR(100) NOT NULL,
    CONSTRAINT fk_character_aliases_character
        FOREIGN KEY (character_id) REFERENCES characters(id)
        ON DELETE CASCADE
);

CREATE TABLE daily_challenges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    character_id INT NOT NULL,
    mode VARCHAR(50) NOT NULL DEFAULT 'classic',
    CONSTRAINT uq_daily_mode UNIQUE (date, mode),
    CONSTRAINT fk_daily_challenges_character
        FOREIGN KEY (character_id) REFERENCES characters(id)
        ON DELETE CASCADE
);

CREATE TABLE daily_challenge_solves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    challenge_id INT NOT NULL,
    solver_token VARCHAR(64) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_daily_solver UNIQUE (challenge_id, solver_token),
    CONSTRAINT fk_daily_challenge_solves_challenge
        FOREIGN KEY (challenge_id) REFERENCES daily_challenges(id)
        ON DELETE CASCADE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
