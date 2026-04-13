CREATE TABLE suggestions (
    id INT NOT NULL AUTO_INCREMENT,
    suggestion_token VARCHAR(64) NOT NULL,
    suggestion_date DATE NOT NULL,
    title VARCHAR(120) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_suggestions_token_date (suggestion_token, suggestion_date),
    KEY idx_suggestions_date (suggestion_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
