<?php
header('Content-Type: application/json');

$leaderboardFile = __DIR__ . '/data/leaderboard.json';
$maxEntries = 10;

function getLeaderboard($file) {
    if (!file_exists($file)) {
        return [];
    }
    $data = file_get_contents($file);
    return json_decode($data, true) ?: [];
}

function saveLeaderboard($file, $data) {
    $fp = fopen($file, 'w');
    if (flock($fp, LOCK_EX)) {
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

function sanitizeName($name) {
    $name = strtoupper(preg_replace('/[^A-Za-z]/', '', $name));
    return substr($name, 0, 3);
}

// GET - retrieve leaderboard
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $leaderboard = getLeaderboard($leaderboardFile);
    echo json_encode(['success' => true, 'leaderboard' => $leaderboard]);
    exit;
}

// POST - submit score
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['name']) || !isset($input['score']) || !isset($input['level'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    $name = sanitizeName($input['name']);
    $score = intval($input['score']);
    $level = intval($input['level']);

    if (strlen($name) !== 3) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Name must be 3 letters']);
        exit;
    }

    if ($score < 0 || $score > 999999) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid score']);
        exit;
    }

    $leaderboard = getLeaderboard($leaderboardFile);

    // Add new entry
    $newEntry = [
        'name' => $name,
        'score' => $score,
        'level' => $level,
        'date' => date('Y-m-d')
    ];

    $leaderboard[] = $newEntry;

    // Sort by score descending
    usort($leaderboard, function($a, $b) {
        return $b['score'] - $a['score'];
    });

    // Keep only top entries
    $leaderboard = array_slice($leaderboard, 0, $maxEntries);

    saveLeaderboard($leaderboardFile, $leaderboard);

    // Find rank of submitted score
    $rank = -1;
    foreach ($leaderboard as $i => $entry) {
        if ($entry['name'] === $name && $entry['score'] === $score) {
            $rank = $i + 1;
            break;
        }
    }

    echo json_encode([
        'success' => true,
        'rank' => $rank,
        'leaderboard' => $leaderboard
    ]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);
