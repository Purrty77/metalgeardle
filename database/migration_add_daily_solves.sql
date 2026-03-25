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
