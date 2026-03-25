(() => {
    const config = window.MetalGearDle;

    if (!config) {
        return;
    }

    const timezone = config.timezone || 'Europe/Paris';
    const dailyResetHour = Number(config.dailyResetHour || 16);
    const storagePrefix = 'metalgeardle:';
    const solverTokenKey = 'metalgeardle:solver-token';
    const streakKey = 'metalgeardle:streak';
    const searchPanel = document.querySelector('.panel-search');
    const nextChallengePanel = document.querySelector('#next-challenge-panel');
    const nextChallengeTimer = document.querySelector('#next-challenge-timer');
    const dailySolvesCopy = document.querySelector('#daily-solves-copy');
    const streakCount = document.querySelector('#streak-count');
    const form = document.querySelector('#guess-form');
    const input = document.querySelector('#guess');
    const suggestions = document.querySelector('#search-suggestions');
    const errorBox = document.querySelector('#guess-error');
    const attemptCount = document.querySelector('#attempt-count');
    const emptyState = document.querySelector('#empty-state');
    const resultsWrap = document.querySelector('#results-wrap');
    const resultsBody = document.querySelector('#results-body');
    const winModal = document.querySelector('#win-modal');
    const winModalImage = document.querySelector('#win-modal-image');
    const winModalName = document.querySelector('#win-modal-name');
    const winModalClose = document.querySelector('#win-modal-close');
    const directory = Array.isArray(config.characters) ? config.characters : [];
    let storageKey = `${storagePrefix}${config.challengeDate}`;
    let lastRenderedCount = 0;
    let activeSuggestionIndex = -1;

    if (!searchPanel || !nextChallengePanel || !nextChallengeTimer || !dailySolvesCopy || !streakCount || !form || !input || !suggestions || !errorBox || !attemptCount || !emptyState || !resultsWrap || !resultsBody || !winModal || !winModalImage || !winModalName || !winModalClose) {
        return;
    }

    const escapeHtml = (value) => String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

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

            if (key && key.startsWith(storagePrefix) && key !== activeKey) {
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

    const updateNextChallengeTimer = () => {
        const { nowWallClock, nextResetWallClock } = getNextResetDate();
        nextChallengeTimer.textContent = formatCountdown(nextResetWallClock - nowWallClock);
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

    const renderSuggestions = () => {
        const query = input.value.trim().toLowerCase();

        if (query.length === 0) {
            closeSuggestions();
            return;
        }

        const matches = directory
            .filter((character) => character.name.toLowerCase().includes(query))
            .slice(0, 6);

        openSuggestions(matches);
    };

    const cell = (value, status) => `<td class="status-${status}">${escapeHtml(value)}</td>`;
    const animationDuration = 720;
    const animationStagger = 520;

    const closeWinModal = () => {
        winModal.classList.add('is-hidden');
        winModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
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
        winModal.classList.remove('is-hidden');
        winModal.classList.add('is-entering');
        winModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                winModal.classList.remove('is-entering');
            });
        });
    };

    const animateNewestRow = () => {
        const newestRow = resultsBody.querySelector('tr');

        if (!newestRow) {
            return;
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
    };

    const submitGuess = async () => {
        if (!refreshChallengeWindow()) {
            return;
        }

        setError('');
        closeSuggestions();

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
                    ${cell(entry.guess.gender, entry.comparison.gender)}
                    ${cell(entry.guess.affiliation, entry.comparison.affiliation)}
                    ${cell(entry.guess.nationality, entry.comparison.nationality)}
                    ${cell(entry.guess.first_game, entry.comparison.first_game)}
                    ${cell(entry.guess.era, entry.comparison.era)}
                    ${cell(entry.guess.role_type, entry.comparison.role_type)}
                </tr>
            `)
            .join('');

        attemptCount.textContent = String(entries.length);
        emptyState.classList.toggle('is-hidden', entries.length > 0);
        resultsWrap.classList.toggle('is-hidden', entries.length === 0);

        const winningEntry = entries.find((entry) => entry.is_win) || null;

        if (winningEntry) {
            showSearchPanel();
        }

        if (shouldAnimateNewest) {
            animateNewestRow();

            if (winningEntry) {
                const revealDelay = ((7 - 1) * animationStagger) + animationDuration;
                window.setTimeout(() => {
                    registerWinForStreak();
                    hideSearchPanel();
                    openWinModal(winningEntry);
                }, revealDelay);
            }
        } else if (winningEntry) {
            registerWinForStreak();
            hideSearchPanel();
        } else {
            showSearchPanel();
        }

        lastRenderedCount = entries.length;
    };

    suggestions.addEventListener('click', async (event) => {
        const button = event.target.closest('.suggestion-item');

        if (!button) {
            return;
        }

        input.value = button.dataset.name || '';
        await submitGuess();
    });

    input.addEventListener('input', () => {
        setError('');
        renderSuggestions();
    });

    input.addEventListener('focus', () => {
        renderSuggestions();
    });

    input.addEventListener('keydown', async (event) => {
        const buttons = getSuggestionButtons();

        if (event.key === 'ArrowDown' && buttons.length > 0) {
            event.preventDefault();
            const nextIndex = activeSuggestionIndex < buttons.length - 1 ? activeSuggestionIndex + 1 : 0;
            setActiveSuggestion(nextIndex);
            return;
        }

        if (event.key === 'ArrowUp' && buttons.length > 0) {
            event.preventDefault();
            const nextIndex = activeSuggestionIndex > 0 ? activeSuggestionIndex - 1 : buttons.length - 1;
            setActiveSuggestion(nextIndex);
            return;
        }

        if (event.key === 'Enter' && activeSuggestionIndex >= 0 && buttons[activeSuggestionIndex]) {
            event.preventDefault();
            input.value = buttons[activeSuggestionIndex].dataset.name || '';
            await submitGuess();
        }

        if (event.key === 'Escape') {
            closeSuggestions();
        }
    });

    document.addEventListener('click', (event) => {
        if (event.target === input || suggestions.contains(event.target)) {
            return;
        }

        if (event.target instanceof HTMLElement && event.target.dataset.closeModal === 'true') {
            closeWinModal();
        }

        closeSuggestions();
    });

    winModalClose.addEventListener('click', () => {
        closeWinModal();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeWinModal();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        await submitGuess();
    });

    if (!refreshChallengeWindow()) {
        return;
    }

    lastRenderedCount = readStoredCount();
    updateDailySolvesCopy(Number(config.dailySolveCount || 0));
    updateStreakBadge();
    scheduleDailyReset();
    updateNextChallengeTimer();
    window.setInterval(updateNextChallengeTimer, 1000);
    render();
})();
