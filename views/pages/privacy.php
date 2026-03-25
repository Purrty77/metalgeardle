<?php

declare(strict_types=1);
?>
<main class="page-shell page-shell--narrow">
    <section class="hero">
        <p class="eyebrow">Policy</p>
        <h1>Privacy Policy</h1>
        <p class="lede">A lightweight explanation of what Metal Gear Dle stores today and what should change if the project grows.</p>
    </section>

    <section class="panel policy-panel">
        <h2>Essential Local Storage</h2>
        <p>
            Metal Gear Dle stores your guesses in your browser so the current daily run feels consistent while you play.
            That information is kept on your device, is not meant to identify you personally, and is cleared when the daily
            challenge rolls over at 4:00 PM in the game&apos;s configured timezone.
        </p>
    </section>

    <section class="panel policy-panel">
        <h2>Cookies and Similar Technologies</h2>
        <p>
            The current version of the game is designed to stay simple. It does not depend on a heavy advertising stack to work.
            If cookies, analytics, ad measurement, or external monetization tools are introduced later, this page should be updated
            to explain what data is used, why it is collected, and what controls players have.
        </p>
    </section>

    <section class="panel policy-panel">
        <h2>Future Consent Controls</h2>
        <p>
            If Metal Gear Dle later adds personalized ads, analytics, or third-party services that track behavior across sessions,
            players should be able to revisit those choices from a clearly visible privacy or consent settings link.
        </p>
    </section>

    <section class="panel policy-panel">
        <h2>Contact and Project Links</h2>
        <p>
            For project updates, source code, or feedback, use the GitHub link in the footer. As the project evolves, this page can
            expand into a fuller policy with contact details, hosting information, and any third-party providers in use.
        </p>
        <p>
            <a href="<?= htmlspecialchars(rtrim((string) $baseUrl, '/') . '/', ENT_QUOTES, 'UTF-8') ?>">Back to today&apos;s challenge</a>
        </p>
    </section>
</main>
