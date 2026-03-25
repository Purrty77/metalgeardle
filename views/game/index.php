<?php

declare(strict_types=1);

$characterDirectory = array_map(
    static fn ($character) => [
        'id' => $character->id,
        'name' => $character->name,
        'image_small' => $character->imageSmall,
    ],
    $characters
);
$timezoneLabel = (string) (\App\Core\Config::get('app', 'timezone') ?? 'Europe/Paris');
$dailySolveCopy = $dailySolveCount === 1
    ? '1 player has already found today\'s character.'
    : sprintf('%d players have already found today\'s character.', $dailySolveCount);
$yesterdayCharacterCopy = $yesterdayCharacterName !== null
    ? sprintf('Yesterday\'s character was %s.', $yesterdayCharacterName)
    : 'Yesterday\'s character will appear here once a second daily challenge has run.';
?>
<main class="page-shell">
    <section class="hero">
        <div class="hero-topline">
            <p class="eyebrow">Classic Mode</p>
            <div class="streak-badge" aria-label="Current streak">
                <span class="streak-badge__star" aria-hidden="true">★</span>
                <span class="streak-badge__label">Streak</span>
                <span class="streak-badge__count" id="streak-count">0</span>
            </div>
        </div>
        <h1>Metal Gear Dle</h1>
        <p class="lede">Guess the character of the day and compare six gameplay-friendly attributes.</p>
        <p class="hero-note" id="daily-solves-copy"><?= htmlspecialchars($dailySolveCopy, ENT_QUOTES, 'UTF-8') ?></p>
        <p class="hero-subnote"><?= htmlspecialchars($yesterdayCharacterCopy, ENT_QUOTES, 'UTF-8') ?></p>
    </section>

    <?php if (!empty($error)): ?>
        <section class="panel">
            <p class="form-error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p>
        </section>
    <?php endif; ?>

    <?php if ($challenge === null): ?>
        <section class="panel panel-warning">
            <h2>No challenge available</h2>
            <p>The app could not create a daily challenge yet. Make sure the <code>characters</code> table has data.</p>
        </section>
    <?php else: ?>
        <section class="panel panel-search">
            <form method="post" action="<?= htmlspecialchars(rtrim((string) $baseUrl, '/') . '/guess', ENT_QUOTES, 'UTF-8') ?>" class="guess-form" id="guess-form">
                <label for="guess">Enter a character name or alias</label>
                <div class="guess-row">
                    <div class="search-shell">
                        <input
                            id="guess"
                            name="guess"
                            type="text"
                            placeholder="Solid Snake, Ocelot, The Boss..."
                            autocomplete="off"
                            required
                        >
                        <div class="search-suggestions is-hidden" id="search-suggestions"></div>
                    </div>
                    <button type="submit">Submit Guess</button>
                </div>
            </form>
            <p class="client-note">Your tries are stored only in this browser. The daily character rolls over every day at 4:00 PM <?= htmlspecialchars($timezoneLabel, ENT_QUOTES, 'UTF-8') ?>.</p>
            <p class="form-error is-hidden" id="guess-error"></p>
        </section>

        <section class="panel panel-next-challenge is-hidden" id="next-challenge-panel">
            <p class="meta-label">Mission Complete</p>
            <h2>Come back in <span id="next-challenge-timer">00:00:00</span></h2>
            <p class="client-note">A new daily character unlocks at 4:00 PM <?= htmlspecialchars($timezoneLabel, ENT_QUOTES, 'UTF-8') ?>.</p>
        </section>

        <section class="panel">
            <div class="challenge-meta">
                <div>
                    <span class="meta-label">Daily Challenge</span>
                    <strong><?= htmlspecialchars($challenge->date, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div>
                    <span class="meta-label">Mode</span>
                    <strong><?= htmlspecialchars(ucfirst($challenge->mode), ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div>
                    <span class="meta-label">Attempts</span>
                    <strong id="attempt-count">0</strong>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="results-head">
                <h2>Guess History</h2>
                <p>Green means match. Orange means partial overlap. Red means mismatch.</p>
            </div>

            <p class="empty-state" id="empty-state">No guesses yet. Start with your best guess.</p>
            <div class="results-table-wrap is-hidden" id="results-wrap">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Guess</th>
                            <th>Gender</th>
                            <th>Affiliation</th>
                            <th>Nationality</th>
                            <th>First Game</th>
                            <th>Era</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody id="results-body"></tbody>
                </table>
            </div>
        </section>

        <div class="win-modal is-hidden" id="win-modal" aria-hidden="true">
            <div class="win-modal-backdrop" data-close-modal="true"></div>
            <div class="win-modal-card" role="dialog" aria-modal="true" aria-labelledby="win-modal-title">
                <button type="button" class="win-modal-close" id="win-modal-close" aria-label="Close">×</button>
                <p class="meta-label">Daily Challenge</p>
                <h2 id="win-modal-title">Congratulations</h2>
                <p class="win-modal-copy">Mission complete. You found today's Metal Gear character.</p>
                <img src="" alt="" class="win-modal-image is-hidden" id="win-modal-image">
                <h3 class="reveal-name" id="win-modal-name"></h3>
            </div>
        </div>

        <script>
            window.MetalGearDle = {
                baseUrl: <?= json_encode(rtrim((string) $baseUrl, '/'), JSON_THROW_ON_ERROR) ?>,
                challengeDate: <?= json_encode($challenge->date, JSON_THROW_ON_ERROR) ?>,
                timezone: <?= json_encode((string) (\App\Core\Config::get('app', 'timezone') ?? 'Europe/Paris'), JSON_THROW_ON_ERROR) ?>,
                dailyResetHour: <?= json_encode((int) (\App\Core\Config::get('app', 'daily_reset_hour') ?? 16), JSON_THROW_ON_ERROR) ?>,
                dailySolveCount: <?= json_encode((int) $dailySolveCount, JSON_THROW_ON_ERROR) ?>,
                characters: <?= json_encode($characterDirectory, JSON_THROW_ON_ERROR) ?>,
            };
        </script>
        <script src="<?= htmlspecialchars(rtrim((string) $baseUrl, '/') . '/public/assets/js/game.js', ENT_QUOTES, 'UTF-8') ?>"></script>
    <?php endif; ?>
</main>
