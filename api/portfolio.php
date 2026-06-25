<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/config.php';

try {
    $db = getDb();
    $profileId = 1;

    $profile = $db->query("SELECT * FROM profile WHERE id = $profileId")->fetch();
    if (!$profile) {
        http_response_code(404);
        echo json_encode(['error' => 'Profile not found']);
        exit;
    }

    $skills = $db->query("SELECT * FROM skills WHERE profile_id = $profileId ORDER BY sort_order")->fetchAll();

    $languages = $db->query("SELECT * FROM languages WHERE profile_id = $profileId ORDER BY sort_order")->fetchAll();

    $education = $db->query("SELECT * FROM education WHERE profile_id = $profileId ORDER BY sort_order")->fetchAll();

    $experience = $db->query("SELECT * FROM experience WHERE profile_id = $profileId ORDER BY sort_order")->fetchAll();

    $contacts = $db->query("SELECT * FROM contact_links WHERE profile_id = $profileId ORDER BY sort_order")->fetchAll();

    $projects = $db->query("SELECT * FROM projects WHERE profile_id = $profileId ORDER BY sort_order")->fetchAll();

    foreach ($projects as &$project) {
        $pid = $project['id'];
        $project['tags'] = $db->query("SELECT tag FROM project_tags WHERE project_id = $pid ORDER BY sort_order")->fetchAll(PDO::FETCH_COLUMN);
        $project['highlights'] = $db->query("SELECT text_nl, text_en FROM project_highlights WHERE project_id = $pid ORDER BY sort_order")->fetchAll();
    }
    unset($project);

    $moodItems = $db->query("SELECT * FROM mood_items WHERE profile_id = $profileId ORDER BY sort_order")->fetchAll();

    $facts = $db->query("SELECT * FROM personality_facts WHERE profile_id = $profileId ORDER BY sort_order")->fetchAll();

    echo json_encode([
        'profile'    => $profile,
        'skills'     => $skills,
        'languages'  => $languages,
        'education'  => $education,
        'experience' => $experience,
        'contacts'   => $contacts,
        'projects'   => $projects,
        'moodItems'  => $moodItems,
        'facts'      => $facts,
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
