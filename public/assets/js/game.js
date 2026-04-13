(() => {
    const config = window.MetalGearDle;

    if (!config) {
        return;
    }

    const timezone = config.timezone || 'Europe/Paris';
    const dailyResetHour = Number(config.dailyResetHour || 6);
    const storagePrefix = 'metalgeardle:';
    const solverTokenKey = 'metalgeardle:solver-token';
    const streakKey = 'metalgeardle:streak';
    const codecPreferenceKey = `${storagePrefix}codec-frequency-visible`;
    const preservedStorageKeys = new Set([solverTokenKey, streakKey, codecPreferenceKey]);
    const searchPanel = document.querySelector('.panel-search');
    const nextChallengePanel = document.querySelector('#next-challenge-panel');
    const nextChallengeTimer = document.querySelector('#next-challenge-timer');
    const nextChallengeTimezone = document.querySelector('#next-challenge-timezone');
    const dailySolvesCopy = document.querySelector('#daily-solves-copy');
    const streakCount = document.querySelector('#streak-count');
    const codecFrequencyReadout = document.querySelector('#codec-frequency-readout');
    const codecFrequencyHint = document.querySelector('#codec-frequency-hint');
    const codecFrequencyToggleWrap = document.querySelector('#codec-frequency-toggle-wrap');
    const codecFrequencyToggle = document.querySelector('#codec-frequency-toggle');
    const codecPreferenceModal = document.querySelector('#codec-preference-modal');
    const codecPreferenceShowButton = document.querySelector('#codec-preference-show');
    const codecPreferenceHideButton = document.querySelector('#codec-preference-hide');
    const signalStacks = [...document.querySelectorAll('.codec-signal-svg-wrap--primary')];
    const currentGuessImage = document.querySelector('#current-guess-image');
    const currentGuessPlaceholder = document.querySelector('#current-guess-placeholder');
    const currentGuessName = document.querySelector('#current-guess-name');
    const currentGuessMeta = document.querySelector('#current-guess-meta');
    const targetImage = document.querySelector('#target-image');
    const targetPlaceholder = document.querySelector('#target-placeholder');
    const targetName = document.querySelector('#target-name');
    const targetMeta = document.querySelector('#target-meta');
    const form = document.querySelector('#guess-form');
    const input = document.querySelector('#guess');
    const suggestions = document.querySelector('#search-suggestions');
    const errorBox = document.querySelector('#guess-error');
    const attemptCount = document.querySelector('#attempt-count');
    const emptyState = document.querySelector('#empty-state');
    const resultsWrap = document.querySelector('#results-wrap');
    const resultsBody = document.querySelector('#results-body');
    const hasResultsTable = Boolean(emptyState && resultsWrap && resultsBody);
    const winModal = document.querySelector('#win-modal');
    const winModalImage = document.querySelector('#win-modal-image');
    const winModalName = document.querySelector('#win-modal-name');
    const winModalAttempts = document.querySelector('#win-modal-attempts');
    const winModalShareButton = document.querySelector('#win-modal-share-button');
    const winModalShareStatus = document.querySelector('#win-modal-share-status');
    const nextChallengeShareButton = document.querySelector('#next-challenge-share-button');
    const nextChallengeShareStatus = document.querySelector('#next-challenge-share-status');
    const headerShareButton = document.querySelector('#header-share-button');
    const headerShareStatus = document.querySelector('#header-share-status');
    const winModalClose = document.querySelector('#win-modal-close');
    const sharedModal = document.querySelector('#shared-modal');
    const sharedModalCopy = document.querySelector('#shared-modal-copy');
    const sharedModalClose = document.querySelector('#shared-modal-close');
    const sharedModalDismissButton = document.querySelector('#shared-modal-dismiss-button');
    const updatesModal = document.querySelector('#updates-modal');
    const updatesModalClose = document.querySelector('#updates-modal-close');
    const updatesModalDismissButton = document.querySelector('#updates-modal-dismiss-button');
    const suggestionModal = document.querySelector('#suggestion-modal');
    const suggestionModalClose = document.querySelector('#suggestion-modal-close');
    const suggestionForm = document.querySelector('#suggestion-form');
    const suggestionTitleInput = document.querySelector('#suggestion-title');
    const suggestionBodyInput = document.querySelector('#suggestion-body');
    const suggestionError = document.querySelector('#suggestion-error');
    const suggestionSuccess = document.querySelector('#suggestion-success');
    const suggestionSubmitButton = document.querySelector('#suggestion-submit-button');
    const suggestionThanksToast = document.querySelector('#suggestion-thanks-toast');
    const suggestionThanksToastClose = document.querySelector('#suggestion-thanks-toast-close');
    const patchNotesToast = document.querySelector('#patch-notes-toast');
    const patchNotesToastButton = document.querySelector('#patch-notes-toast-button');
    const patchNotesToastClose = document.querySelector('#patch-notes-toast-close');
    const directory = Array.isArray(config.characters) ? config.characters : [];
    let storageKey = `${storagePrefix}${config.challengeDate}`;
    let lastRenderedCount = 0;
    let activeSuggestionIndex = -1;
    let isRevealLocked = false;

    const submitButton = form.querySelector('button[type="submit"]');

    if (!searchPanel || !nextChallengePanel || !nextChallengeTimer || !nextChallengeTimezone || !dailySolvesCopy || !streakCount || !codecFrequencyReadout || !codecFrequencyHint || !codecFrequencyToggleWrap || !codecFrequencyToggle || !codecPreferenceModal || !codecPreferenceShowButton || !codecPreferenceHideButton || !currentGuessImage || !currentGuessPlaceholder || !currentGuessName || !currentGuessMeta || !targetImage || !targetPlaceholder || !targetName || !targetMeta || !form || !input || !suggestions || !errorBox || !attemptCount || !winModal || !winModalImage || !winModalName || !winModalAttempts || !winModalShareButton || !winModalShareStatus || !nextChallengeShareButton || !nextChallengeShareStatus || !headerShareButton || !headerShareStatus || !winModalClose || !sharedModal || !sharedModalCopy || !sharedModalClose || !sharedModalDismissButton || !updatesModal || !updatesModalClose || !updatesModalDismissButton || !suggestionModal || !suggestionModalClose || !suggestionForm || !suggestionTitleInput || !suggestionBodyInput || !suggestionError || !suggestionSuccess || !suggestionSubmitButton || !suggestionThanksToast || !suggestionThanksToastClose || !patchNotesToast || !patchNotesToastButton || !patchNotesToastClose || !submitButton) {
        return;
    }

    let suggestionToastTimeoutId = null;
    let patchNotesToastTimeoutId = null;
    const suggestionToastKey = `${storagePrefix}suggestion-thanks:${config.challengeDate}`;
    const patchNotesToastKey = `${storagePrefix}patch-notes:${config.challengeDate}:codec-pref`;
    let codecPreference = null;

    const escapeHtml = (value) => String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const normalizeSearchValue = (value) => String(value)
        .toLowerCase()
        .trim();

    const formatter = new Intl.DateTimeFormat('en-CA', {
        timeZone: timezone,
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hourCycle: 'h23',
    });

    const getZonedParts = (date = new Date()) => {
        const parts = formatter.formatToParts(date);
        const lookup = {};

        parts.forEach((part) => {
            if (part.type !== 'literal') {
                lookup[part.type] = part.value;
            }
        });

        return {
            year: Number(lookup.year),
            month: Number(lookup.month),
            day: Number(lookup.day),
            hour: Number(lookup.hour),
            minute: Number(lookup.minute),
            second: Number(lookup.second),
        };
    };

    const formatDateParts = (year, month, day) => [
        String(year),
        String(month).padStart(2, '0'),
        String(day).padStart(2, '0'),
    ].join('-');

    const getChallengeDate = (date = new Date()) => {
        const parts = getZonedParts(date);
        const wallClock = Date.UTC(parts.year, parts.month - 1, parts.day, parts.hour, parts.minute, parts.second);
        const adjusted = new Date(wallClock - (parts.hour < dailyResetHour ? 86400000 : 0));

        return formatDateParts(
            adjusted.getUTCFullYear(),
            adjusted.getUTCMonth() + 1,
            adjusted.getUTCDate()
        );
    };

    const cleanupStoredEntries = (activeChallengeDate) => {
        const activeKey = `${storagePrefix}${activeChallengeDate}`;

        for (let index = localStorage.length - 1; index >= 0; index -= 1) {
            const key = localStorage.key(index);

            if (key && key.startsWith(storagePrefix) && key !== activeKey && !preservedStorageKeys.has(key)) {
                localStorage.removeItem(key);
            }
        }
    };

    const refreshChallengeWindow = ({ forceReload = false } = {}) => {
        const challengeDate = getChallengeDate();

        if (challengeDate !== config.challengeDate || forceReload) {
            localStorage.removeItem(storageKey);
            cleanupStoredEntries(challengeDate);
            closeWinModal();
            window.location.reload();
            return false;
        }

        storageKey = `${storagePrefix}${challengeDate}`;
        cleanupStoredEntries(challengeDate);
        return true;
    };

    const getNextResetDate = (date = new Date()) => {
        const parts = getZonedParts(date);
        const nowWallClock = Date.UTC(parts.year, parts.month - 1, parts.day, parts.hour, parts.minute, parts.second);
        let nextResetWallClock = Date.UTC(parts.year, parts.month - 1, parts.day, dailyResetHour, 0, 0);

        if (parts.hour >= dailyResetHour) {
            nextResetWallClock += 86400000;
        }

        return {
            nowWallClock,
            nextResetWallClock,
        };
    };

    const formatCountdown = (milliseconds) => {
        const totalSeconds = Math.max(0, Math.floor(milliseconds / 1000));
        const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');

        return `${hours}:${minutes}:${seconds}`;
    };

    const getTimezoneAbbreviation = (date = new Date()) => {
        const parts = new Intl.DateTimeFormat('en-US', {
            timeZone: timezone,
            timeZoneName: 'short',
        }).formatToParts(date);
        const zonePart = parts.find((part) => part.type === 'timeZoneName');
        return zonePart ? zonePart.value : timezone;
    };

    const updateNextChallengeTimer = () => {
        const now = new Date();
        const { nowWallClock, nextResetWallClock } = getNextResetDate(now);
        nextChallengeTimer.textContent = formatCountdown(nextResetWallClock - nowWallClock);
        nextChallengeTimezone.textContent = getTimezoneAbbreviation(now);
    };

    const scheduleDailyReset = () => {
        const { nowWallClock, nextResetWallClock } = getNextResetDate();
        const delay = Math.max(1000, nextResetWallClock - nowWallClock);

        window.setTimeout(() => {
            refreshChallengeWindow({ forceReload: true });
        }, delay);
    };

    const getSolverToken = () => {
        const existingToken = localStorage.getItem(solverTokenKey);

        if (existingToken) {
            return existingToken;
        }

        const nextToken = window.crypto && typeof window.crypto.randomUUID === 'function'
            ? window.crypto.randomUUID()
            : `solver-${Date.now()}-${Math.random().toString(16).slice(2)}`;

        localStorage.setItem(solverTokenKey, nextToken);

        return nextToken;
    };

    const formatDailySolvesCopy = (count) => count === 1
        ? "1 player has already found today's character."
        : `${count} players have already found today's character.`;

    const parseChallengeDate = (challengeDate) => {
        const [year, month, day] = String(challengeDate).split('-').map(Number);
        return Date.UTC(year, month - 1, day);
    };

    const readStreakState = () => {
        try {
            const raw = JSON.parse(localStorage.getItem(streakKey) || '{}');
            return {
                count: Number.isInteger(raw.count) ? raw.count : 0,
                lastWinDate: typeof raw.lastWinDate === 'string' ? raw.lastWinDate : null,
            };
        } catch (error) {
            return {
                count: 0,
                lastWinDate: null,
            };
        }
    };

    const writeStreakState = (state) => {
        localStorage.setItem(streakKey, JSON.stringify(state));
    };

    const normalizeStreakState = () => {
        const state = readStreakState();

        if (!state.lastWinDate) {
            return state;
        }

        const diffDays = Math.round((parseChallengeDate(config.challengeDate) - parseChallengeDate(state.lastWinDate)) / 86400000);

        if (diffDays > 1) {
            const resetState = {
                count: 0,
                lastWinDate: state.lastWinDate,
            };
            writeStreakState(resetState);
            return resetState;
        }

        return state;
    };

    const updateStreakBadge = () => {
        const state = normalizeStreakState();
        streakCount.textContent = String(Math.max(0, state.count));
    };

    const registerWinForStreak = () => {
        const state = normalizeStreakState();

        if (state.lastWinDate === config.challengeDate) {
            updateStreakBadge();
            return;
        }

        const diffDays = state.lastWinDate
            ? Math.round((parseChallengeDate(config.challengeDate) - parseChallengeDate(state.lastWinDate)) / 86400000)
            : null;

        const nextState = {
            count: diffDays === 1 ? state.count + 1 : 1,
            lastWinDate: config.challengeDate,
        };

        writeStreakState(nextState);
        updateStreakBadge();
    };

    const updateDailySolvesCopy = (count) => {
        dailySolvesCopy.textContent = formatDailySolvesCopy(count);
    };

    const setRevealLock = (locked) => {
        isRevealLocked = locked;
        input.disabled = locked;
        submitButton.disabled = locked;
        searchPanel.classList.toggle('is-busy', locked);

        if (locked) {
            closeSuggestions();
        }
    };

    const getRevealDuration = (cardCount) => (((Math.max(cardCount, 1) - 1) * animationStagger) + animationDuration);


    const runSignalSweep = () => {
        if (signalStacks.length === 0) {
            return;
        }

        signalStacks.forEach((stack) => {
            const bars = [...stack.querySelectorAll('.codec-signal-bar')].reverse();
            const litCount = Math.max(3, Math.floor(Math.random() * bars.length) + 1);

            bars.forEach((bar) => {
                bar.classList.remove('is-lit');
            });

            bars.forEach((bar, index) => {
                if (index < litCount) {
                    const delay = index * 80;
                    window.setTimeout(() => {
                        bar.classList.add('is-lit');
                    }, delay);
                }
            });
        });
    };

    const updateCurrentGuessPanel = (entry, previewCharacter = null) => {
        if (!entry && !previewCharacter) {
            currentGuessImage.removeAttribute('src');
            currentGuessImage.alt = '';
            currentGuessImage.classList.add('is-hidden');
            currentGuessPlaceholder.classList.remove('is-hidden');
            currentGuessName.textContent = 'Awaiting input';
            currentGuessMeta.textContent = 'Enter a character to begin the transmission.';
            return;
        }

        const source = previewCharacter
            ? {
                name: previewCharacter.name,
                image_small: previewCharacter.image_small || null,
            }
            : entry.guess;

        if (source.image_small) {
            currentGuessImage.src = source.image_small;
            currentGuessImage.alt = source.name;
            currentGuessImage.classList.remove('is-hidden');
            currentGuessPlaceholder.classList.add('is-hidden');
        } else {
            currentGuessImage.removeAttribute('src');
            currentGuessImage.alt = '';
            currentGuessImage.classList.add('is-hidden');
            currentGuessPlaceholder.classList.remove('is-hidden');
        }

        currentGuessName.textContent = source.name;

        if (previewCharacter) {
            currentGuessMeta.textContent = 'Queued subject. Press send to transmit the guess.';
            return;
        }

        currentGuessMeta.textContent = entry.is_win
            ? 'Transmission confirmed. Subject matches target.'
            : 'Latest submitted subject.';
    };

    const updateTargetPanel = (entry) => {
        if (!entry || !entry.is_win) {
            targetImage.removeAttribute('src');
            targetImage.alt = '';
            targetImage.classList.add('is-hidden');
            targetPlaceholder.classList.remove('is-hidden');
            targetName.textContent = 'Unknown';
            targetMeta.textContent = 'Identity locked until mission completion.';
            return;
        }

        if (entry.guess.image_large || entry.guess.image_small) {
            targetImage.src = entry.guess.image_large || entry.guess.image_small;
            targetImage.alt = entry.guess.name;
            targetImage.classList.remove('is-hidden');
            targetPlaceholder.classList.add('is-hidden');
        }

        targetName.textContent = entry.guess.name;
        targetMeta.textContent = 'Target unsealed. Visual confirmation acquired.';
    };

    const readStoredCount = () => {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '[]').length;
        } catch (error) {
            return 0;
        }
    };

    const readEntries = () => {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '[]');
        } catch (error) {
            return [];
        }
    };

    const writeEntries = (entries) => {
        localStorage.setItem(storageKey, JSON.stringify(entries));
    };

    const setError = (message) => {
        if (!message) {
            errorBox.textContent = '';
            errorBox.classList.add('is-hidden');
            return;
        }

        errorBox.textContent = message;
        errorBox.classList.remove('is-hidden');
    };

    const getSuggestionButtons = () => [...suggestions.querySelectorAll('.suggestion-item')];

    const setActiveSuggestion = (index) => {
        const buttons = getSuggestionButtons();
        activeSuggestionIndex = buttons.length === 0 ? -1 : index;

        buttons.forEach((button, buttonIndex) => {
            const isActive = buttonIndex === activeSuggestionIndex;
            button.classList.toggle('is-active', isActive);

            if (isActive) {
                button.scrollIntoView({ block: 'nearest' });
            }
        });
    };

    const closeSuggestions = () => {
        suggestions.innerHTML = '';
        suggestions.classList.add('is-hidden');
        activeSuggestionIndex = -1;
    };

    const openSuggestions = (matches) => {
        if (matches.length === 0) {
            closeSuggestions();
            return;
        }

        suggestions.innerHTML = matches.map((character) => `
            <button type="button" class="suggestion-item" data-name="${escapeHtml(character.name)}">
                ${character.image_small ? `<img src="${escapeHtml(character.image_small)}" alt="" class="suggestion-thumb">` : '<span class="suggestion-thumb suggestion-thumb-placeholder"></span>'}
                <span>${escapeHtml(character.name)}</span>
            </button>
        `).join('');
        suggestions.classList.remove('is-hidden');
        setActiveSuggestion(0);
    };

    const findPreviewCharacter = () => {
        const query = input.value.trim().toLowerCase();

        if (!query) {
            return null;
        }

        const buttons = getSuggestionButtons();
        const activeButton = activeSuggestionIndex >= 0 ? buttons[activeSuggestionIndex] : null;

        if (activeButton?.dataset.name) {
            const activeMatch = directory.find((character) => character.name === activeButton.dataset.name);
            if (activeMatch) {
                return activeMatch;
            }
        }

        return directory.find((character) => character.name.toLowerCase() === query) || null;
    };

    const renderSuggestions = () => {
        const query = normalizeSearchValue(input.value);

        if (query.length === 0) {
            closeSuggestions();
            return;
        }

        const guessedNames = new Set(
            readEntries().map((entry) => String(entry.guess.name || '').toLowerCase())
        );

        const matches = directory
            .filter((character) => !guessedNames.has(character.name.toLowerCase()))
            .filter((character) => {
                const names = [character.name, ...(Array.isArray(character.aliases) ? character.aliases : [])];
                return names.some((name) => normalizeSearchValue(name).includes(query));
            })
            .slice(0, 6);

        openSuggestions(matches);
    };

    const cell = (value, status, field = '') => {
        const cssStatus = status === 'higher' || status === 'lower' ? 'incorrect' : status;
        const directionalClass = status === 'higher' || status === 'lower'
            ? ` status-directional status-directional--${status}${field ? ` status-directional--${field}` : ''}`
            : '';
        const content = status === 'higher' || status === 'lower'
            ? `<span class="status-directional__value">${escapeHtml(value)}</span>`
            : escapeHtml(value);

        return `<td class="status-${cssStatus}${directionalClass}">${content}</td>`;
    };
    const animationDuration = 720;
    const animationStagger = 520;

    const syncModalOpenState = () => {
        const hasVisibleModal = [codecPreferenceModal, winModal, sharedModal, updatesModal, suggestionModal].some((modal) => !modal.classList.contains('is-hidden'));
        document.body.classList.toggle('modal-open', hasVisibleModal);
    };

    const closeCodecPreferenceModal = () => {
        codecPreferenceModal.classList.add('is-hidden');
        codecPreferenceModal.setAttribute('aria-hidden', 'true');
        syncModalOpenState();
    };

    const openCodecPreferenceModal = () => {
        codecPreferenceModal.classList.remove('is-hidden');
        codecPreferenceModal.classList.add('is-entering');
        codecPreferenceModal.setAttribute('aria-hidden', 'false');
        syncModalOpenState();

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                codecPreferenceModal.classList.remove('is-entering');
            });
        });
    };

    const closeWinModal = () => {
        winModal.classList.add('is-hidden');
        winModal.setAttribute('aria-hidden', 'true');
        syncModalOpenState();
    };

    const hideSearchPanel = () => {
        searchPanel.classList.add('is-hidden');
        nextChallengePanel.classList.remove('is-hidden');
        updateNextChallengeTimer();
    };

    const showSearchPanel = () => {
        searchPanel.classList.remove('is-hidden');
        nextChallengePanel.classList.add('is-hidden');
    };

    const formatWinAttempts = (count) => count === 1
        ? "You found today's character in 1 try."
        : `You found today's character in ${count} tries.`;

    const getShareBaseUrl = () => `${window.location.origin}${window.location.pathname}`;
    const shareVersion = '4';

    const setShareStatus = (target, message) => {
        if (!target) {
            return;
        }

        if (!message) {
            target.textContent = '';
            target.classList.add('is-hidden');
            return;
        }

        target.textContent = message;
        target.classList.remove('is-hidden');
    };

    const buildShareUrl = () => {
        const params = new URLSearchParams();
        params.set('shared_tries', String(readEntries().length));
        params.set('shared_date', config.challengeDate);
        params.set('share_v', shareVersion);
        params.set('share_nonce', String(Date.now()));
        return `${getShareBaseUrl()}?${params.toString()}`;
    };

    const formatShareDate = (challengeDate) => {
        const [year, month, day] = String(challengeDate).split('-');

        if (!year || !month || !day) {
            return String(challengeDate);
        }

        return `${day}/${month}/${year.slice(-2)}`;
    };

    const shareGlyphForStatus = (status) => {
        switch (status) {
            case 'correct':
                return '🟩';
            case 'close':
                return '🟨';
            case 'incorrect':
            case 'higher':
            case 'lower':
            default:
                return '🟥';
        }
    };

    const buildShareGrid = () => {
        const fields = ['gender', 'affiliation', 'nationality', 'first_game', 'era', 'role_type'];

        return readEntries()
            .slice()
            .reverse()
            .map((entry) => fields.map((field) => shareGlyphForStatus(entry.comparison[field])).join(''))
            .join('\n');
    };

    const buildShareText = () => {
        const entries = readEntries();
        const tries = entries.length;
        const triesLabel = tries === 1 ? '1 try' : `${tries} tries`;
        const header = `Metal Gear Dle ${formatShareDate(config.challengeDate)} ${triesLabel}`;
        const grid = buildShareGrid();

        return `${header}
${grid}
${buildShareUrl()}`;
    };

    const buildChallengeShareText = () => `Metal Gear Dle is live. Think you can identify today's character?
${getShareBaseUrl()}`;

    const stripShareParams = () => {
        const url = new URL(window.location.href);
        url.searchParams.delete('shared_tries');
        url.searchParams.delete('shared_date');
        window.history.replaceState({}, '', `${url.pathname}${url.search}${url.hash}`);
    };

    const closeSharedModal = () => {
        sharedModal.classList.add('is-hidden');
        sharedModal.setAttribute('aria-hidden', 'true');
        syncModalOpenState();
        stripShareParams();
    };

    const closeUpdatesModal = () => {
        updatesModal.classList.add('is-hidden');
        updatesModal.setAttribute('aria-hidden', 'true');
        syncModalOpenState();
    };

    const resetSuggestionMessages = () => {
        suggestionError.textContent = '';
        suggestionError.classList.add('is-hidden');
        suggestionSuccess.textContent = '';
        suggestionSuccess.classList.add('is-hidden');
    };

    const closeSuggestionModal = () => {
        suggestionModal.classList.add('is-hidden');
        suggestionModal.setAttribute('aria-hidden', 'true');
        syncModalOpenState();
    };

    const hideSuggestionToast = () => {
        if (suggestionToastTimeoutId) {
            window.clearTimeout(suggestionToastTimeoutId);
            suggestionToastTimeoutId = null;
        }

        suggestionThanksToast.classList.add('is-hidden');
        suggestionThanksToast.classList.remove('is-visible');
    };

    const hidePatchNotesToast = () => {
        if (patchNotesToastTimeoutId) {
            window.clearTimeout(patchNotesToastTimeoutId);
            patchNotesToastTimeoutId = null;
        }

        patchNotesToast.classList.add('is-hidden');
        patchNotesToast.classList.remove('is-visible');
    };

    const showSuggestionToast = () => {
        try {
            localStorage.setItem(suggestionToastKey, 'seen');
        } catch (error) {
            // Ignore storage write failures and still show the notice.
        }

        if (suggestionToastTimeoutId) {
            window.clearTimeout(suggestionToastTimeoutId);
        }

        suggestionThanksToast.classList.remove('is-hidden');

        requestAnimationFrame(() => {
            suggestionThanksToast.classList.add('is-visible');
        });

        suggestionToastTimeoutId = window.setTimeout(() => {
            hideSuggestionToast();
        }, 7800);
    };

    const maybeShowSuggestionToast = () => {
        try {
            if (localStorage.getItem(suggestionToastKey) === 'seen') {
                return;
            }
        } catch (error) {
            // Ignore storage read failures and allow the notice to show.
        }

        window.setTimeout(() => {
            const hasVisibleModal = [codecPreferenceModal, winModal, sharedModal, updatesModal, suggestionModal].some((modal) => !modal.classList.contains('is-hidden'));

            if (!hasVisibleModal) {
                showSuggestionToast();
            }
        }, 1400);
    };

    const showPatchNotesToast = () => {
        try {
            localStorage.setItem(patchNotesToastKey, 'seen');
        } catch (error) {
            // Ignore storage write failures and still show the notice.
        }

        if (patchNotesToastTimeoutId) {
            window.clearTimeout(patchNotesToastTimeoutId);
        }

        patchNotesToast.classList.remove('is-hidden');

        requestAnimationFrame(() => {
            patchNotesToast.classList.add('is-visible');
        });

        patchNotesToastTimeoutId = window.setTimeout(() => {
            hidePatchNotesToast();
        }, 9000);
    };

    const maybeShowPatchNotesToast = () => {
        try {
            if (localStorage.getItem(patchNotesToastKey) === 'seen') {
                return;
            }
        } catch (error) {
            // Ignore storage read failures and allow the notice to show.
        }

        const suggestionAlreadySeen = (() => {
            try {
                return localStorage.getItem(suggestionToastKey) === 'seen';
            } catch (error) {
                return false;
            }
        })();

        const delay = suggestionAlreadySeen ? 1700 : 9800;

        window.setTimeout(() => {
            const hasVisibleModal = [codecPreferenceModal, winModal, sharedModal, updatesModal, suggestionModal].some((modal) => !modal.classList.contains('is-hidden'));

            if (!hasVisibleModal) {
                showPatchNotesToast();
            }
        }, delay);
    };

    const maybeOpenSharedModal = () => {
        const params = new URLSearchParams(window.location.search);
        const tries = Number(params.get('shared_tries') || '0');
        const sharedDate = params.get('shared_date');

        if (!Number.isInteger(tries) || tries <= 0) {
            return;
        }

        const triesLabel = tries === 1 ? '1 try' : `${tries} tries`;
        const missionLabel = sharedDate === config.challengeDate
            ? "today's mission"
            : 'this mission';

        sharedModalCopy.textContent = `Your friend completed ${missionLabel} in ${triesLabel}. Can you beat that?`;
        sharedModal.classList.remove('is-hidden');
        sharedModal.classList.add('is-entering');
        sharedModal.setAttribute('aria-hidden', 'false');
        syncModalOpenState();

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                sharedModal.classList.remove('is-entering');
            });
        });
    };

    const openUpdatesModal = () => {
        updatesModal.classList.remove('is-hidden');
        updatesModal.classList.add('is-entering');
        updatesModal.setAttribute('aria-hidden', 'false');
        syncModalOpenState();

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                updatesModal.classList.remove('is-entering');
            });
        });
    };

    const openSuggestionModal = () => {
        resetSuggestionMessages();
        suggestionModal.classList.remove('is-hidden');
        suggestionModal.classList.add('is-entering');
        suggestionModal.setAttribute('aria-hidden', 'false');
        syncModalOpenState();

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                suggestionModal.classList.remove('is-entering');
                suggestionTitleInput.focus();
            });
        });
    };

    const getCodecPreference = () => {
        try {
            const stored = localStorage.getItem(codecPreferenceKey);
            return stored === 'show' || stored === 'hide' ? stored : null;
        } catch (error) {
            return null;
        }
    };

    const setCodecPreferenceStorage = (value) => {
        try {
            localStorage.setItem(codecPreferenceKey, value);
        } catch (error) {
            // Ignore storage failures and still update the UI.
        }
    };

    const applyCodecPreference = (value, { persist = true } = {}) => {
        const shouldShow = value === 'show';
        const actualValue = codecFrequencyReadout.dataset.actual || 'N/A';
        const concealedValue = codecFrequencyReadout.dataset.concealed || 'CLASSIFIED';
        const defaultCopy = codecFrequencyHint.dataset.defaultCopy || '';
        const hiddenCopy = codecFrequencyHint.dataset.hiddenCopy || '';

        codecPreference = shouldShow ? 'show' : 'hide';

        if (persist) {
            setCodecPreferenceStorage(codecPreference);
        }

        codecFrequencyReadout.textContent = shouldShow ? actualValue : concealedValue;
        codecFrequencyReadout.classList.toggle('is-concealed', !shouldShow);
        codecFrequencyHint.textContent = shouldShow ? defaultCopy : hiddenCopy;
        codecFrequencyHint.classList.toggle('is-concealed', !shouldShow);
        codecFrequencyToggle.checked = shouldShow;
        codecFrequencyToggleWrap.classList.remove('is-hidden');
    };

    const initializeCodecPreference = () => {
        const storedPreference = getCodecPreference();

        if (storedPreference) {
            applyCodecPreference(storedPreference, { persist: false });
            closeCodecPreferenceModal();
            return;
        }

        applyCodecPreference('hide', { persist: false });
        openCodecPreferenceModal();
    };

    const sharePayload = async (statusTarget, text, url) => {
        const shareData = {
            title: 'Metal Gear Dle',
            text,
            url,
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
                setShareStatus(statusTarget, 'Transmission sent.');
                return;
            }

            await navigator.clipboard.writeText(text);
            setShareStatus(statusTarget, 'Challenge link copied.');
        } catch (error) {
            if (error && error.name === 'AbortError') {
                return;
            }

            setShareStatus(statusTarget, 'Unable to share right now.');
        }
    };

    const shareResults = async (statusTarget) => {
        await sharePayload(statusTarget, buildShareText(), buildShareUrl());
    };

    const shareChallenge = async (statusTarget) => {
        await sharePayload(statusTarget, buildChallengeShareText(), getShareBaseUrl());
    };

    const submitSuggestion = async () => {
        resetSuggestionMessages();
        const title = suggestionTitleInput.value.trim();
        const body = suggestionBodyInput.value.trim();

        if (!title || !body) {
            suggestionError.textContent = 'Please fill in both the title and the suggestion text.';
            suggestionError.classList.remove('is-hidden');
            return;
        }

        suggestionSubmitButton.disabled = true;

        const payload = new URLSearchParams({
            suggestion_token: getSolverToken(),
            title,
            body,
        });

        try {
            const response = await fetch(`${config.baseUrl}/suggestion`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: payload.toString(),
            });
            const result = await response.json();

            if (!response.ok) {
                suggestionError.textContent = result.error || 'Unable to send the suggestion right now.';
                suggestionError.classList.remove('is-hidden');
                return;
            }

            suggestionSuccess.textContent = result.message || 'Suggestion transmitted.';
            suggestionSuccess.classList.remove('is-hidden');
            suggestionForm.reset();
        } catch (error) {
            suggestionError.textContent = 'Unable to send the suggestion right now.';
            suggestionError.classList.remove('is-hidden');
        } finally {
            suggestionSubmitButton.disabled = false;
        }
    };

    const openWinModal = (winningEntry) => {
        if (!winningEntry) {
            return;
        }

        if (winningEntry.guess.image_large) {
            winModalImage.src = winningEntry.guess.image_large;
            winModalImage.alt = winningEntry.guess.name;
            winModalImage.classList.remove('is-hidden');
        } else {
            winModalImage.removeAttribute('src');
            winModalImage.alt = '';
            winModalImage.classList.add('is-hidden');
        }

        winModalName.textContent = winningEntry.guess.name;
        winModalAttempts.textContent = formatWinAttempts(readEntries().length);
        setShareStatus(winModalShareStatus, '');
        setShareStatus(nextChallengeShareStatus, '');
        winModal.classList.remove('is-hidden');
        winModal.classList.add('is-entering');
        winModal.setAttribute('aria-hidden', 'false');
        syncModalOpenState();

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                winModal.classList.remove('is-entering');
            });
        });
    };

    const animateNewestRow = () => {
        if (!hasResultsTable) {
            return 0;
        }

        const newestRow = resultsBody.querySelector('tr');

        if (!newestRow) {
            return 0;
        }

        const cards = [...newestRow.querySelectorAll('td')];
        cards.forEach((card) => card.classList.add('reveal-pending'));

        requestAnimationFrame(() => {
            cards.forEach((card, index) => {
                window.setTimeout(() => {
                    card.classList.remove('reveal-pending');
                }, index * animationStagger);
            });
        });

        return getRevealDuration(cards.length);
    };

    const submitGuess = async () => {
        if (!refreshChallengeWindow()) {
            return;
        }

        setError('');
        closeSuggestions();

        if (isRevealLocked) {
            return;
        }

        const guess = input.value.trim();
        if (!guess) {
            return;
        }

        const existingEntries = readEntries();
        if (existingEntries.some((entry) => entry.guess.name.toLowerCase() === guess.toLowerCase())) {
            setError('You already tried that character today.');
            return;
        }

        const body = new URLSearchParams({
            guess,
            solver_token: getSolverToken(),
        });

        try {
            const response = await fetch(`${config.baseUrl}/guess`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: body.toString(),
            });
            const payload = await response.json();

            if (!response.ok) {
                setError(payload.error || 'Something went wrong while checking that guess.');
                return;
            }

            const nextEntries = [...existingEntries, payload];
            writeEntries(nextEntries);
            if (typeof payload.daily_solves_count === 'number') {
                updateDailySolvesCopy(payload.daily_solves_count);
            }
            input.value = '';
            render();
        } catch (error) {
            setError('Unable to submit your guess right now.');
        }
    };

    const render = () => {
        if (!refreshChallengeWindow()) {
            return;
        }

        const entries = readEntries();
        const shouldAnimateNewest = entries.length > lastRenderedCount;

        if (hasResultsTable) {
            resultsBody.innerHTML = entries
                .slice()
                .reverse()
                .map((entry) => `
                    <tr>
                        <td class="guess-cell">
                            <div class="guess-character">
                                ${entry.guess.image_small ? `<img src="${escapeHtml(entry.guess.image_small)}" alt="" class="guess-thumb">` : '<span class="guess-thumb guess-thumb-placeholder"></span>'}
                                <span>${escapeHtml(entry.guess.name)}</span>
                            </div>
                        </td>
                        ${cell(entry.guess.gender, entry.comparison.gender, 'gender')}
                        ${cell(entry.guess.affiliation, entry.comparison.affiliation, 'affiliation')}
                        ${cell(entry.guess.nationality, entry.comparison.nationality, 'nationality')}
                        ${cell(entry.guess.first_game, entry.comparison.first_game, 'first-game')}
                        ${cell(entry.guess.era, entry.comparison.era, 'era')}
                        ${cell(entry.guess.role_type, entry.comparison.role_type, 'role-type')}
                    </tr>
                `)
                .join('');

            emptyState.textContent = '';
            emptyState.classList.add('is-hidden');
            resultsWrap.classList.toggle('is-hidden', entries.length === 0);
        }

        attemptCount.textContent = String(entries.length);

        const latestEntry = entries.length > 0 ? entries[entries.length - 1] : null;
        const winningEntry = entries.find((entry) => entry.is_win) || null;
        const previewCharacter = findPreviewCharacter();

        updateCurrentGuessPanel(latestEntry, previewCharacter);
        updateTargetPanel(winningEntry);

        if (winningEntry) {
            showSearchPanel();
        }

        if (shouldAnimateNewest) {
            const revealDelay = animateNewestRow();
            setRevealLock(true);

            window.setTimeout(() => {
                setRevealLock(false);

                if (winningEntry) {
                    registerWinForStreak();
                    hideSearchPanel();
                    openWinModal(winningEntry);
                }
            }, revealDelay);
        } else if (winningEntry) {
            registerWinForStreak();
            hideSearchPanel();
        } else {
            showSearchPanel();
        }

        lastRenderedCount = entries.length;
    };

    suggestions.addEventListener('click', async (event) => {
        if (isRevealLocked) {
            return;
        }

        const button = event.target.closest('.suggestion-item');

        if (!button) {
            return;
        }

        input.value = button.dataset.name || '';
        await submitGuess();
    });

    input.addEventListener('input', () => {
        if (isRevealLocked) {
            return;
        }

        setError('');
        renderSuggestions();
        render();
    });

    input.addEventListener('focus', () => {
        if (isRevealLocked) {
            return;
        }

        renderSuggestions();
        render();
    });

    input.addEventListener('keydown', async (event) => {
        if (isRevealLocked) {
            event.preventDefault();
            return;
        }

        const buttons = getSuggestionButtons();

        if (event.key === 'ArrowDown' && buttons.length > 0) {
            event.preventDefault();
            const nextIndex = activeSuggestionIndex < buttons.length - 1 ? activeSuggestionIndex + 1 : 0;
            setActiveSuggestion(nextIndex);
            render();
            return;
        }

        if (event.key === 'ArrowUp' && buttons.length > 0) {
            event.preventDefault();
            const nextIndex = activeSuggestionIndex > 0 ? activeSuggestionIndex - 1 : buttons.length - 1;
            setActiveSuggestion(nextIndex);
            render();
            return;
        }

        if (event.key === 'Enter' && activeSuggestionIndex >= 0 && buttons[activeSuggestionIndex]) {
            event.preventDefault();
            input.value = buttons[activeSuggestionIndex].dataset.name || '';
            await submitGuess();
        }

        if (event.key === 'Escape') {
            closeSuggestions();
            render();
        }
    });

    document.addEventListener('click', (event) => {
        if (event.target === input || suggestions.contains(event.target)) {
            return;
        }

        if (event.target instanceof HTMLElement && event.target.dataset.closeModal === 'true') {
            closeWinModal();
        }

        if (event.target instanceof HTMLElement && event.target.dataset.closeShare === 'true') {
            closeSharedModal();
        }

        if (event.target instanceof HTMLElement && event.target.dataset.closeUpdates === 'true') {
            closeUpdatesModal();
        }

        if (event.target instanceof HTMLElement && event.target.dataset.closeSuggestion === 'true') {
            closeSuggestionModal();
        }

        if (event.target instanceof HTMLElement && event.target.closest('#updates-modal-button')) {
            openUpdatesModal();
        }

        if (event.target instanceof HTMLElement && event.target.closest('#suggestion-modal-button')) {
            openSuggestionModal();
        }

        closeSuggestions();
        render();
    });

    winModalShareButton.addEventListener('click', async () => {
        setShareStatus(nextChallengeShareStatus, '');
        await shareResults(winModalShareStatus);
    });

    nextChallengeShareButton.addEventListener('click', async () => {
        setShareStatus(winModalShareStatus, '');
        setShareStatus(headerShareStatus, '');
        await shareResults(nextChallengeShareStatus);
    });

    headerShareButton.addEventListener('click', async () => {
        setShareStatus(winModalShareStatus, '');
        setShareStatus(nextChallengeShareStatus, '');
        await shareChallenge(headerShareStatus);
    });

    sharedModalClose.addEventListener('click', () => {
        closeSharedModal();
    });

    sharedModalDismissButton.addEventListener('click', () => {
        closeSharedModal();
    });

    updatesModalClose.addEventListener('click', () => {
        closeUpdatesModal();
    });

    updatesModalDismissButton.addEventListener('click', () => {
        closeUpdatesModal();
    });

    suggestionModalClose.addEventListener('click', () => {
        closeSuggestionModal();
    });

    suggestionThanksToastClose.addEventListener('click', () => {
        hideSuggestionToast();
    });

    patchNotesToastClose.addEventListener('click', () => {
        hidePatchNotesToast();
    });

    patchNotesToastButton.addEventListener('click', () => {
        hidePatchNotesToast();
        openUpdatesModal();
    });

    suggestionForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        await submitSuggestion();
    });

    codecFrequencyToggle.addEventListener('change', () => {
        applyCodecPreference(codecFrequencyToggle.checked ? 'show' : 'hide');
    });

    codecPreferenceShowButton.addEventListener('click', () => {
        applyCodecPreference('show');
        closeCodecPreferenceModal();
    });

    codecPreferenceHideButton.addEventListener('click', () => {
        applyCodecPreference('hide');
        closeCodecPreferenceModal();
    });

    winModalClose.addEventListener('click', () => {
        closeWinModal();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeWinModal();
            closeSharedModal();
            closeUpdatesModal();
            closeSuggestionModal();
        }
    });

    form.addEventListener('submit', async (event) => {
        if (isRevealLocked) {
            event.preventDefault();
            return;
        }

        event.preventDefault();
        await submitGuess();
    });

    if (!refreshChallengeWindow()) {
        return;
    }

    lastRenderedCount = readStoredCount();
    updateDailySolvesCopy(Number(config.dailySolveCount || 0));
    updateStreakBadge();
    initializeCodecPreference();
    maybeShowSuggestionToast();
    maybeShowPatchNotesToast();
    scheduleDailyReset();
    updateNextChallengeTimer();
    runSignalSweep();
    window.setInterval(updateNextChallengeTimer, 1000);
    window.setInterval(runSignalSweep, 1500);
    render();
    maybeOpenSharedModal();
})();
