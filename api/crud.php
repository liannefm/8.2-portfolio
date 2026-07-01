<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

requireAuth();

try {
    $db = getDb();
    $action = $_POST['action'] ?? '';
    $pid = 1;

    switch ($action) {

        // ── PROFILE ──
        case 'update_profile':
            $stmt = $db->prepare("UPDATE profile SET bio_nl=?, bio_en=?, greeting_nl=?, greeting_en=? WHERE id=?");
            $stmt->execute([$_POST['bio_nl'], $_POST['bio_en'], $_POST['greeting_nl'] ?? '', $_POST['greeting_en'] ?? '', $pid]);
            if (!empty($_FILES['photo']['name'])) {
                $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $name = 'about-photo.' . $ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/../uploads/mood/' . $name);
                $db->prepare("UPDATE profile SET avatar_url=? WHERE id=?")->execute([$name, $pid]);
            }
            echo json_encode(['ok' => true]);
            break;

        // ── SKILLS ──
        case 'create_skill':
            $stmt = $db->prepare("INSERT INTO skills (profile_id,name,description_nl,description_en,color,sort_order) VALUES (?,?,?,?,?,?)");
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM skills WHERE profile_id=$pid")->fetchColumn();
            $stmt->execute([$pid, $_POST['name'], $_POST['description_nl'], $_POST['description_en'], $_POST['color'], $order]);
            echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            break;
        case 'update_skill':
            $stmt = $db->prepare("UPDATE skills SET name=?, description_nl=?, description_en=?, color=? WHERE id=? AND profile_id=?");
            $stmt->execute([$_POST['name'], $_POST['description_nl'], $_POST['description_en'], $_POST['color'], $_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;
        case 'delete_skill':
            $db->prepare("DELETE FROM skills WHERE id=? AND profile_id=?")->execute([$_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        // ── EDUCATION ──
        case 'create_education':
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM education WHERE profile_id=$pid")->fetchColumn();
            $stmt = $db->prepare("INSERT INTO education (profile_id,year_start,year_end,degree_nl,degree_en,organization,description_nl,description_en,sort_order) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$pid, $_POST['year_start'], $_POST['year_end'] ?: null, $_POST['degree_nl'], $_POST['degree_en'], $_POST['organization'], $_POST['description_nl'] ?? '', $_POST['description_en'] ?? '', $order]);
            echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            break;
        case 'update_education':
            $stmt = $db->prepare("UPDATE education SET year_start=?, year_end=?, degree_nl=?, degree_en=?, organization=?, description_nl=?, description_en=? WHERE id=? AND profile_id=?");
            $stmt->execute([$_POST['year_start'], $_POST['year_end'] ?: null, $_POST['degree_nl'], $_POST['degree_en'], $_POST['organization'], $_POST['description_nl'] ?? '', $_POST['description_en'] ?? '', $_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;
        case 'delete_education':
            $db->prepare("DELETE FROM education WHERE id=? AND profile_id=?")->execute([$_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        // ── EXPERIENCE ──
        case 'create_experience':
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM experience WHERE profile_id=$pid")->fetchColumn();
            $stmt = $db->prepare("INSERT INTO experience (profile_id,year_start,year_end,position_nl,position_en,company,description_nl,description_en,sort_order) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$pid, $_POST['year_start'], $_POST['year_end'] ?: null, $_POST['position_nl'], $_POST['position_en'], $_POST['company'], $_POST['description_nl'] ?? '', $_POST['description_en'] ?? '', $order]);
            echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            break;
        case 'update_experience':
            $stmt = $db->prepare("UPDATE experience SET year_start=?, year_end=?, position_nl=?, position_en=?, company=?, description_nl=?, description_en=? WHERE id=? AND profile_id=?");
            $stmt->execute([$_POST['year_start'], $_POST['year_end'] ?: null, $_POST['position_nl'], $_POST['position_en'], $_POST['company'], $_POST['description_nl'] ?? '', $_POST['description_en'] ?? '', $_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;
        case 'delete_experience':
            $db->prepare("DELETE FROM experience WHERE id=? AND profile_id=?")->execute([$_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        // ── LANGUAGES ──
        case 'create_language':
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM languages WHERE profile_id=$pid")->fetchColumn();
            $stmt = $db->prepare("INSERT INTO languages (profile_id,name,level_nl,level_en,proficiency,sort_order) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$pid, $_POST['name'], $_POST['level_nl'], $_POST['level_en'], $_POST['proficiency'], $order]);
            echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            break;
        case 'update_language':
            $stmt = $db->prepare("UPDATE languages SET name=?, level_nl=?, level_en=?, proficiency=? WHERE id=? AND profile_id=?");
            $stmt->execute([$_POST['name'], $_POST['level_nl'], $_POST['level_en'], $_POST['proficiency'], $_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;
        case 'delete_language':
            $db->prepare("DELETE FROM languages WHERE id=? AND profile_id=?")->execute([$_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        // ── CONTACT ──
        case 'create_contact':
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM contact_links WHERE profile_id=$pid")->fetchColumn();
            $stmt = $db->prepare("INSERT INTO contact_links (profile_id,platform,url,label,sort_order) VALUES (?,?,?,?,?)");
            $stmt->execute([$pid, $_POST['platform'], $_POST['url'], $_POST['label'], $order]);
            echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            break;
        case 'update_contact':
            $stmt = $db->prepare("UPDATE contact_links SET platform=?, url=?, label=? WHERE id=? AND profile_id=?");
            $stmt->execute([$_POST['platform'], $_POST['url'], $_POST['label'], $_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;
        case 'delete_contact':
            $db->prepare("DELETE FROM contact_links WHERE id=? AND profile_id=?")->execute([$_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        // ── MOOD ITEMS ──
        case 'create_mood':
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM mood_items WHERE profile_id=$pid")->fetchColumn();
            $type = $_POST['type'];
            $key = $_POST['key_name'];
            $imgUrl = null;
            if ($type === 'photo' && !empty($_FILES['image']['name'])) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imgUrl = $key . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/mood/' . $imgUrl);
            }
            $stmt = $db->prepare("INSERT INTO mood_items (profile_id,type,key_name,caption_nl,caption_en,image_url,sort_order) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$pid, $type, $key, $_POST['caption_nl'] ?? '', $_POST['caption_en'] ?? '', $imgUrl, $order]);
            echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            break;
        case 'update_mood_position':
            $stmt = $db->prepare("UPDATE mood_items SET pos_x=?, pos_y=? WHERE id=? AND profile_id=?");
            $stmt->execute([(float)$_POST['pos_x'], (float)$_POST['pos_y'], $_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        case 'update_mood':
            $stmt = $db->prepare("UPDATE mood_items SET caption_nl=?, caption_en=? WHERE id=? AND profile_id=?");
            $stmt->execute([$_POST['caption_nl'], $_POST['caption_en'], $_POST['id'], $pid]);
            if (!empty($_FILES['image']['name'])) {
                $row = $db->prepare("SELECT key_name FROM mood_items WHERE id=?");
                $row->execute([$_POST['id']]);
                $key = $row->fetchColumn();
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imgUrl = $key . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/mood/' . $imgUrl);
                $db->prepare("UPDATE mood_items SET image_url=? WHERE id=?")->execute([$imgUrl, $_POST['id']]);
            }
            echo json_encode(['ok' => true]);
            break;
        case 'delete_mood':
            $db->prepare("DELETE FROM mood_items WHERE id=? AND profile_id=?")->execute([$_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        // ── PERSONALITY FACTS ──
        case 'create_fact':
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM personality_facts WHERE profile_id=$pid")->fetchColumn();
            $stmt = $db->prepare("INSERT INTO personality_facts (profile_id,text_nl,text_en,sort_order) VALUES (?,?,?,?)");
            $stmt->execute([$pid, $_POST['text_nl'], $_POST['text_en'], $order]);
            echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            break;
        case 'update_fact':
            $db->prepare("UPDATE personality_facts SET text_nl=?, text_en=? WHERE id=? AND profile_id=?")->execute([$_POST['text_nl'], $_POST['text_en'], $_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;
        case 'delete_fact':
            $db->prepare("DELETE FROM personality_facts WHERE id=? AND profile_id=?")->execute([$_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        // ── PROJECTS ──
        case 'create_project':
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM projects WHERE profile_id=$pid")->fetchColumn();
            $stmt = $db->prepare("INSERT INTO projects (profile_id,title_nl,title_en,description_nl,description_en,date_nl,date_en,role_nl,role_en,type_nl,type_en,duration,intro_nl,intro_en,outcome_nl,outcome_en,live_url,source_url,video_url,sort_order) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$pid, $_POST['title_nl'], $_POST['title_en'], $_POST['description_nl'], $_POST['description_en'], $_POST['date_nl'] ?? '', $_POST['date_en'] ?? '', $_POST['role_nl'] ?? '', $_POST['role_en'] ?? '', $_POST['type_nl'] ?? '', $_POST['type_en'] ?? '', $_POST['duration'] ?? '', $_POST['intro_nl'] ?? '', $_POST['intro_en'] ?? '', $_POST['outcome_nl'] ?? '', $_POST['outcome_en'] ?? '', $_POST['live_url'] ?: null, $_POST['source_url'] ?: null, $_POST['video_url'] ?: null, $order]);
            $projId = $db->lastInsertId();
            if (!empty($_FILES['thumbnail']['name'])) {
                $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
                $fname = 'work-' . $projId . '.' . $ext;
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], __DIR__ . '/../uploads/projects/' . $fname);
                $db->prepare("UPDATE projects SET thumbnail_url=? WHERE id=?")->execute([$fname, $projId]);
            }
            $tags = array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')));
            foreach ($tags as $i => $tag) {
                $db->prepare("INSERT INTO project_tags (project_id,tag,sort_order) VALUES (?,?,?)")->execute([$projId, $tag, $i + 1]);
            }
            $hlNl = array_filter(explode("\n", trim($_POST['highlights_nl'] ?? '')));
            $hlEn = array_filter(explode("\n", trim($_POST['highlights_en'] ?? '')));
            foreach ($hlNl as $i => $hl) {
                $db->prepare("INSERT INTO project_highlights (project_id,text_nl,text_en,sort_order) VALUES (?,?,?,?)")->execute([$projId, trim($hl), trim($hlEn[$i] ?? $hl), $i + 1]);
            }
            echo json_encode(['ok' => true, 'id' => $projId]);
            break;

        case 'update_project':
            $id = $_POST['id'];
            $stmt = $db->prepare("UPDATE projects SET title_nl=?,title_en=?,description_nl=?,description_en=?,date_nl=?,date_en=?,role_nl=?,role_en=?,type_nl=?,type_en=?,duration=?,intro_nl=?,intro_en=?,outcome_nl=?,outcome_en=?,live_url=?,source_url=?,video_url=? WHERE id=? AND profile_id=?");
            $stmt->execute([$_POST['title_nl'], $_POST['title_en'], $_POST['description_nl'], $_POST['description_en'], $_POST['date_nl'] ?? '', $_POST['date_en'] ?? '', $_POST['role_nl'] ?? '', $_POST['role_en'] ?? '', $_POST['type_nl'] ?? '', $_POST['type_en'] ?? '', $_POST['duration'] ?? '', $_POST['intro_nl'] ?? '', $_POST['intro_en'] ?? '', $_POST['outcome_nl'] ?? '', $_POST['outcome_en'] ?? '', $_POST['live_url'] ?: null, $_POST['source_url'] ?: null, $_POST['video_url'] ?: null, $id, $pid]);
            if (!empty($_FILES['thumbnail']['name'])) {
                $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
                $fname = 'work-' . $id . '.' . $ext;
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], __DIR__ . '/../uploads/projects/' . $fname);
                $db->prepare("UPDATE projects SET thumbnail_url=? WHERE id=?")->execute([$fname, $id]);
            }
            $db->prepare("DELETE FROM project_tags WHERE project_id=?")->execute([$id]);
            $tags = array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')));
            foreach ($tags as $i => $tag) {
                $db->prepare("INSERT INTO project_tags (project_id,tag,sort_order) VALUES (?,?,?)")->execute([$id, $tag, $i + 1]);
            }
            $db->prepare("DELETE FROM project_highlights WHERE project_id=?")->execute([$id]);
            $hlNl = array_filter(explode("\n", trim($_POST['highlights_nl'] ?? '')));
            $hlEn = array_filter(explode("\n", trim($_POST['highlights_en'] ?? '')));
            foreach ($hlNl as $i => $hl) {
                $db->prepare("INSERT INTO project_highlights (project_id,text_nl,text_en,sort_order) VALUES (?,?,?,?)")->execute([$id, trim($hl), trim($hlEn[$i] ?? $hl), $i + 1]);
            }
            echo json_encode(['ok' => true]);
            break;

        case 'delete_project':
            $db->prepare("DELETE FROM projects WHERE id=? AND profile_id=?")->execute([$_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        // ── PROJECT MEDIA (foto's en code-screenshots) ──
        case 'create_project_media':
            $projectId = (int)$_POST['project_id'];
            // controleer dat het project van dit profiel is
            $owner = $db->prepare("SELECT id FROM projects WHERE id=? AND profile_id=?");
            $owner->execute([$projectId, $pid]);
            if (!$owner->fetchColumn()) {
                http_response_code(404);
                echo json_encode(['error' => 'Project niet gevonden']);
                break;
            }
            if (empty($_FILES['image']['name'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Geen afbeelding geüpload']);
                break;
            }
            $kind = ($_POST['kind'] ?? 'photo') === 'code' ? 'code' : 'photo';
            $order = $db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM project_media WHERE project_id=$projectId")->fetchColumn();
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fname = 'media-' . $projectId . '-' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/projects/' . $fname);
            $stmt = $db->prepare("INSERT INTO project_media (project_id,kind,image_url,caption_nl,caption_en,sort_order) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$projectId, $kind, $fname, $_POST['caption_nl'] ?? '', $_POST['caption_en'] ?? '', $order]);
            echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            break;

        case 'update_project_media':
            $stmt = $db->prepare("UPDATE project_media m JOIN projects p ON p.id = m.project_id SET m.caption_nl=?, m.caption_en=? WHERE m.id=? AND p.profile_id=?");
            $stmt->execute([$_POST['caption_nl'] ?? '', $_POST['caption_en'] ?? '', $_POST['id'], $pid]);
            echo json_encode(['ok' => true]);
            break;

        case 'delete_project_media':
            $row = $db->prepare("SELECT m.image_url FROM project_media m JOIN projects p ON p.id = m.project_id WHERE m.id=? AND p.profile_id=?");
            $row->execute([$_POST['id'], $pid]);
            $file = $row->fetchColumn();
            $db->prepare("DELETE m FROM project_media m JOIN projects p ON p.id = m.project_id WHERE m.id=? AND p.profile_id=?")->execute([$_POST['id'], $pid]);
            if ($file) { @unlink(__DIR__ . '/../uploads/projects/' . $file); }
            echo json_encode(['ok' => true]);
            break;

        // ── REORDER ──
        case 'reorder':
            $table = $_POST['table'] ?? '';
            $ids = json_decode($_POST['ids'] ?? '[]', true);
            $allowed = ['skills','languages','education','experience','contact_links','mood_items','personality_facts','projects'];
            if (!in_array($table, $allowed) || !is_array($ids)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid reorder']);
                break;
            }
            $stmt = $db->prepare("UPDATE `$table` SET sort_order=? WHERE id=? AND profile_id=?");
            foreach ($ids as $i => $id) {
                $stmt->execute([$i + 1, (int)$id, $pid]);
            }
            echo json_encode(['ok' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Unknown action: ' . $action]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
