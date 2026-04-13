<?php

declare(strict_types=1);

$characterDirectory = array_map(
    static fn ($character) => [
        'id' => $character->id,
        'name' => $character->name,
        'image_small' => $character->imageSmall,
        'aliases' => $character->aliases,
    ],
    $characters
);
$dailySolveCopy = $dailySolveCount === 1
    ? '1 player has already found today\'s character.'
    : sprintf('%d players have already found today\'s character.', $dailySolveCount);
$yesterdayCharacterCopy = $yesterdayCharacterName !== null
    ? sprintf('Yesterday\'s character was %s.', $yesterdayCharacterName)
    : 'Yesterday\'s character will appear here once a second daily challenge has run.';
$challengeFrequency = $challengeCharacter?->codecFrequency ?? null;
$codecHintValue = $challengeFrequency !== null && $challengeFrequency !== ''
    ? $challengeFrequency
    : 'N/A';
$concealedCodecValue = 'CLASSIFIED';
$codecHintDefaultCopy = 'The codec frequency is a hint. If the character never had an official one, the display shows N/A.';
$codecHintHiddenCopy = 'The codec hint is optional. Choose whether you want to reveal the daily frequency clue.';
?>
<main class="page-shell codec-shell">
    <div class="codec-scanlines" aria-hidden="true"></div>
    <div class="codec-grid-overlay" aria-hidden="true"></div>

    <section class="codec-header">
        <div class="codec-header__left">
            <p class="codec-label codec-label--active">CODEC_V.212</p>
            <p class="codec-header-stat" id="daily-solves-copy"><?= htmlspecialchars($dailySolveCopy, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="codec-header__center">
            <h1>Metal Gear Dle</h1>
        </div>
        <div class="codec-header__right">
            <div class="codec-header-status">
                <div class="streak-badge codec-badge" aria-label="Current streak">
                    <span class="streak-badge__star" aria-hidden="true">★</span>
                    <span class="streak-badge__label">Streak</span>
                    <span class="streak-badge__count" id="streak-count">0</span>
                </div>
                <p class="codec-header-note"><?= htmlspecialchars($yesterdayCharacterCopy, ENT_QUOTES, 'UTF-8') ?></p>
                <div class="header-share">
                    <button type="button" class="header-share-button" id="header-share-button">
                        <span class="header-share-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M15 5a3 3 0 1 1 .18 1l-6.1 3.17a3 3 0 0 1 0 1.66L15.18 14a3 3 0 1 1-.68 1.9c0-.18.02-.35.05-.52l-6.17-3.2a3 3 0 1 1 0-2.37l6.17-3.2A3 3 0 0 1 15 5Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span>Share Challenge</span>
                    </button>
                    <p class="header-share-status is-hidden" id="header-share-status"></p>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($error)): ?>
        <section class="codec-alert">
            <p class="form-error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p>
        </section>
    <?php endif; ?>

    <?php if ($challenge === null): ?>
        <section class="panel panel-warning">
            <h2>No challenge available</h2>
            <p>The app could not create a daily challenge yet. Make sure the <code>characters</code> table has data.</p>
        </section>
    <?php else: ?>
        <section class="codec-stage">
            <article class="codec-portrait-panel codec-portrait-panel--guess" id="current-guess-panel">
                <div class="codec-portrait-frame">
                    <img src="" alt="" class="codec-portrait codec-portrait--guess is-hidden" id="current-guess-image">
                    <div class="codec-portrait-placeholder" id="current-guess-placeholder">
                        <span>?</span>
                    </div>
                </div>
                <div class="codec-portrait-caption">
                    <p class="codec-label">Current Guess</p>
                    <strong id="current-guess-name">Awaiting input</strong>
                    <span id="current-guess-meta">Enter a character to begin the transmission.</span>
                </div>
            </article>

            <section class="codec-frequency-panel">
                <p class="codec-label codec-label--center">PTT</p>
                <div class="codec-frequency-shell">
                    <span class="codec-side-arrow codec-side-arrow--left" aria-hidden="true"></span>
                    <span class="codec-side-arrow codec-side-arrow--right" aria-hidden="true"></span>
                    <div class="codec-signal-frame" aria-hidden="true">
                        <div class="codec-signal-ambient codec-signal-ambient--left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="codec-signal-ambient codec-signal-ambient--right">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="codec-signal-svg-wrap codec-signal-svg-wrap--primary" aria-hidden="true">
                            <svg class="codec-signal-svg" viewBox="0 0 220 150" role="presentation" focusable="false">
                                <defs>
                                    <clipPath id="codec-signal-curve">
                                        <path d="M0 0H214A214 214 0 0 0 48 150H0Z"></path>
                                    </clipPath>
                                </defs>
                                <g clip-path="url(#codec-signal-curve)">
                                    <rect class="codec-signal-bar" x="0" y="2" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="16" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="30" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="44" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="58" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="72" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="86" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="100" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="114" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="128" width="214" height="12"></rect>
                                    <rect class="codec-signal-bar" x="0" y="142" width="214" height="12"></rect>
                                </g>
                            </svg>
                        </div>
                        <div class="codec-frequency-readout">
                            <span
                                id="codec-frequency-readout"
                                data-actual="<?= htmlspecialchars($codecHintValue, ENT_QUOTES, 'UTF-8') ?>"
                                data-concealed="<?= htmlspecialchars($concealedCodecValue, ENT_QUOTES, 'UTF-8') ?>"
                            ><?= htmlspecialchars($concealedCodecValue, ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                    <p class="codec-frequency-memory">MEMORY</p>
                </div>
                <p
                    class="codec-frequency-hint"
                    id="codec-frequency-hint"
                    data-default-copy="<?= htmlspecialchars($codecHintDefaultCopy, ENT_QUOTES, 'UTF-8') ?>"
                    data-hidden-copy="<?= htmlspecialchars($codecHintHiddenCopy, ENT_QUOTES, 'UTF-8') ?>"
                >
                    <?= htmlspecialchars($codecHintHiddenCopy, ENT_QUOTES, 'UTF-8') ?>
                </p>
                <label class="codec-frequency-toggle is-hidden" id="codec-frequency-toggle-wrap" for="codec-frequency-toggle">
                    <input type="checkbox" id="codec-frequency-toggle">
                    <span>Show codec frequency hint</span>
                </label>
                <div class="codec-status-strip challenge-meta">
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

            <article class="codec-portrait-panel codec-portrait-panel--target" id="target-panel">
                <div class="codec-portrait-frame codec-portrait-frame--target">
                    <img src="" alt="" class="codec-portrait codec-portrait--target is-hidden" id="target-image">
                    <div class="codec-portrait-placeholder codec-portrait-placeholder--target" id="target-placeholder">
                        <span>?</span>
                    </div>
                </div>
                <div class="codec-portrait-caption">
                    <p class="codec-label">Target ID</p>
                    <strong id="target-name">Unknown</strong>
                    <span id="target-meta">Identity locked until mission completion.</span>
                </div>
            </article>
        </section>

        <section class="panel panel-search codec-terminal-panel">
            <form method="post" action="<?= htmlspecialchars(rtrim((string) $baseUrl, '/') . '/guess', ENT_QUOTES, 'UTF-8') ?>" class="guess-form" id="guess-form">
                <label for="guess">Input Subject Frequency</label>
                <div class="guess-row codec-terminal-row">
                    <div class="search-shell codec-terminal-shell">
                        <span class="codec-terminal-prompt" aria-hidden="true">&gt;</span>
                        <input
                            id="guess"
                            name="guess"
                            type="text"
                            placeholder="Input subject frequency..."
                            autocomplete="off"
                            required
                        >
                        <div class="search-suggestions is-hidden" id="search-suggestions"></div>
                    </div>
                    <button type="submit">Send</button>
                </div>
            </form>
            <div class="codec-terminal-meta">
                <p class="codec-terminal-caption">SHADOW_MOSES_COMM_LINK // SECURE_ENCRYPTION</p>
            </div>
            <p class="form-error is-hidden" id="guess-error"></p>
        </section>

        <section class="panel panel-next-challenge codec-terminal-panel is-hidden" id="next-challenge-panel">
            <p class="codec-label">Mission Complete</p>
            <h2>Come back in <span id="next-challenge-timer">00:00:00</span></h2>
            <p class="client-note">A new daily character unlocks at 6:00 AM <span id="next-challenge-timezone">CET</span>.</p>
            <div class="win-modal-share next-challenge-share">
                <button type="button" class="win-modal-share-button" id="next-challenge-share-button">
                    <span class="win-modal-share-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M15 5a3 3 0 1 1 .18 1l-6.1 3.17a3 3 0 0 1 0 1.66L15.18 14a3 3 0 1 1-.68 1.9c0-.18.02-.35.05-.52l-6.17-3.2a3 3 0 1 1 0-2.37l6.17-3.2A3 3 0 0 1 15 5Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span>Share Intel</span>
                </button>
                <p class="win-modal-share-status is-hidden" id="next-challenge-share-status"></p>
            </div>
        </section>


        <section class="panel codec-log-panel">
            <div class="results-head codec-log-head">
                <div>
                    <p class="codec-label">LOG_STREAM_V212.DAT</p>
                    <h2>Guess History</h2>
                </div>
                <div class="history-legend" aria-label="Indicator guide">
                    <div class="history-legend__item">
                        <span class="history-legend__swatch history-legend__swatch--correct"></span>
                        <span>Correct</span>
                    </div>
                    <div class="history-legend__item">
                        <span class="history-legend__swatch history-legend__swatch--close"></span>
                        <span>Partial</span>
                    </div>
                    <div class="history-legend__item">
                        <span class="history-legend__swatch history-legend__swatch--incorrect"></span>
                        <span>Incorrect</span>
                    </div>
                    <div class="history-legend__item">
                        <span class="history-legend__swatch history-legend__swatch--higher"></span>
                        <span>Higher</span>
                    </div>
                    <div class="history-legend__item">
                        <span class="history-legend__swatch history-legend__swatch--lower"></span>
                        <span>Lower</span>
                    </div>
                    <div class="history-legend__roles">
                        <span class="history-legend__roles-label">Roles</span>
                        <span>boss</span>
                        <span>commander</span>
                        <span>mercenary</span>
                        <span>scientist</span>
                        <span>soldier</span>
                        <span>support</span>
                        <span>spy</span>
                    </div>
                </div>
            </div>

            <p class="empty-state is-hidden" id="empty-state"></p>
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

        <div class="preference-modal is-hidden" id="codec-preference-modal" aria-hidden="true">
            <div class="preference-modal-backdrop"></div>
            <div class="preference-modal-card" role="dialog" aria-modal="true" aria-labelledby="codec-preference-title">
                <p class="codec-label codec-label--center">Codec Preference</p>
                <h2 id="codec-preference-title">Reveal the frequency hint?</h2>
                <p class="preference-modal-copy">The codec frequency can make some missions easier. Choose once now, and you can change it later with the toggle under the codec panel.</p>
                <div class="preference-modal-actions">
                    <button type="button" class="preference-modal-button preference-modal-button--secondary" id="codec-preference-hide">
                        Keep Hidden
                    </button>
                    <button type="button" class="preference-modal-button" id="codec-preference-show">
                        Show Hint
                    </button>
                </div>
            </div>
        </div>

        <div class="win-modal is-hidden" id="win-modal" aria-hidden="true">
            <div class="win-modal-backdrop" data-close-modal="true"></div>
            <div class="win-modal-card" role="dialog" aria-modal="true" aria-labelledby="win-modal-title">
                <button type="button" class="win-modal-close" id="win-modal-close" aria-label="Close">×</button>
                <p class="codec-label codec-label--center">Daily Challenge</p>
                <h2 id="win-modal-title">Congratulations</h2>
                <p class="win-modal-copy">Mission complete. You found today's Metal Gear character.</p>
                <img src="" alt="" class="win-modal-image is-hidden" id="win-modal-image">
                <p class="win-modal-attempts" id="win-modal-attempts"></p>
                <div class="win-modal-share">
                    <button type="button" class="win-modal-share-button" id="win-modal-share-button">
                        <span class="win-modal-share-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M15 5a3 3 0 1 1 .18 1l-6.1 3.17a3 3 0 0 1 0 1.66L15.18 14a3 3 0 1 1-.68 1.9c0-.18.02-.35.05-.52l-6.17-3.2a3 3 0 1 1 0-2.37l6.17-3.2A3 3 0 0 1 15 5Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span>Share Intel</span>
                    </button>
                    <p class="win-modal-share-status is-hidden" id="win-modal-share-status"></p>
                </div>
                <h3 class="reveal-name" id="win-modal-name"></h3>
            </div>
        </div>

        <div class="win-modal is-hidden" id="shared-modal" aria-hidden="true">
            <div class="win-modal-backdrop" data-close-share="true"></div>
            <div class="win-modal-card win-modal-card--shared" role="dialog" aria-modal="true" aria-labelledby="shared-modal-title">
                <button type="button" class="win-modal-close" id="shared-modal-close" aria-label="Close">×</button>
                <p class="codec-label codec-label--center">Incoming Transmission</p>
                <h2 id="shared-modal-title">Challenge Intel Received</h2>
                <p class="win-modal-copy" id="shared-modal-copy"></p>
                <div class="win-modal-share">
                    <button type="button" class="win-modal-share-button" id="shared-modal-dismiss-button">
                        <span>Accept Mission</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="win-modal is-hidden" id="updates-modal" aria-hidden="true">
            <div class="win-modal-backdrop" data-close-updates="true"></div>
            <div class="win-modal-card win-modal-card--updates" role="dialog" aria-modal="true" aria-labelledby="updates-modal-title">
                <button type="button" class="win-modal-close" id="updates-modal-close" aria-label="Close">×</button>
                <p class="codec-label codec-label--center">System Update</p>
                <h2 id="updates-modal-title">Latest Fixes & Features</h2>
                <div class="updates-modal-grid">
                    <section class="updates-modal-section">
                        <h3>Latest Changes</h3>
                        <ul class="updates-modal-list">
                            <li>You can now choose whether the codec frequency hint is shown, and that preference is saved on your device.</li>
                            <li>First-time visitors are asked before the frequency hint is revealed, so the daily clue is never spoiled by default.</li>
                            <li>The Guess History header now displays the full role list, so every possible role label is visible in one place.</li>
                        </ul>
                    </section>
                </div>
                <div class="win-modal-share">
                    <button type="button" class="win-modal-share-button" id="updates-modal-dismiss-button">
                        <span>Back to Mission</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="win-modal is-hidden" id="suggestion-modal" aria-hidden="true">
            <div class="win-modal-backdrop" data-close-suggestion="true"></div>
            <div class="win-modal-card win-modal-card--suggestion" role="dialog" aria-modal="true" aria-labelledby="suggestion-modal-title">
                <button type="button" class="win-modal-close" id="suggestion-modal-close" aria-label="Close">×</button>
                <p class="codec-label codec-label--center">Incoming Transmission</p>
                <h2 id="suggestion-modal-title">Send a Suggestion</h2>
                <p class="win-modal-copy">Send one suggestion per daily cycle. Keep it short, clear, and mission useful.</p>
                <form class="suggestion-form" id="suggestion-form">
                    <label class="suggestion-form__label" for="suggestion-title">Title</label>
                    <input id="suggestion-title" name="title" type="text" maxlength="120" required>
                    <label class="suggestion-form__label" for="suggestion-body">Suggestion</label>
                    <textarea id="suggestion-body" name="body" rows="6" maxlength="2000" required></textarea>
                    <p class="form-error is-hidden" id="suggestion-error"></p>
                    <p class="suggestion-success is-hidden" id="suggestion-success"></p>
                    <div class="win-modal-share">
                        <button type="submit" class="win-modal-share-button" id="suggestion-submit-button">
                            <span>Transmit Suggestion</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="toast-notice is-hidden" id="suggestion-thanks-toast" aria-live="polite" aria-atomic="true">
            <div class="toast-notice__card">
                <div class="toast-notice__content">
                    <p class="toast-notice__eyebrow">Community Intel</p>
                    <p class="toast-notice__title">Thanks to everyone using the suggestion box.</p>
                    <p class="toast-notice__copy">Your feedback helps shape future updates. Use the footer form if you want to send mission ideas too.</p>
                </div>
                <button type="button" class="toast-notice__close" id="suggestion-thanks-toast-close" aria-label="Dismiss">×</button>
            </div>
        </div>

        <div class="toast-notice is-hidden" id="patch-notes-toast" aria-live="polite" aria-atomic="true">
            <div class="toast-notice__card">
                <div class="toast-notice__content">
                    <p class="toast-notice__eyebrow">System Update</p>
                    <p class="toast-notice__title">New patch live. Check the patch notes.</p>
                    <p class="toast-notice__copy">The latest update adds the new codec hint preference flow. Open the patch notes if you want the full mission brief.</p>
                    <button type="button" class="toast-notice__action" id="patch-notes-toast-button">Open Patch Notes</button>
                </div>
                <button type="button" class="toast-notice__close" id="patch-notes-toast-close" aria-label="Dismiss">×</button>
            </div>
        </div>

        <script>
            window.MetalGearDle = {
                baseUrl: <?= json_encode(rtrim((string) $baseUrl, '/'), JSON_THROW_ON_ERROR) ?>,
                challengeDate: <?= json_encode($challenge->date, JSON_THROW_ON_ERROR) ?>,
                timezone: <?= json_encode((string) (\App\Core\Config::get('app', 'timezone') ?? 'Europe/Paris'), JSON_THROW_ON_ERROR) ?>,
                dailyResetHour: <?= json_encode((int) (\App\Core\Config::get('app', 'daily_reset_hour') ?? 6), JSON_THROW_ON_ERROR) ?>,
                dailySolveCount: <?= json_encode((int) $dailySolveCount, JSON_THROW_ON_ERROR) ?>,
                characters: <?= json_encode($characterDirectory, JSON_THROW_ON_ERROR) ?>,
            };
        </script>
        <script src="<?= htmlspecialchars(rtrim((string) $baseUrl, '/') . '/public/assets/js/game.js?v=5', ENT_QUOTES, 'UTF-8') ?>"></script>
    <?php endif; ?>
</main>
