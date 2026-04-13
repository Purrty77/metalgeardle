<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config;
use App\Core\View;
use App\Repositories\CharacterRepository;
use App\Repositories\DailyChallengeRepository;
use App\Repositories\SuggestionRepository;
use App\Services\GameService;
use Throwable;

final class GameController
{
    public function index(): void
    {
        $error = null;
        $challenge = null;
        $characters = [];
        $dailySolveCount = 0;
        $yesterdayCharacterName = null;
        $challengeCharacter = null;
        $baseUrl = (string) (Config::get('app', 'base_url') ?? '');
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = (string) ($_SERVER['HTTP_HOST'] ?? 'metalgeardle.com');
        $origin = $scheme . '://' . $host;
        $pageTitle = 'Metal Gear Dle | Daily Metal Gear Character Guessing Game';
        $metaDescription = 'Play Metal Gear Dle, a daily Metal Gear guessing game inspired by LoLdle. Compare character attributes, build your streak, and come back every day at 6 AM Paris time.';
        $ogImageUrl = $origin . ($baseUrl !== '' ? $baseUrl : '') . '/public/assets/img/social-card-home.png';

        try {
            $dailyChallenges = new DailyChallengeRepository();
            $charactersRepository = new CharacterRepository();
            $challenge = $dailyChallenges->ensureCurrent();
            $previousChallenge = $dailyChallenges->findPrevious();
            $characters = $charactersRepository->all();
            $challengeCharacter = $challenge !== null
                ? $charactersRepository->findById($challenge->characterId)
                : null;
            $dailySolveCount = $challenge !== null ? $dailyChallenges->countSolves($challenge->id) : 0;
            $yesterdayCharacterName = $previousChallenge !== null
                ? $charactersRepository->findById($previousChallenge->characterId)?->name
                : null;
        } catch (Throwable $exception) {
            $error = 'Database connection failed. Update your DB settings before playing.';
        }

        $sharedTries = filter_input(INPUT_GET, 'shared_tries', FILTER_VALIDATE_INT);
        $sharedDate = trim((string) ($_GET['shared_date'] ?? ''));
        $shareVersion = trim((string) ($_GET['share_v'] ?? ''));
        $shareNonce = trim((string) ($_GET['share_nonce'] ?? ''));

        if ($sharedTries !== false && $sharedTries !== null && $sharedTries > 0) {
            $triesLabel = $sharedTries === 1 ? '1 try' : sprintf('%d tries', $sharedTries);
            $missionLabel = "today's mission";

            if ($sharedDate !== '') {
                $sharedDateObject = date_create_immutable($sharedDate);

                if ($sharedDateObject instanceof \DateTimeImmutable) {
                    $missionLabel = sprintf(
                        'the %s mission',
                        $sharedDateObject->format('F j, Y')
                    );
                }
            }

            $pageTitle = sprintf('Metal Gear Dle | A Friend Cleared %s in %s', $missionLabel, $triesLabel);
            $metaDescription = sprintf(
                'A friend completed %s in %s. Open Metal Gear Dle and see if you can beat that score.',
                $missionLabel,
                $triesLabel
            );
            $ogImageUrl = sprintf(
                '%s%s/share-card?shared_tries=%d%s%s',
                
                $origin,
                $baseUrl !== '' ? $baseUrl : '',
                $sharedTries,
                $sharedDate !== '' ? '&shared_date=' . rawurlencode($sharedDate) : '',
                ($shareVersion !== '' ? '&share_v=' . rawurlencode($shareVersion) : '')
                . ($shareNonce !== '' ? '&share_nonce=' . rawurlencode($shareNonce) : '')
            );
        }

        View::render('game/index', [
            'title' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonicalUrl' => 'https://metalgeardle.com/',
            'metaRobots' => 'index,follow',
            'challenge' => $challenge,
            'characters' => $characters,
            'challengeCharacter' => $challengeCharacter,
            'dailySolveCount' => $dailySolveCount,
            'yesterdayCharacterName' => $yesterdayCharacterName,
            'error' => $error,
            'baseUrl' => $baseUrl,
            'ogImageUrl' => $ogImageUrl,
        ]);
    }

    public function shareCard(): void
    {
        $tries = filter_input(INPUT_GET, 'shared_tries', FILTER_VALIDATE_INT);
        $sharedDate = trim((string) ($_GET['shared_date'] ?? ''));
        $tries = ($tries !== false && $tries !== null && $tries > 0 && $tries < 1000) ? $tries : 0;

        $width = 1200;
        $height = 630;
        $image = imagecreatetruecolor($width, $height);

        if ($image === false) {
            http_response_code(500);
            return;
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        $bg = imagecolorallocate($image, 0, 0, 0);
        $bgCore = imagecolorallocate($image, 0, 0, 0);
        $line = imagecolorallocatealpha($image, 198, 205, 203, 110);
        $lineSoft = imagecolorallocatealpha($image, 198, 205, 203, 122);
        $scan = imagecolorallocatealpha($image, 198, 205, 203, 126);
        $text = imagecolorallocate($image, 213, 229, 225);
        $muted = imagecolorallocate($image, 146, 164, 157);
        $accent = imagecolorallocate($image, 228, 242, 238);
        $green = imagecolorallocate($image, 145, 241, 214);
        $red = imagecolorallocatealpha($image, 161, 51, 51, 88);

        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        for ($x = 0; $x <= $width; $x += 60) {
            imageline($image, $x, 0, $x, $height, $lineSoft);
        }

        for ($y = 0; $y <= $height; $y += 60) {
            imageline($image, 0, $y, $width, $y, $lineSoft);
        }

        for ($y = 0; $y <= $height; $y += 4) {
            imageline($image, 0, $y, $width, $y, $scan);
        }

        $panelLeft = 118;
        $panelTop = 104;
        $panelRight = 1082;
        $panelBottom = 526;

        imagefilledrectangle($image, $panelLeft, $panelTop, $panelRight, $panelBottom, $bgCore);
        imagerectangle($image, $panelLeft, $panelTop, $panelRight, $panelBottom, $line);

        imageline($image, 156, 166, 1044, 166, $lineSoft);
        imageline($image, 156, 454, 1044, 454, $lineSoft);

        $titleFontPath = dirname(__DIR__, 2) . '/public/assets/img/metal-gear-solid/METAG___.TTF';
        $titleFontAvailable = is_file($titleFontPath);

        if ($tries > 0) {
            $triesText = (string) $tries;
            $triesSuffix = $tries === 1 ? 'TRY' : 'TRIES';
        } else {
            $triesText = 'N/A';
            $triesSuffix = 'NO DATA';
        }

        $titleText = 'METAL GEAR DLE';
        $subText = 'FRIEND CLEAR';
        $dateText = $sharedDate !== '' ? sprintf('MISSION DATE // %s', $sharedDate) : 'DAILY MISSION LINK';
        $challengeText = 'CAN YOU BEAT THIS SCORE?';

        if ($titleFontAvailable) {
            $titleBox = imagettfbbox(28, 0, $titleFontPath, $titleText);
            $titleWidth = (int) abs($titleBox[2] - $titleBox[0]);
            $titleX = (int) (($width - $titleWidth) / 2);
            imagettftext($image, 28, 0, $titleX, 146, $muted, $titleFontPath, $titleText);
        } else {
            imagestring($image, 5, 500, 120, $titleText, $muted);
        }

        imagestring($image, 5, 168, 188, $subText, $accent);
        imagestring($image, 5, 168, 480, $dateText, $muted);
        imagestring($image, 5, 742, 480, $challengeText, $muted);

        $drawSegmentDigit = static function ($canvas, string $digit, int $x, int $y, int $w, int $h, int $thickness, int $onColor, int $offColor): void {
            $segments = [
                '0' => ['a', 'b', 'c', 'd', 'e', 'f'],
                '1' => ['b', 'c'],
                '2' => ['a', 'b', 'g', 'e', 'd'],
                '3' => ['a', 'b', 'g', 'c', 'd'],
                '4' => ['f', 'g', 'b', 'c'],
                '5' => ['a', 'f', 'g', 'c', 'd'],
                '6' => ['a', 'f', 'g', 'e', 'c', 'd'],
                '7' => ['a', 'b', 'c'],
                '8' => ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
                '9' => ['a', 'b', 'c', 'd', 'f', 'g'],
            ];

            $active = $segments[$digit] ?? [];
            $innerTop = $y + $thickness;
            $innerBottom = $y + $h - $thickness;
            $midY = $y + (int) floor($h / 2);

            $bars = [
                'a' => [$x + $thickness, $y, $x + $w - $thickness, $y + $thickness],
                'b' => [$x + $w - $thickness, $innerTop, $x + $w, $midY - (int) floor($thickness / 2)],
                'c' => [$x + $w - $thickness, $midY + (int) floor($thickness / 2), $x + $w, $innerBottom],
                'd' => [$x + $thickness, $y + $h - $thickness, $x + $w - $thickness, $y + $h],
                'e' => [$x, $midY + (int) floor($thickness / 2), $x + $thickness, $innerBottom],
                'f' => [$x, $innerTop, $x + $thickness, $midY - (int) floor($thickness / 2)],
                'g' => [$x + $thickness, $midY - (int) floor($thickness / 2), $x + $w - $thickness, $midY + (int) floor($thickness / 2)],
            ];

            foreach ($bars as $segment => [$x1, $y1, $x2, $y2]) {
                imagefilledrectangle($canvas, $x1, $y1, $x2, $y2, in_array($segment, $active, true) ? $onColor : $offColor);
            }
        };

        if (ctype_digit($triesText)) {
            $digitWidth = 74;
            $digitHeight = 150;
            $digitGap = 24;
            $digitThickness = 12;
            $digitCount = strlen($triesText);
            $totalWidth = ($digitCount * $digitWidth) + (($digitCount - 1) * $digitGap);
            $digitX = 716 + (int) floor((180 - $totalWidth) / 2);
            $digitY = 212;
            $digitOff = imagecolorallocatealpha($image, 145, 241, 214, 108);

            for ($i = 0; $i < $digitCount; $i++) {
                $drawSegmentDigit(
                    $image,
                    $triesText[$i],
                    $digitX + ($i * ($digitWidth + $digitGap)),
                    $digitY,
                    $digitWidth,
                    $digitHeight,
                    $digitThickness,
                    $green,
                    $digitOff
                );
            }

            if ($titleFontAvailable) {
                $suffixBox = imagettfbbox(44, 0, $titleFontPath, $triesSuffix);
                $suffixWidth = (int) abs($suffixBox[2] - $suffixBox[0]);
                $suffixX = 700 + (int) floor((180 - $suffixWidth) / 2);
                imagettftext($image, 44, 0, $suffixX, 430, $text, $titleFontPath, $triesSuffix);
            } else {
                imagestring($image, 5, 754, 412, $triesSuffix, $text);
            }
        } else {
            if ($titleFontAvailable) {
                $numberBox = imagettfbbox(94, 0, $titleFontPath, $triesText);
                $numberWidth = (int) abs($numberBox[2] - $numberBox[0]);
                $numberX = 716 + (int) floor((180 - $numberWidth) / 2);
                imagettftext($image, 94, 0, $numberX, 336, $green, $titleFontPath, $triesText);

                $suffixBox = imagettfbbox(34, 0, $titleFontPath, $triesSuffix);
                $suffixWidth = (int) abs($suffixBox[2] - $suffixBox[0]);
                $suffixX = 716 + (int) floor((180 - $suffixWidth) / 2);
                imagettftext($image, 34, 0, $suffixX, 420, $text, $titleFontPath, $triesSuffix);
            } else {
                imagestring($image, 5, 744, 286, $triesText, $green);
                imagestring($image, 5, 734, 380, $triesSuffix, $text);
            }
        }

        $miniPanelX = 166;
        $miniPanelY = 210;
        $miniPanelW = 320;
        $miniPanelH = 180;
        $miniRowH = 42;
        $miniThumb = imagecolorallocate($image, 54, 78, 65);
        $miniGreen = imagecolorallocatealpha($image, 84, 216, 111, 28);
        $miniAmber = imagecolorallocatealpha($image, 224, 156, 56, 44);
        $miniRed = imagecolorallocatealpha($image, 208, 74, 74, 36);
        $miniMask = imagecolorallocatealpha($image, 6, 10, 10, 22);

        imagerectangle($image, $miniPanelX, $miniPanelY, $miniPanelX + $miniPanelW, $miniPanelY + $miniPanelH, $lineSoft);
        imagestring($image, 3, $miniPanelX + 10, $miniPanelY + 8, 'LOG_STREAM_V212.DAT', $muted);

        $rowY = $miniPanelY + 34;
        for ($i = 0; $i < 3; $i++) {
            $fill = [$miniGreen, $miniAmber, $miniRed][$i];
            imagefilledrectangle($image, $miniPanelX + 10, $rowY, $miniPanelX + $miniPanelW - 10, $rowY + $miniRowH, $fill);
            imagerectangle($image, $miniPanelX + 10, $rowY, $miniPanelX + $miniPanelW - 10, $rowY + $miniRowH, $lineSoft);
            imagefilledrectangle($image, $miniPanelX + 18, $rowY + 8, $miniPanelX + 44, $rowY + 34, $miniThumb);
            imagefilledrectangle($image, $miniPanelX + 56, $rowY + 10, $miniPanelX + 140, $rowY + 18, $text);
            imagefilledrectangle($image, $miniPanelX + 56, $rowY + 24, $miniPanelX + 196, $rowY + 30, $muted);
            imagefilledrectangle($image, $miniPanelX + 210, $rowY + 10, $miniPanelX + 294, $rowY + 34, $miniMask);
            imagefilledrectangle($image, $miniPanelX + 224, $rowY + 14, $miniPanelX + 280, $rowY + 20, $text);
            imagefilledrectangle($image, $miniPanelX + 224, $rowY + 25, $miniPanelX + 268, $rowY + 30, $muted);
            $rowY += $miniRowH + 10;
        }

        imagefilledpolygon($image, [606, 280, 626, 300, 606, 320], $red);
        imagefilledpolygon($image, [954, 280, 934, 300, 954, 320], $red);

        header('Content-Type: image/png');
        header('Cache-Control: public, max-age=300');
        imagepng($image);
        imagedestroy($image);
    }

    public function privacy(): void
    {
        View::render('pages/privacy', [
            'title' => 'Privacy Policy | Metal Gear Dle',
            'metaDescription' => 'Read the privacy policy for Metal Gear Dle, including essential browser storage used for daily gameplay features.',
            'canonicalUrl' => 'https://metalgeardle.com/privacy-policy',
            'metaRobots' => 'index,follow',
            'baseUrl' => (string) (Config::get('app', 'base_url') ?? ''),
        ]);
    }

    public function guess(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $dailyChallenges = new DailyChallengeRepository();
            $characters = new CharacterRepository();
            $gameService = new GameService();
            $challenge = $dailyChallenges->ensureCurrent();
        } catch (Throwable $exception) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Database connection failed. Check your production database credentials.',
            ]);
            return;
        }

        if ($challenge === null) {
            http_response_code(404);
            echo json_encode([
                'error' => 'No daily challenge is available yet. Make sure the characters table has data.',
            ]);
            return;
        }

        $rawGuess = trim((string) ($_POST['guess'] ?? ''));
        $solverToken = trim((string) ($_POST['solver_token'] ?? ''));
        $guess = $characters->findByGuess($rawGuess);
        $solution = $characters->findById($challenge->characterId);

        if ($rawGuess === '' || $guess === null || $solution === null) {
            http_response_code(422);
            echo json_encode([
                'error' => 'Character not found. Try a valid Metal Gear character name or alias.',
            ]);
            return;
        }

        $isWin = $guess->id === $solution->id;
        $dailySolveCount = $dailyChallenges->countSolves($challenge->id);

        if ($isWin && $solverToken !== '') {
            $dailySolveCount = $dailyChallenges->registerSolve($challenge->id, $solverToken);
        }

        echo json_encode([
            'challenge_date' => $challenge->date,
            'guess' => $guess->toArray(),
            'comparison' => $gameService->compare($guess, $solution),
            'is_win' => $isWin,
            'daily_solves_count' => $dailySolveCount,
        ]);
    }

    public function suggestion(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $dailyChallenges = new DailyChallengeRepository();
            $suggestions = new SuggestionRepository();
            $suggestionDate = $dailyChallenges->currentDateKey();
        } catch (Throwable $exception) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Database connection failed. Unable to send the suggestion right now.',
            ]);
            return;
        }

        $suggestionToken = trim((string) ($_POST['suggestion_token'] ?? ''));
        $title = trim((string) ($_POST['title'] ?? ''));
        $body = trim((string) ($_POST['body'] ?? ''));

        if ($suggestionToken === '') {
            http_response_code(422);
            echo json_encode([
                'error' => 'Missing suggestion token. Refresh the page and try again.',
            ]);
            return;
        }

        if ($title === '' || $body === '') {
            http_response_code(422);
            echo json_encode([
                'error' => 'Please fill in both the title and the suggestion text.',
            ]);
            return;
        }

        if (mb_strlen($title) > 120) {
            http_response_code(422);
            echo json_encode([
                'error' => 'Keep the title under 120 characters.',
            ]);
            return;
        }

        if (mb_strlen($body) > 2000) {
            http_response_code(422);
            echo json_encode([
                'error' => 'Keep the suggestion text under 2000 characters.',
            ]);
            return;
        }

        if ($suggestions->hasSubmittedForDate($suggestionToken, $suggestionDate)) {
            http_response_code(429);
            echo json_encode([
                'error' => 'You already sent one suggestion for this daily cycle. Come back after the next reset.',
            ]);
            return;
        }

        try {
            $suggestions->create($suggestionToken, $suggestionDate, $title, $body);
        } catch (Throwable $exception) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Unable to save the suggestion right now.',
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Suggestion transmitted. Thanks for helping improve the mission.',
        ]);
    }
}
