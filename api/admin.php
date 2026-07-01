<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

// Handle login/logout
if (isset($_POST['admin_login'])) {
    if (attemptLogin($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: admin.php');
        exit;
    }
    $loginError = true;
}
if (isset($_GET['logout'])) {
    logout();
    header('Location: admin.php?login=1');
    exit;
}

// Show login page if not authenticated
if (!isLoggedIn()) {
    showLoginPage($loginError ?? false);
    exit;
}

$db = getDb();
$pid = 1;

$profile = $db->query("SELECT * FROM profile WHERE id = $pid")->fetch();
$skills = $db->query("SELECT * FROM skills WHERE profile_id = $pid ORDER BY sort_order")->fetchAll();
$languages = $db->query("SELECT * FROM languages WHERE profile_id = $pid ORDER BY sort_order")->fetchAll();
$education = $db->query("SELECT * FROM education WHERE profile_id = $pid ORDER BY sort_order")->fetchAll();
$experience = $db->query("SELECT * FROM experience WHERE profile_id = $pid ORDER BY sort_order")->fetchAll();
$contacts = $db->query("SELECT * FROM contact_links WHERE profile_id = $pid ORDER BY sort_order")->fetchAll();
$moodItems = $db->query("SELECT * FROM mood_items WHERE profile_id = $pid ORDER BY sort_order")->fetchAll();
$facts = $db->query("SELECT * FROM personality_facts WHERE profile_id = $pid ORDER BY sort_order")->fetchAll();
$projects = $db->query("SELECT * FROM projects WHERE profile_id = $pid ORDER BY sort_order")->fetchAll();
foreach ($projects as &$p) {
    $p['tags'] = $db->query("SELECT tag FROM project_tags WHERE project_id = {$p['id']} ORDER BY sort_order")->fetchAll(PDO::FETCH_COLUMN);
    $p['highlights'] = $db->query("SELECT * FROM project_highlights WHERE project_id = {$p['id']} ORDER BY sort_order")->fetchAll();
    $p['media'] = $db->query("SELECT * FROM project_media WHERE project_id = {$p['id']} ORDER BY sort_order")->fetchAll();
}
unset($p);

$TAB_COLORS = [
  'about' => 'var(--pink)', 'personal' => 'var(--pink)', 'works' => 'var(--lime)',
  'skills' => 'var(--yellow)', 'education' => 'var(--blue)', 'experience' => 'var(--pink)',
  'languages' => 'var(--lime)', 'contact' => 'var(--blue)',
];

$PLATFORM_META = [
  'email' => ['icon' => '@', 'bg' => 'var(--pink)'],
  'linkedin' => ['icon' => 'in', 'bg' => 'var(--blue)'],
  'github' => ['icon' => '</>', 'bg' => 'var(--ink)'],
  'instagram' => ['icon' => 'ig', 'bg' => 'var(--lime)', 'color' => 'var(--ink)'],
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LianneOS — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@700;900&family=DM+Sans:wght@400;500;700&family=Space+Mono:wght@400;700&family=Caveat:wght@500&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --navy:#161d7a;--navy-2:#101663;--navy-deep:#0a0f47;--ink:#10122e;
  --cream:#f5f1e6;--cream-2:#e9e3d1;--cream-3:#dcd4bd;
  --pink:#ff2e97;--pink-deep:#d6157a;--yellow:#ffe01a;--lime:#aef23a;--blue:#3b6cff;
  --muted:#5a5d77;
  --font-display:'Archivo',sans-serif;--font-body:'DM Sans',sans-serif;--font-mono:'Space Mono',monospace;
  --shadow-hard:7px 7px 0 rgba(8,10,40,.45);--shadow-soft:0 18px 40px -12px rgba(8,10,40,.7);
}
body{
  background:
    radial-gradient(120% 90% at 85% -10%, #28309e 0%, rgba(40,48,158,0) 55%),
    radial-gradient(90% 80% at 10% 110%, #1c2492 0%, rgba(28,36,146,0) 55%),
    var(--navy);
  color:var(--cream);font-family:var(--font-body);min-height:100vh;
}
body::before{
  content:"";position:fixed;inset:0;pointer-events:none;z-index:0;
  background-image:radial-gradient(rgba(255,255,255,.07) 1.5px,transparent 1.5px);
  background-size:26px 26px;opacity:.9;
}

/* ═══ LAYOUT ═══ */
.admin{position:relative;z-index:1;max-width:920px;margin:0 auto;padding:28px 20px 60px}

/* ═══ TASKBAR ═══ */
.admin-bar{
  display:flex;align-items:center;gap:10px;padding:0 14px;height:48px;
  background:var(--navy-deep);border:2.5px solid #000;border-radius:10px;
  box-shadow:0 4px 16px rgba(0,0,0,.35);margin-bottom:28px;
}
.admin-bar .start{
  display:flex;align-items:center;gap:9px;height:34px;padding:0 14px 0 10px;
  background:var(--pink);color:#fff;border:2.5px solid var(--ink);
  border-radius:8px;box-shadow:2px 2px 0 rgba(0,0,0,.4);
  font-family:var(--font-display);font-weight:800;font-size:14px;letter-spacing:.01em;cursor:default;
}
.admin-bar .start .gem{width:12px;height:12px;background:var(--yellow);border:2px solid var(--ink);transform:rotate(45deg)}
.admin-bar .spacer{flex:1}
.admin-bar .clock{
  font-family:var(--font-mono);font-weight:700;font-size:12px;color:var(--cream);
  background:var(--navy);border:2px solid rgba(255,255,255,.18);border-radius:7px;
  padding:6px 12px;line-height:1;text-align:center;
}
.admin-bar .clock small{display:block;font-size:9px;color:rgba(255,255,255,.55);margin-top:2px}
.btn-logout{
  font-family:var(--font-mono);font-weight:700;font-size:11px;color:var(--cream);
  background:none;border:2px solid rgba(255,255,255,.18);border-radius:7px;
  padding:6px 12px;text-decoration:none;transition:all .15s;
}
.btn-logout:hover{background:var(--pink);border-color:var(--pink);color:#fff}

/* ═══ WINDOW ═══ */
.window{
  background:var(--cream);border:2.5px solid var(--ink);
  box-shadow:var(--shadow-hard),var(--shadow-soft);border-radius:10px;overflow:hidden;
}
.titlebar{
  height:38px;display:flex;align-items:center;gap:10px;
  padding:0 12px;background:var(--ink);color:var(--cream);user-select:none;
}
.titlebar .dot{width:11px;height:11px;border-radius:50%;background:var(--acc,var(--pink));flex:0 0 auto;border:1.5px solid rgba(255,255,255,.5)}
.titlebar .path{font-family:var(--font-mono);font-size:12.5px;font-weight:700;letter-spacing:.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex:1 1 auto}
.titlebar .win-controls{display:flex;gap:6px;flex:0 0 auto}
.titlebar .win-controls button{
  width:18px;height:18px;border:1.5px solid var(--cream);background:transparent;
  border-radius:3px;color:var(--cream);display:flex;align-items:center;justify-content:center;
  font-size:11px;line-height:1;padding:0;cursor:pointer;
}
.titlebar .win-controls button:hover{background:var(--cream);color:var(--ink)}

/* tabs */
.tabs{display:flex;gap:6px;padding:9px 10px 0;background:var(--cream);overflow-x:auto;scrollbar-width:none}
.tabs::-webkit-scrollbar{display:none}
.tab-btn{
  flex:0 0 auto;font-family:var(--font-mono);font-weight:700;font-size:12px;
  padding:6px 13px;border:2.5px solid var(--ink);border-bottom:none;
  border-radius:9px 9px 0 0;background:var(--cream-2);color:var(--ink);
  transform:translateY(2.5px);white-space:nowrap;cursor:pointer;
}
.tab-btn:hover{background:var(--cream-3)}
.tab-btn.active{background:var(--tab-acc,var(--pink));color:#fff;transform:translateY(0);padding-bottom:8px}

/* win body */
.win-body{overflow:auto;background:var(--cream);border-top:2.5px solid var(--ink);padding:24px}
.win-body::-webkit-scrollbar{width:12px}
.win-body::-webkit-scrollbar-thumb{background:var(--cream-3);border:2px solid var(--cream);border-radius:8px}

/* ═══ SECTION HEADERS ═══ */
.panel{display:none}
.panel.active{display:block}
.eyebrow{
  font-family:var(--font-mono);font-weight:700;font-size:12px;letter-spacing:.14em;
  text-transform:uppercase;color:var(--pink-deep);margin:0 0 8px;
}
.sec-title{
  font-family:var(--font-display);font-weight:900;font-size:clamp(26px,4vw,38px);
  line-height:.95;letter-spacing:-.02em;margin:0 0 14px;color:var(--ink);
}
.sec-title .hl{color:var(--pink)}
.lead{font-size:16px;line-height:1.6;color:#2a2c44;margin:0 0 18px;max-width:52ch}

/* ═══ FORMS ═══ */
.field{margin-bottom:16px}
.field label{display:block;font-family:var(--font-mono);font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-bottom:5px}
.field input,.field textarea,.field select{
  width:100%;padding:10px 12px;border:2.5px solid var(--cream-3);border-radius:6px;
  font-family:var(--font-body);font-size:14px;background:#fff;color:var(--ink);transition:border-color .15s;
}
.field input:focus,.field textarea:focus,.field select:focus{outline:none;border-color:var(--pink)}
.field textarea{resize:vertical;min-height:80px}
.dual{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.dual .field:first-child label::after{content:' NL';font-size:9px;opacity:.5}
.dual .field:last-child label::after{content:' EN';font-size:9px;opacity:.5}
.triple{display:grid;grid-template-columns:1fr 1fr 2fr;gap:12px}

/* ═══ BUTTONS ═══ */
.btn{
  font-family:var(--font-mono);font-size:12px;font-weight:700;padding:8px 16px;
  border:2.5px solid var(--ink);border-radius:8px;cursor:pointer;transition:all .12s;
}
.btn-pink{background:var(--pink);color:#fff;box-shadow:var(--shadow-hard)}
.btn-pink:hover{background:var(--pink-deep);transform:translate(2px,2px);box-shadow:none}
.btn-lime{background:var(--lime);color:var(--ink);box-shadow:3px 3px 0 rgba(8,10,40,.3)}
.btn-lime:hover{transform:translate(1px,1px);box-shadow:none}
.btn-yellow{background:var(--yellow);color:var(--ink);box-shadow:3px 3px 0 rgba(8,10,40,.3)}
.btn-yellow:hover{transform:translate(1px,1px);box-shadow:none}
.btn-del{
  background:none;border:2px solid var(--cream-3);color:var(--muted);font-size:11px;
  padding:4px 10px;border-radius:6px;cursor:pointer;font-family:var(--font-mono);font-weight:700;
}
.btn-del:hover{background:var(--pink);border-color:var(--pink);color:#fff}
.btn-add{
  background:var(--cream);border:2.5px dashed var(--cream-3);color:var(--muted);
  width:100%;padding:14px;border-radius:12px;cursor:pointer;
  font-family:var(--font-mono);font-size:12px;font-weight:700;margin-top:10px;transition:all .15s;
}
.btn-add:hover{border-color:var(--pink);color:var(--pink);background:#fff}

/* ═══ COLOR PICKER ═══ */
.color-opts{display:flex;gap:8px;margin-top:4px}
.color-opt{width:28px;height:28px;border-radius:50%;border:3px solid transparent;cursor:pointer;transition:all .12s}
.color-opt:hover,.color-opt.active{border-color:var(--ink);transform:scale(1.15)}

/* ═══ PROFICIENCY DOTS ═══ */
.prof-dots{display:flex;gap:6px;margin-top:4px}
.prof-dot{width:16px;height:16px;border-radius:50%;border:2.5px solid var(--ink);background:var(--cream);cursor:pointer;transition:all .12s}
.prof-dot.filled{background:var(--pink)}

/* ═══ FILE UPLOAD ═══ */
.file-area{
  border:2.5px dashed var(--cream-3);border-radius:10px;padding:18px;
  text-align:center;cursor:pointer;transition:all .15s;position:relative;
}
.file-area:hover{border-color:var(--pink);background:rgba(255,46,151,.04)}
.file-area input{position:absolute;inset:0;opacity:0;cursor:pointer}
.file-area p{font-family:var(--font-mono);font-size:11px;color:var(--muted)}

/* ═══ CHIPS ═══ */
.chip-row{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}
.chip{
  font-family:var(--font-mono);font-weight:700;font-size:11px;padding:4px 10px;
  border:2.5px solid var(--ink);border-radius:20px;background:var(--cream);color:var(--ink);
}
.chip.fill-pink{background:var(--pink);color:#fff}
.chip.fill-blue{background:var(--blue);color:#fff}
.chip.fill-yellow{background:var(--yellow)}
.chip.fill-lime{background:var(--lime)}

/* ═══ EDIT OVERLAY ═══ */
.edit-overlay{
  display:none;position:fixed;inset:0;background:rgba(10,15,71,.7);z-index:100;
  align-items:center;justify-content:center;
}
.edit-overlay.open{display:flex}
.edit-modal{
  background:var(--cream);border:2.5px solid var(--ink);border-radius:12px;
  box-shadow:var(--shadow-hard),var(--shadow-soft);padding:24px;width:90%;max-width:500px;
  max-height:90vh;overflow-y:auto;
}
.edit-modal h3{font-family:var(--font-display);font-weight:800;font-size:17px;color:var(--ink);margin:0 0 16px}

/* ═══ INLINE EDIT CARD ═══ */
.edit-form{display:none;margin-top:14px;padding-top:14px;border-top:2px dashed var(--cream-3)}
.edit-form.open{display:block}

/* ═══ SKILL GRID (portfolio-stijl) ═══ */
.skill-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:4px}
.skill-card{
  border:2.5px solid var(--ink);border-radius:12px;background:var(--cream);
  box-shadow:var(--shadow-hard);padding:14px 16px 15px;
  display:flex;flex-direction:column;gap:8px;transition:transform .12s ease,box-shadow .12s ease;
  position:relative;cursor:pointer;
}
.skill-card:hover{transform:translate(-1px,-2px);box-shadow:9px 9px 0 rgba(8,10,40,.45)}
.skill-card-top{display:flex;align-items:center;gap:10px}
.skill-card .sq{width:18px;height:18px;border:2.5px solid var(--ink);border-radius:4px;background:var(--bc,var(--pink));flex:none}
.skill-card-name{font-family:var(--font-display);font-weight:800;font-size:20px}
.skill-card-use{margin:0;font-size:13.5px;line-height:1.5;color:#3a3c54}
.skill-card .btn-del{position:absolute;top:10px;right:10px;opacity:0;transition:opacity .15s}
.skill-card:hover .btn-del{opacity:1}

/* ═══ TIMELINE (portfolio-stijl) ═══ */
.timeline{position:relative;padding-left:26px}
.timeline::before{content:"";position:absolute;left:6px;top:4px;bottom:4px;width:3px;background:var(--ink);border-radius:2px}
.tl-item{position:relative;padding:0 0 22px;cursor:pointer}
.tl-item::before{
  content:"";position:absolute;left:-26px;top:3px;width:15px;height:15px;border-radius:50%;
  background:var(--pink);border:2.5px solid var(--ink);
}
.tl-item .yr{font-family:var(--font-mono);font-weight:700;font-size:12px;color:var(--pink-deep);letter-spacing:.05em}
.tl-item h3{margin:3px 0 2px;font-family:var(--font-display);font-weight:800;font-size:19px;color:var(--ink)}
.tl-item .org{font-size:14px;font-weight:600;color:#3a3c54}
.tl-item p{margin:6px 0 0;font-size:13.5px;line-height:1.5;color:#42445c}
.tl-item .btn-del{position:absolute;top:0;right:0;opacity:0;transition:opacity .15s}
.tl-item:hover .btn-del{opacity:1}

/* ═══ LANGUAGE CARDS (portfolio-stijl) ═══ */
.lang-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:4px}
.lang-card{
  border:2.5px solid var(--ink);border-radius:12px;background:var(--cream);
  box-shadow:var(--shadow-hard);padding:16px 18px 17px;
  display:flex;flex-direction:column;gap:11px;transition:transform .12s ease,box-shadow .12s ease;
  position:relative;cursor:pointer;
}
.lang-card:hover{transform:translate(-1px,-2px);box-shadow:9px 9px 0 rgba(8,10,40,.45)}
.lang-card-top{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.lang-card .sq{width:18px;height:18px;border:2.5px solid var(--ink);border-radius:4px;background:var(--bc,var(--pink));flex:none}
.lang-card-name{font-family:var(--font-display);font-weight:800;font-size:21px}
.lang-card-level{
  margin-left:auto;font-family:var(--font-mono);font-weight:700;font-size:11px;
  letter-spacing:.04em;text-transform:uppercase;color:var(--ink);
  border:2px solid var(--ink);border-radius:999px;padding:3px 9px;background:var(--bc,var(--pink));
}
.dots{display:flex;gap:5px}
.dots i{width:11px;height:11px;border:2px solid var(--ink);border-radius:50%;background:var(--cream);font-style:normal}
.dots i.on{background:var(--pink)}
.lang-card .btn-del{position:absolute;top:10px;right:10px;opacity:0;transition:opacity .15s}
.lang-card:hover .btn-del{opacity:1}

/* ═══ CONTACT ROWS (portfolio-stijl) ═══ */
.contact-list{display:flex;flex-direction:column;gap:12px}
.contact-row{
  display:flex;align-items:center;gap:14px;padding:14px 16px;border:2.5px solid var(--ink);
  border-radius:10px;background:var(--cream);box-shadow:3px 3px 0 rgba(8,10,40,.28);
  text-decoration:none;color:var(--ink);cursor:pointer;position:relative;
  transition:transform .12s,box-shadow .12s;
}
.contact-row:hover{background:var(--yellow);transform:translate(-1px,-1px);box-shadow:5px 5px 0 rgba(8,10,40,.35)}
.contact-row .ci{
  width:40px;height:40px;flex:0 0 auto;border:2.5px solid var(--ink);border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  font-family:var(--font-mono);font-weight:700;font-size:13px;color:#fff;
}
.contact-row .ct b{display:block;font-family:var(--font-display);font-weight:800;font-size:15px}
.contact-row .ct span{font-family:var(--font-mono);font-size:13px;color:var(--muted)}
.contact-row .btn-del{position:absolute;top:10px;right:10px;opacity:0;transition:opacity .15s}
.contact-row:hover .btn-del{opacity:1}

/* ═══ WORK CARDS (portfolio-stijl) ═══ */
.work-timeline .work-item{padding-bottom:26px}
.work-card{
  margin-top:6px;display:flex;gap:14px;border:2.5px solid var(--ink);border-radius:12px;
  background:var(--cream);box-shadow:var(--shadow-hard);overflow:hidden;cursor:pointer;
  transition:transform .12s,box-shadow .12s;
}
.work-card:hover{transform:translate(-1px,-2px);box-shadow:9px 9px 0 rgba(8,10,40,.45)}
.work-shot{
  flex:0 0 150px;align-self:stretch;min-height:120px;border-right:2.5px solid var(--ink);position:relative;
  background:repeating-linear-gradient(45deg,var(--cream-2) 0 12px,var(--cream-3) 12px 24px);
  display:flex;align-items:center;justify-content:center;padding:10px;
}
.work-shot img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.work-meta{padding:14px 16px 15px;display:flex;flex-direction:column;gap:8px;flex:1 1 auto}
.work-meta h3{margin:0;font-family:var(--font-display);font-weight:800;font-size:20px;color:var(--ink)}
.work-meta p{margin:0;font-size:13.5px;line-height:1.5;color:#3a3c54;flex:1 1 auto}
.tagrow{display:flex;flex-wrap:wrap;gap:6px}
.tagrow span{font-family:var(--font-mono);font-weight:700;font-size:10.5px;padding:3px 8px;border:2px solid var(--ink);border-radius:6px;background:var(--cream)}

/* project edit toggle */
.proj-edit{display:none;margin-top:14px;padding:18px;background:var(--cream);border:2.5px solid var(--ink);border-radius:12px;box-shadow:var(--shadow-hard)}
.proj-edit.open{display:block}

/* project media-beheer */
.media-mgr{margin-top:18px;padding-top:16px;border-top:2px dashed rgba(16,18,46,.25)}
.media-mgr h4{font-family:var(--font-display);font-weight:800;font-size:15px;margin:0 0 4px}
.media-hint{font-family:var(--font-mono);font-size:11px;color:var(--muted);margin:0 0 12px;line-height:1.5}
.media-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;margin-bottom:16px}
.media-card{position:relative;border:2.5px solid var(--ink);border-radius:10px;background:#fff;padding:8px;box-shadow:2px 2px 0 rgba(8,10,40,.3)}
.media-card img{width:100%;aspect-ratio:16/10;object-fit:cover;border-radius:6px;border:2px solid var(--ink);display:block;margin-bottom:8px}
.media-card input{width:100%;padding:6px 8px;border:2px solid var(--cream-3);border-radius:6px;font-family:var(--font-body);font-size:12px;margin-bottom:6px}
.media-kind{position:absolute;top:14px;left:14px;font-family:var(--font-mono);font-size:10px;font-weight:700;padding:2px 8px;border:2px solid var(--ink);border-radius:20px;background:var(--lime);color:var(--ink)}
.media-kind.code{background:var(--yellow)}
.media-actions{display:flex;gap:6px;align-items:center}
.media-add{padding:14px;border:2px dashed rgba(16,18,46,.35);border-radius:10px;background:rgba(255,255,255,.5)}

/* ═══ MOOD BOARD (portfolio-stijl) ═══ */
.frame{
  padding:clamp(12px,1.6vw,20px);border-radius:14px;margin-top:22px;
  background:repeating-linear-gradient(92deg,rgba(0,0,0,.10) 0 2px,transparent 2px 9px),linear-gradient(160deg,#8a5a2b,#6f4420 60%,#5c3717);
  box-shadow:inset 0 2px 0 rgba(255,255,255,.18),inset 0 -4px 8px rgba(0,0,0,.4),0 22px 40px -16px rgba(0,0,0,.6);
}
.board{
  position:relative;border-radius:6px;overflow:hidden;min-height:500px;
  background-color:#c89b58;
  background-image:radial-gradient(rgba(94,58,22,.32) 17%,transparent 18%),radial-gradient(rgba(150,104,52,.28) 17%,transparent 18%),radial-gradient(rgba(70,42,16,.22) 12%,transparent 13%),linear-gradient(150deg,#cda063,#bd8b48);
  background-size:13px 13px,13px 13px,21px 21px,100% 100%;
  background-position:0 0,6px 6px,3px 9px,0 0;
  box-shadow:inset 0 0 0 2px rgba(70,42,16,.35),inset 0 0 60px rgba(60,34,12,.45);
}
.board-item{
  position:absolute;cursor:grab;user-select:none;width:180px;
  transition:filter .18s ease;
}
.board-item:hover{z-index:10;filter:drop-shadow(0 10px 14px rgba(0,0,0,.4))}
.board-item.dragging{cursor:grabbing;z-index:20;opacity:.85}
.board-item .edit-btn{
  position:absolute;top:-8px;right:-8px;z-index:15;width:24px;height:24px;border-radius:50%;
  background:var(--pink);border:2px solid var(--ink);color:#fff;cursor:pointer;
  font-size:12px;display:flex;align-items:center;justify-content:center;
  opacity:0;transition:opacity .15s;box-shadow:2px 2px 0 rgba(0,0,0,.3);
}
.board-item:hover .edit-btn{opacity:1}
.board-item .del-btn{
  position:absolute;top:-8px;left:-8px;z-index:15;width:24px;height:24px;border-radius:50%;
  background:#c0392b;border:2px solid var(--ink);color:#fff;cursor:pointer;
  font-size:14px;display:flex;align-items:center;justify-content:center;
  opacity:0;transition:opacity .15s;box-shadow:2px 2px 0 rgba(0,0,0,.3);
}
.board-item:hover .del-btn{opacity:1}

.pin{position:absolute;top:-13px;left:50%;transform:translateX(-50%);width:24px;height:24px;z-index:6;pointer-events:none}
.pin .head{
  position:absolute;inset:0;border-radius:50%;background:var(--pin);
  border:2px solid var(--ink);box-shadow:2px 2px 0 rgba(8,10,40,.35);
}
.pin .head::after{content:"";position:absolute;left:26%;top:22%;width:22%;height:22%;border-radius:50%;background:rgba(255,255,255,.75)}
.pin.pink{--pin:var(--pink)}.pin.yellow{--pin:var(--yellow)}.pin.lime{--pin:var(--lime)}.pin.blue{--pin:var(--blue)}

.pcard{background:#fffdf5;padding:10px 10px 0;border:1px solid rgba(0,0,0,.08);box-shadow:0 10px 18px -6px rgba(0,0,0,.45)}
.pcard-photo{width:100%;aspect-ratio:4/5;overflow:hidden;background:#fffdf5;display:flex;align-items:center;justify-content:center}
.pcard-photo.pcard-wide{aspect-ratio:3/2}
.pcard-photo.pcard-character{background:#eae4f6}
.pcard-photo.pcard-character img{object-fit:contain !important}
.pcard-photo img{width:100%;height:100%;object-fit:cover;pointer-events:none}
.pcard .pcard-cap{font-family:'Caveat',cursive;font-size:22px;line-height:1.05;text-align:center;color:#2a2336;padding:7px 4px 11px}

.snote{
  padding:16px 14px 18px;font-family:'Caveat',cursive;font-size:22px;line-height:1.28;color:#3a2f12;
  background:var(--note,var(--yellow));box-shadow:0 10px 16px -6px rgba(0,0,0,.4);min-height:112px;display:flex;align-items:center;
}
.snote.lime{--note:var(--lime)}.snote.pink{--note:#ffd6ea}.snote.cream{--note:var(--cream)}

.washi{
  position:absolute;top:-12px;left:50%;transform:translateX(-50%) rotate(-4deg);
  width:74px;height:24px;z-index:6;
  background:repeating-linear-gradient(45deg,rgba(255,255,255,.5) 0 6px,rgba(255,255,255,.2) 6px 12px),var(--tape,var(--pink));
  opacity:.8;box-shadow:0 2px 4px rgba(0,0,0,.2);
}
.washi.blue{--tape:var(--blue)}.washi.lime{--tape:var(--lime)}

.board-toolbar{
  display:flex;gap:8px;align-items:center;margin-top:14px;flex-wrap:wrap;
}

/* ═══ FACTS (under board) ═══ */
.facts-section{margin-top:28px}
.facts-section h3{font-family:var(--font-display);font-weight:800;font-size:17px;color:var(--ink);margin:0 0 14px}
.fact-row{
  display:flex;gap:10px;align-items:center;padding:12px 16px;margin-bottom:8px;
  border:2.5px solid var(--ink);border-radius:10px;background:var(--cream);
  box-shadow:3px 3px 0 rgba(8,10,40,.28);
}
.fact-row .fact-text{flex:1;font-size:14px;color:var(--ink)}

/* ═══ TOAST ═══ */
.toast{
  position:fixed;bottom:24px;left:50%;transform:translateX(-50%);
  background:var(--ink);color:var(--cream);
  font-family:var(--font-mono);font-size:13px;font-weight:700;
  padding:10px 24px;border:2.5px solid var(--cream);border-radius:10px;
  box-shadow:var(--shadow-hard);z-index:999;animation:toast-in .3s ease;
}
.toast.error{background:#c0392b;border-color:#fff}
@keyframes toast-in{from{opacity:0;transform:translateX(-50%) translateY(20px)}to{opacity:1;transform:translateX(-50%) translateY(0)}}

/* ═══ STICKERS ═══ */
.sticker{
  position:fixed;pointer-events:none;user-select:none;z-index:0;
  font-family:var(--font-mono);font-weight:700;font-size:13px;
  padding:6px 12px;border:2.5px solid var(--ink);box-shadow:3px 3px 0 rgba(8,10,40,.4);
}
.sticker.s1{right:5%;top:14%;background:var(--yellow);color:var(--ink);transform:rotate(7deg)}
.sticker.s2{left:3%;top:42%;background:var(--lime);color:var(--ink);transform:rotate(-5deg)}
.sticker.s3{right:8%;bottom:18%;background:var(--pink);color:#fff;transform:rotate(4deg)}

/* ═══ RESPONSIVE ═══ */
@media(max-width:700px){
  .dual,.triple{grid-template-columns:1fr}
  .tabs{gap:3px;padding:7px 8px 0;flex-wrap:nowrap;-webkit-overflow-scrolling:touch}
  .tab-btn{padding:5px 10px;font-size:11px}
  .win-body{padding:16px 12px}
  .sticker{display:none}
  .admin{padding:10px 6px 40px}
  .admin-bar{padding:0 10px;gap:6px}
  .admin-bar .start{padding:0 10px 0 8px;font-size:12px;height:30px}
  .admin-bar .clock{padding:4px 8px;font-size:11px}
  .skill-grid,.lang-grid{grid-template-columns:1fr}
  .skill-card{padding:12px 14px}
  .skill-card-name{font-size:17px}
  .lang-card{padding:14px 15px}
  .lang-card-name{font-size:18px}
  .lang-card-level{font-size:10px;padding:2px 7px}
  .work-card{flex-direction:column}
  .work-shot{flex:0 0 auto;border-right:none;border-bottom:2.5px solid var(--ink);aspect-ratio:16/9;min-height:0}
  .work-meta h3{font-size:17px}
  .contact-row{padding:12px 14px;gap:10px}
  .contact-row .ci{width:34px;height:34px;font-size:11px}
  .contact-row .ct b{font-size:14px}
  .contact-row .ct span{font-size:12px}
  .sec-title{font-size:24px}
  .lead{font-size:14px}
  .eyebrow{font-size:10px}
  .timeline{padding-left:22px}
  .tl-item::before{left:-22px;width:12px;height:12px}
  .tl-item h3{font-size:16px}
  .tl-item .yr{font-size:11px}
  .tl-item .org{font-size:13px}
  .tl-item p{font-size:12.5px}
  .board{min-height:350px}
  .board-item{width:130px}
  .pcard .pcard-cap{font-size:17px;padding:5px 3px 8px}
  .snote{font-size:17px;min-height:85px;padding:12px 10px}
  .fact-row{padding:10px 12px;flex-wrap:wrap}
  .btn{font-size:11px;padding:7px 12px}
  .btn-add{padding:12px;font-size:11px}
  .field label{font-size:9.5px}
  .field input,.field textarea,.field select{font-size:13px;padding:9px 10px}
  .edit-modal{padding:18px;width:95%}
  .edit-modal h3{font-size:15px}
  .titlebar .path{font-size:11px}
  .proj-edit{padding:14px}
  .triple{grid-template-columns:1fr}
  .tagrow span{font-size:9.5px;padding:2px 6px}
  .window{border-radius:8px}
  .titlebar{height:34px;padding:0 10px}
}

@media(max-width:400px){
  .tab-btn{padding:4px 7px;font-size:10px}
  .admin-bar .start span.gem{display:none}
  .admin-bar .start{padding:0 8px;font-size:11px}
  .sec-title{font-size:21px}
  .skill-card-name{font-size:15px}
  .lang-card-name{font-size:16px}
  .board-item{width:110px}
  .board{min-height:280px}
  .contact-row .ci{width:30px;height:30px;font-size:10px;border-radius:6px}
  .edit-modal{width:98%;padding:14px}
}
</style>
</head>
<body>

<span class="sticker s1">admin ✦</span>
<span class="sticker s2">edit mode</span>
<span class="sticker s3">CRUD</span>

<div class="admin">

  <!-- ═══ TASKBAR ═══ -->
  <div class="admin-bar">
    <span class="start"><span class="gem"></span> LianneOS</span>
    <span class="spacer"></span>
    <a href="admin.php?logout" class="btn-logout" title="Uitloggen">uitloggen</a>
    <span class="clock"><span id="clock-time"></span><small id="clock-date"></small></span>
  </div>

  <!-- ═══ WINDOW ═══ -->
  <div class="window">
    <div class="titlebar" style="--acc:var(--pink)">
      <span class="dot"></span>
      <span class="path">C:\LIANNE\admin</span>
      <span class="win-controls">
        <button>&minus;</button>
        <button>&times;</button>
      </span>
    </div>

    <div class="tabs" id="tab-bar">
      <?php foreach ($TAB_COLORS as $id => $color): ?>
      <button class="tab-btn<?= $id==='about'?' active':'' ?>"
        style="--tab-acc:<?= $color ?>"
        onclick="showTab('<?= $id ?>')">
        <?= $id === 'about' ? 'about-me' : $id ?>
      </button>
      <?php endforeach; ?>
    </div>

    <div class="win-body">

      <!-- ═══ ABOUT ═══ -->
      <div class="panel active" id="tab-about">
        <p class="eyebrow">C:\LIANNE\admin\about-me</p>
        <h1 class="sec-title">Over <span class="hl">mij</span></h1>
        <form onsubmit="return saveForm(this,'update_profile')">
          <div class="dual">
            <div class="field"><label>Bio</label><textarea name="bio_nl" rows="4"><?= htmlspecialchars($profile['bio_nl']) ?></textarea></div>
            <div class="field"><label>Bio</label><textarea name="bio_en" rows="4"><?= htmlspecialchars($profile['bio_en']) ?></textarea></div>
          </div>
          <div class="dual">
            <div class="field"><label>Greeting</label><input name="greeting_nl" value="<?= htmlspecialchars($profile['greeting_nl']) ?>"></div>
            <div class="field"><label>Greeting</label><input name="greeting_en" value="<?= htmlspecialchars($profile['greeting_en']) ?>"></div>
          </div>
          <div class="field">
            <label>Foto</label>
            <div class="file-area">
              <input type="file" name="photo" accept="image/*">
              <p>klik om een foto te uploaden</p>
            </div>
          </div>
          <button type="submit" class="btn btn-pink">Opslaan ✦</button>
        </form>
      </div>

      <!-- ═══ PERSONAL (Mood Board) ═══ -->
      <div class="panel" id="tab-personal">
        <p class="eyebrow">C:\LIANNE\admin\personal</p>
        <h1 class="sec-title">Mood <span class="hl">board</span></h1>
        <p class="lead">Sleep items over het prikbord om ze te positioneren.</p>

        <div class="frame">
          <div class="board" id="mood-board">
            <?php
            $pinColors = ['pink','yellow','lime','blue','pink','lime'];
            $noteColors = ['cream','lime','pink'];
            $idx = 0;
            foreach ($moodItems as $m):
              $posStyle = '';
              if ($m['pos_x'] !== null && $m['pos_y'] !== null) {
                $posStyle = "left:{$m['pos_x']}%;top:{$m['pos_y']}%;";
              } else {
                $defaultX = 10 + ($idx % 4) * 22;
                $defaultY = 8 + intdiv($idx, 4) * 35;
                $posStyle = "left:{$defaultX}%;top:{$defaultY}%;";
              }
              $rotation = ['-2deg','2.4deg','-3deg','1.6deg','-1.4deg'][$idx % 5];
            ?>
            <div class="board-item" data-id="<?= $m['id'] ?>" style="<?= $posStyle ?>transform:rotate(<?= $rotation ?>)">
              <span class="pin <?= $pinColors[$idx % count($pinColors)] ?>"><span class="head"></span></span>
              <button class="edit-btn" onclick="event.stopPropagation();openMoodEdit(<?= $m['id'] ?>)" title="Bewerken">✎</button>
              <button class="del-btn" onclick="event.stopPropagation();deleteItem('mood',<?= $m['id'] ?>,this)" title="Verwijderen">×</button>

              <?php
              $MOOD_IMAGES = [
                'ik.png' => '../uploads/mood/ik.png',
                'konijnentjes.jpg' => '../uploads/mood/konijnentjes.jpg',
                'gezin.png' => '../uploads/mood/gezin.png',
              ];
              $CHARACTER_IMG = '../uploads/mood/lianne-character.png';
              ?>
              <?php if ($m['type'] === 'photo'): ?>
              <div class="pcard">
                <div class="pcard-photo<?= $m['key_name']==='creative'?' pcard-wide':'' ?><?= $m['key_name']==='me'?' pcard-character':'' ?>">
                  <?php if ($m['key_name'] === 'me'): ?>
                  <img src="<?= $CHARACTER_IMG ?>" alt="<?= htmlspecialchars($m['caption_nl'] ?? '') ?>">
                  <?php elseif ($m['image_url'] && isset($MOOD_IMAGES[$m['image_url']])): ?>
                  <img src="<?= $MOOD_IMAGES[$m['image_url']] ?>" alt="<?= htmlspecialchars($m['caption_nl'] ?? '') ?>"<?= $m['key_name']==='creative'?' style="object-position:center 20%"':'' ?>>
                  <?php elseif ($m['image_url']): ?>
                  <img src="../uploads/mood/<?= htmlspecialchars($m['image_url']) ?>" alt="<?= htmlspecialchars($m['caption_nl'] ?? '') ?>">
                  <?php else: ?>
                  <div style="width:100%;height:100%;background:#e7e2d4;display:flex;align-items:center;justify-content:center;font-family:var(--font-mono);font-size:11px;color:var(--muted)">foto</div>
                  <?php endif; ?>
                </div>
                <div class="pcard-cap"><?= htmlspecialchars($m['caption_nl'] ?? '') ?></div>
              </div>
              <?php else: ?>
              <div class="snote <?= $noteColors[$idx % count($noteColors)] ?>">
                <?= htmlspecialchars($m['caption_nl'] ?? '') ?>
              </div>
              <?php endif; ?>
            </div>
            <?php $idx++; endforeach; ?>
          </div>
        </div>

        <div class="board-toolbar">
          <button class="btn btn-pink" onclick="addMoodItem()">+ item toevoegen</button>
        </div>

        <!-- facts -->
        <div class="facts-section">
          <h3>Feitjes</h3>
          <div id="facts-list">
            <?php foreach ($facts as $f): ?>
            <div class="fact-row" data-id="<?= $f['id'] ?>">
              <span class="fact-text"><?= htmlspecialchars($f['text_nl']) ?> / <?= htmlspecialchars($f['text_en']) ?></span>
              <button class="btn btn-lime" style="padding:4px 10px" onclick="this.parentElement.querySelector('.edit-form').classList.toggle('open')">✎</button>
              <button class="btn-del" onclick="deleteItem('fact',<?= $f['id'] ?>,this)">×</button>
              <div class="edit-form" style="width:100%">
                <form onsubmit="return saveForm(this,'update_fact')">
                  <input type="hidden" name="id" value="<?= $f['id'] ?>">
                  <div class="dual">
                    <div class="field"><label>Tekst</label><input name="text_nl" value="<?= htmlspecialchars($f['text_nl']) ?>"></div>
                    <div class="field"><label>Text</label><input name="text_en" value="<?= htmlspecialchars($f['text_en']) ?>"></div>
                  </div>
                  <button type="submit" class="btn btn-lime">opslaan</button>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <button class="btn-add" onclick="addFact()">+ feitje toevoegen</button>
        </div>
      </div>

      <!-- ═══ WORKS ═══ -->
      <div class="panel" id="tab-works">
        <p class="eyebrow">C:\LIANNE\admin\works</p>
        <h1 class="sec-title">Mijn <span class="hl">projecten</span></h1>
        <p class="lead">Klik op een project om te bewerken.</p>

        <div class="timeline work-timeline">
          <?php foreach (array_reverse($projects) as $proj): ?>
          <div class="tl-item work-item" data-id="<?= $proj['id'] ?>">
            <span class="yr"><?= htmlspecialchars($proj['date_nl'] ?? '') ?></span>
            <div class="work-card" onclick="toggleProjEdit(<?= $proj['id'] ?>)">
              <div class="work-shot">
                <?php if ($proj['thumbnail_url']): ?>
                <img src="../uploads/projects/<?= htmlspecialchars($proj['thumbnail_url']) ?>" alt="">
                <?php else: ?>
                <span style="font-family:var(--font-mono);font-size:11px;color:var(--muted)">thumbnail</span>
                <?php endif; ?>
              </div>
              <div class="work-meta">
                <h3><?= htmlspecialchars($proj['title_nl']) ?></h3>
                <p><?= htmlspecialchars($proj['description_nl']) ?></p>
                <div class="tagrow">
                  <?php foreach ($proj['tags'] as $tag): ?>
                  <span><?= htmlspecialchars($tag) ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <div class="proj-edit" id="proj-edit-<?= $proj['id'] ?>">
              <form onsubmit="return saveForm(this,'update_project')">
                <input type="hidden" name="id" value="<?= $proj['id'] ?>">
                <div class="dual">
                  <div class="field"><label>Titel</label><input name="title_nl" value="<?= htmlspecialchars($proj['title_nl']) ?>"></div>
                  <div class="field"><label>Title</label><input name="title_en" value="<?= htmlspecialchars($proj['title_en']) ?>"></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Beschrijving</label><textarea name="description_nl" rows="2"><?= htmlspecialchars($proj['description_nl']) ?></textarea></div>
                  <div class="field"><label>Description</label><textarea name="description_en" rows="2"><?= htmlspecialchars($proj['description_en']) ?></textarea></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Datum</label><input name="date_nl" value="<?= htmlspecialchars($proj['date_nl'] ?? '') ?>"></div>
                  <div class="field"><label>Date</label><input name="date_en" value="<?= htmlspecialchars($proj['date_en'] ?? '') ?>"></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Rol</label><input name="role_nl" value="<?= htmlspecialchars($proj['role_nl'] ?? '') ?>"></div>
                  <div class="field"><label>Role</label><input name="role_en" value="<?= htmlspecialchars($proj['role_en'] ?? '') ?>"></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Type</label><input name="type_nl" value="<?= htmlspecialchars($proj['type_nl'] ?? '') ?>"></div>
                  <div class="field"><label>Type</label><input name="type_en" value="<?= htmlspecialchars($proj['type_en'] ?? '') ?>"></div>
                </div>
                <div class="field"><label>Duur</label><input name="duration" value="<?= htmlspecialchars($proj['duration'] ?? '') ?>"></div>
                <div class="dual">
                  <div class="field"><label>Intro</label><textarea name="intro_nl" rows="3"><?= htmlspecialchars($proj['intro_nl'] ?? '') ?></textarea></div>
                  <div class="field"><label>Intro</label><textarea name="intro_en" rows="3"><?= htmlspecialchars($proj['intro_en'] ?? '') ?></textarea></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Outcome</label><input name="outcome_nl" value="<?= htmlspecialchars($proj['outcome_nl'] ?? '') ?>"></div>
                  <div class="field"><label>Outcome</label><input name="outcome_en" value="<?= htmlspecialchars($proj['outcome_en'] ?? '') ?>"></div>
                </div>
                <div class="field"><label>Tags (komma-gescheiden)</label><input name="tags" value="<?= htmlspecialchars(implode(', ', $proj['tags'])) ?>"></div>
                <div class="dual">
                  <div class="field"><label>Highlights (1 per regel)</label><textarea name="highlights_nl" rows="3"><?= htmlspecialchars(implode("\n", array_column($proj['highlights'], 'text_nl'))) ?></textarea></div>
                  <div class="field"><label>Highlights (1 per line)</label><textarea name="highlights_en" rows="3"><?= htmlspecialchars(implode("\n", array_column($proj['highlights'], 'text_en'))) ?></textarea></div>
                </div>
                <div class="field">
                  <label>Thumbnail</label>
                  <div class="file-area">
                    <input type="file" name="thumbnail" accept="image/*">
                    <p><?= $proj['thumbnail_url'] ? htmlspecialchars($proj['thumbnail_url']) : 'klik om te uploaden' ?></p>
                  </div>
                </div>
                <div class="dual">
                  <div class="field"><label>Live website (optioneel)</label><input name="live_url" placeholder="https://..." value="<?= htmlspecialchars($proj['live_url'] ?? '') ?>"></div>
                  <div class="field"><label>GitHub link</label><input name="source_url" placeholder="https://github.com/..." value="<?= htmlspecialchars($proj['source_url'] ?? '') ?>"></div>
                </div>
                <div class="field"><label>Video (optioneel — YouTube/Vimeo link)</label><input name="video_url" placeholder="https://youtube.com/watch?v=..." value="<?= htmlspecialchars($proj['video_url'] ?? '') ?>"></div>
                <div style="display:flex;gap:8px">
                  <button type="submit" class="btn btn-pink">Opslaan ✦</button>
                  <button type="button" class="btn-del" onclick="deleteItem('project',<?= $proj['id'] ?>,this)">verwijderen</button>
                </div>
              </form>

              <!-- Media-beheer: foto's en code-screenshots -->
              <div class="media-mgr">
                <h4>Foto's &amp; code</h4>
                <p class="media-hint">Foto's van het project en screenshots van code met uitleg. Uitleg = de tekst die onder de code komt.</p>
                <div class="media-list" id="media-list-<?= $proj['id'] ?>">
                  <?php foreach ($proj['media'] as $m): ?>
                  <div class="media-card" data-id="<?= $m['id'] ?>">
                    <img src="../uploads/projects/<?= htmlspecialchars($m['image_url']) ?>" alt="">
                    <span class="media-kind <?= $m['kind'] ?>"><?= $m['kind'] === 'code' ? 'code' : 'foto' ?></span>
                    <form onsubmit="return saveForm(this,'update_project_media')">
                      <input type="hidden" name="id" value="<?= $m['id'] ?>">
                      <input name="caption_nl" placeholder="Bijschrift / uitleg (NL)" value="<?= htmlspecialchars($m['caption_nl'] ?? '') ?>">
                      <input name="caption_en" placeholder="Caption / explanation (EN)" value="<?= htmlspecialchars($m['caption_en'] ?? '') ?>">
                      <div class="media-actions">
                        <button type="submit" class="btn btn-pink" style="padding:5px 12px;font-size:12px">Opslaan</button>
                        <button type="button" class="btn-del" onclick="deleteMedia(<?= $m['id'] ?>,this)">verwijderen</button>
                      </div>
                    </form>
                  </div>
                  <?php endforeach; ?>
                </div>
                <form class="media-add" onsubmit="return addMedia(this,<?= $proj['id'] ?>)">
                  <div class="dual">
                    <div class="field"><label>Type</label>
                      <select name="kind"><option value="photo">Foto</option><option value="code">Code (met uitleg)</option></select>
                    </div>
                    <div class="field"><label>Afbeelding</label><input type="file" name="image" accept="image/*" required></div>
                  </div>
                  <div class="dual">
                    <div class="field"><label>Bijschrift / uitleg (NL)</label><input name="caption_nl"></div>
                    <div class="field"><label>Caption / explanation (EN)</label><input name="caption_en"></div>
                  </div>
                  <button type="submit" class="btn btn-pink" style="padding:7px 14px;font-size:13px">+ Media toevoegen</button>
                </form>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addProject()">+ nieuw project toevoegen</button>
      </div>

      <!-- ═══ SKILLS ═══ -->
      <div class="panel" id="tab-skills">
        <p class="eyebrow">C:\LIANNE\admin\skills</p>
        <h1 class="sec-title">Mijn <span class="hl">stack</span></h1>
        <p class="lead">Klik op een skill om te bewerken.</p>

        <div class="skill-grid" id="skills-list">
          <?php foreach ($skills as $s): ?>
          <div class="skill-card" style="--bc:<?= htmlspecialchars($s['color']) ?>" data-id="<?= $s['id'] ?>" onclick="toggleEdit(this)">
            <div class="skill-card-top">
              <span class="sq"></span>
              <span class="skill-card-name"><?= htmlspecialchars($s['name']) ?></span>
            </div>
            <p class="skill-card-use"><?= htmlspecialchars($s['description_nl']) ?></p>
            <button class="btn-del" onclick="event.stopPropagation();deleteItem('skill',<?= $s['id'] ?>,this)">×</button>

            <div class="edit-form" onclick="event.stopPropagation()">
              <form onsubmit="return saveForm(this,'update_skill')">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <div class="field"><label>Naam</label><input name="name" value="<?= htmlspecialchars($s['name']) ?>"></div>
                <div class="dual">
                  <div class="field"><label>Beschrijving</label><input name="description_nl" value="<?= htmlspecialchars($s['description_nl']) ?>"></div>
                  <div class="field"><label>Description</label><input name="description_en" value="<?= htmlspecialchars($s['description_en']) ?>"></div>
                </div>
                <div class="field">
                  <label>Kleur</label>
                  <div class="color-opts">
                    <?php foreach (['var(--pink)'=>'#ff2e97','var(--blue)'=>'#3b6cff','var(--lime)'=>'#aef23a','var(--yellow)'=>'#ffe01a'] as $val=>$hex): ?>
                    <div class="color-opt <?= $s['color']===$val?'active':'' ?>" style="background:<?= $hex ?>" onclick="pickColor(this,'<?= $val ?>')"></div>
                    <?php endforeach; ?>
                  </div>
                  <input type="hidden" name="color" value="<?= htmlspecialchars($s['color']) ?>">
                </div>
                <button type="submit" class="btn btn-lime">opslaan</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addSkill()">+ skill toevoegen</button>
      </div>

      <!-- ═══ EDUCATION ═══ -->
      <div class="panel" id="tab-education">
        <p class="eyebrow">C:\LIANNE\admin\education</p>
        <h1 class="sec-title">Mijn <span class="hl">opleiding</span></h1>
        <p class="lead">Klik op een item om te bewerken.</p>

        <div class="timeline" id="education-list">
          <?php foreach ($education as $e): ?>
          <div class="tl-item" data-id="<?= $e['id'] ?>" onclick="toggleEdit(this)">
            <span class="yr"><?= $e['year_start'] ?> — <?= $e['year_end'] ?? 'nu' ?></span>
            <h3><?= htmlspecialchars($e['degree_nl']) ?></h3>
            <div class="org"><?= htmlspecialchars($e['organization']) ?></div>
            <?php if ($e['description_nl']): ?><p><?= htmlspecialchars($e['description_nl']) ?></p><?php endif; ?>
            <button class="btn-del" onclick="event.stopPropagation();deleteItem('education',<?= $e['id'] ?>,this)">×</button>

            <div class="edit-form" onclick="event.stopPropagation()">
              <form onsubmit="return saveForm(this,'update_education')">
                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                <div class="triple">
                  <div class="field"><label>Start jaar</label><input name="year_start" value="<?= $e['year_start'] ?>" type="number"></div>
                  <div class="field"><label>Eind jaar</label><input name="year_end" value="<?= $e['year_end'] ?? '' ?>" type="number" placeholder="leeg = nu"></div>
                  <div class="field"><label>Organisatie</label><input name="organization" value="<?= htmlspecialchars($e['organization']) ?>"></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Opleiding</label><input name="degree_nl" value="<?= htmlspecialchars($e['degree_nl']) ?>"></div>
                  <div class="field"><label>Degree</label><input name="degree_en" value="<?= htmlspecialchars($e['degree_en']) ?>"></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Omschrijving</label><textarea name="description_nl" rows="2"><?= htmlspecialchars($e['description_nl'] ?? '') ?></textarea></div>
                  <div class="field"><label>Description</label><textarea name="description_en" rows="2"><?= htmlspecialchars($e['description_en'] ?? '') ?></textarea></div>
                </div>
                <button type="submit" class="btn btn-lime">opslaan</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addEducation()">+ opleiding toevoegen</button>
      </div>

      <!-- ═══ EXPERIENCE ═══ -->
      <div class="panel" id="tab-experience">
        <p class="eyebrow">C:\LIANNE\admin\experience</p>
        <h1 class="sec-title">Werk<span class="hl">ervaring</span></h1>
        <p class="lead">Klik op een item om te bewerken.</p>

        <div class="timeline" id="experience-list">
          <?php foreach ($experience as $e): ?>
          <div class="tl-item" data-id="<?= $e['id'] ?>" onclick="toggleEdit(this)">
            <span class="yr"><?= $e['year_start'] ?> — <?= $e['year_end'] ?? 'nu' ?></span>
            <h3><?= htmlspecialchars($e['position_nl']) ?></h3>
            <div class="org"><?= htmlspecialchars($e['company']) ?></div>
            <?php if ($e['description_nl']): ?><p><?= htmlspecialchars($e['description_nl']) ?></p><?php endif; ?>
            <button class="btn-del" onclick="event.stopPropagation();deleteItem('experience',<?= $e['id'] ?>,this)">×</button>

            <div class="edit-form" onclick="event.stopPropagation()">
              <form onsubmit="return saveForm(this,'update_experience')">
                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                <div class="triple">
                  <div class="field"><label>Start jaar</label><input name="year_start" value="<?= $e['year_start'] ?>" type="number"></div>
                  <div class="field"><label>Eind jaar</label><input name="year_end" value="<?= $e['year_end'] ?? '' ?>" type="number" placeholder="leeg = nu"></div>
                  <div class="field"><label>Bedrijf</label><input name="company" value="<?= htmlspecialchars($e['company']) ?>"></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Functie</label><input name="position_nl" value="<?= htmlspecialchars($e['position_nl']) ?>"></div>
                  <div class="field"><label>Position</label><input name="position_en" value="<?= htmlspecialchars($e['position_en']) ?>"></div>
                </div>
                <div class="dual">
                  <div class="field"><label>Omschrijving</label><textarea name="description_nl" rows="2"><?= htmlspecialchars($e['description_nl'] ?? '') ?></textarea></div>
                  <div class="field"><label>Description</label><textarea name="description_en" rows="2"><?= htmlspecialchars($e['description_en'] ?? '') ?></textarea></div>
                </div>
                <button type="submit" class="btn btn-lime">opslaan</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addExperience()">+ ervaring toevoegen</button>
      </div>

      <!-- ═══ LANGUAGES ═══ -->
      <div class="panel" id="tab-languages">
        <p class="eyebrow">C:\LIANNE\admin\languages</p>
        <h1 class="sec-title">Mijn <span class="hl">talen</span></h1>
        <p class="lead">Klik op een taal om te bewerken.</p>

        <div class="lang-grid" id="languages-list">
          <?php foreach ($languages as $l): ?>
          <div class="lang-card" style="--bc:var(--pink)" data-id="<?= $l['id'] ?>" onclick="toggleEdit(this)">
            <div class="lang-card-top">
              <span class="sq"></span>
              <span class="lang-card-name"><?= htmlspecialchars($l['name']) ?></span>
              <span class="lang-card-level"><?= htmlspecialchars($l['level_nl']) ?></span>
            </div>
            <div class="dots">
              <?php for ($i = 1; $i <= 5; $i++): ?>
              <i class="<?= $i <= $l['proficiency'] ? 'on' : '' ?>"></i>
              <?php endfor; ?>
            </div>
            <button class="btn-del" onclick="event.stopPropagation();deleteItem('language',<?= $l['id'] ?>,this)">×</button>

            <div class="edit-form" onclick="event.stopPropagation()">
              <form onsubmit="return saveForm(this,'update_language')">
                <input type="hidden" name="id" value="<?= $l['id'] ?>">
                <div class="field"><label>Taal</label><input name="name" value="<?= htmlspecialchars($l['name']) ?>"></div>
                <div class="dual">
                  <div class="field"><label>Niveau</label><input name="level_nl" value="<?= htmlspecialchars($l['level_nl']) ?>"></div>
                  <div class="field"><label>Level</label><input name="level_en" value="<?= htmlspecialchars($l['level_en']) ?>"></div>
                </div>
                <div class="field">
                  <label>Beheersing</label>
                  <div class="prof-dots">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="prof-dot <?= $i <= $l['proficiency'] ? 'filled' : '' ?>" onclick="pickProf(this,<?= $i ?>)"></div>
                    <?php endfor; ?>
                  </div>
                  <input type="hidden" name="proficiency" value="<?= $l['proficiency'] ?>">
                </div>
                <button type="submit" class="btn btn-lime">opslaan</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addLanguage()">+ taal toevoegen</button>
      </div>

      <!-- ═══ CONTACT ═══ -->
      <div class="panel" id="tab-contact">
        <p class="eyebrow">C:\LIANNE\admin\contact</p>
        <h1 class="sec-title">Contact <span class="hl">gegevens</span></h1>
        <p class="lead">Klik op een item om te bewerken.</p>

        <div class="contact-list" id="contact-list">
          <?php foreach ($contacts as $c):
            $meta = $PLATFORM_META[$c['platform']] ?? ['icon' => '?', 'bg' => 'var(--muted)'];
          ?>
          <div class="contact-row" data-id="<?= $c['id'] ?>" onclick="toggleEdit(this)">
            <span class="ci" style="background:<?= $meta['bg'] ?>;color:<?= $meta['color'] ?? '#fff' ?>"><?= $meta['icon'] ?></span>
            <span class="ct"><b><?= htmlspecialchars($c['platform']) ?></b><span><?= htmlspecialchars($c['label']) ?></span></span>
            <button class="btn-del" style="position:absolute;top:10px;right:10px;opacity:0" onclick="event.stopPropagation();deleteItem('contact',<?= $c['id'] ?>,this)">×</button>

            <div class="edit-form" onclick="event.stopPropagation()" style="width:100%">
              <form onsubmit="return saveForm(this,'update_contact')">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <div class="triple">
                  <div class="field"><label>Platform</label>
                    <select name="platform">
                      <?php foreach (['email','linkedin','github','instagram'] as $pl): ?>
                      <option <?= $c['platform']===$pl?'selected':'' ?>><?= $pl ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="field"><label>URL</label><input name="url" value="<?= htmlspecialchars($c['url']) ?>"></div>
                  <div class="field"><label>Label</label><input name="label" value="<?= htmlspecialchars($c['label']) ?>"></div>
                </div>
                <button type="submit" class="btn btn-lime">opslaan</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addContact()">+ contact toevoegen</button>
      </div>

    </div><!-- /win-body -->
  </div><!-- /window -->
</div><!-- /admin -->

<!-- ═══ MOOD EDIT MODAL ═══ -->
<div class="edit-overlay" id="mood-modal">
  <div class="edit-modal">
    <h3>Item bewerken</h3>
    <form id="mood-edit-form" onsubmit="return saveMoodEdit(this)">
      <input type="hidden" name="id" id="mood-edit-id">
      <div class="dual">
        <div class="field"><label>Tekst</label><input name="caption_nl" id="mood-edit-nl"></div>
        <div class="field"><label>Text</label><input name="caption_en" id="mood-edit-en"></div>
      </div>
      <div class="field" id="mood-edit-photo-field">
        <label>Foto</label>
        <div class="file-area">
          <input type="file" name="image" accept="image/*">
          <p>klik om te uploaden</p>
        </div>
      </div>
      <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-pink">Opslaan ✦</button>
        <button type="button" class="btn btn-yellow" onclick="closeMoodEdit()">Annuleren</button>
      </div>
    </form>
  </div>
</div>

<script>
const API = 'crud.php';

// ═══ TABS ═══
function showTab(id) {
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id).classList.add('active');
  event.target.classList.add('active');
}

// ═══ TOAST ═══
function toast(msg, err) {
  const t = document.createElement('div');
  t.className = 'toast' + (err ? ' error' : '');
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 2200);
}

// ═══ TOGGLE INLINE EDIT ═══
function toggleEdit(el) {
  const form = el.querySelector('.edit-form');
  if (form) form.classList.toggle('open');
}

// ═══ SAVE FORM ═══
async function saveForm(form, action) {
  const fd = new FormData(form);
  fd.append('action', action);
  try {
    const res = await fetch(API, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.ok) {
      toast('Opgeslagen ✦');
      if (data.id) {
        let idField = form.querySelector('[name="id"]');
        if (idField) idField.value = data.id;
        else { const h = document.createElement('input'); h.type='hidden'; h.name='id'; h.value=data.id; form.appendChild(h); }
      }
    } else {
      toast(data.error || 'Fout', true);
    }
  } catch (e) {
    toast('Fout: ' + e.message, true);
  }
  return false;
}

// ═══ DELETE ═══
async function deleteItem(type, id, btn) {
  if (!confirm('Weet je het zeker?')) return;
  const fd = new FormData();
  fd.append('action', 'delete_' + type);
  fd.append('id', id);
  try {
    const res = await fetch(API, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.ok) {
      const el = btn.closest('.skill-card,.tl-item,.lang-card,.contact-row,.fact-row,.board-item,.work-item');
      if (el) el.remove();
      toast('Verwijderd');
    }
  } catch (e) {
    toast('Fout', true);
  }
}

// ═══ COLOR & PROFICIENCY ═══
function pickColor(el, val) {
  el.parentElement.querySelectorAll('.color-opt').forEach(o => o.classList.remove('active'));
  el.classList.add('active');
  el.parentElement.nextElementSibling.value = val;
}

function pickProf(el, n) {
  const dots = el.parentElement.querySelectorAll('.prof-dot');
  dots.forEach((d, i) => d.classList.toggle('filled', i < n));
  el.parentElement.nextElementSibling.value = n;
}

// ═══ CLOCK ═══
function updateClock() {
  const now = new Date();
  document.getElementById('clock-time').textContent = now.toLocaleTimeString('nl-NL', {hour:'2-digit',minute:'2-digit'});
  document.getElementById('clock-date').textContent = now.toLocaleDateString('nl-NL', {day:'numeric',month:'short'});
}
updateClock();
setInterval(updateClock, 30000);

// ═══ PROJECT EDIT TOGGLE ═══
function toggleProjEdit(id) {
  document.getElementById('proj-edit-' + id).classList.toggle('open');
}

// ═══ PROJECT MEDIA (foto's & code) ═══
async function addMedia(form, projectId) {
  const fd = new FormData(form);
  fd.append('action', 'create_project_media');
  fd.append('project_id', projectId);
  try {
    const res = await fetch(API, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.ok) { toast('Media toegevoegd ✦'); location.reload(); }
    else toast(data.error || 'Fout', true);
  } catch (e) { toast('Fout: ' + e.message, true); }
  return false;
}

async function deleteMedia(id, btn) {
  if (!confirm('Deze media verwijderen?')) return;
  const fd = new FormData();
  fd.append('action', 'delete_project_media');
  fd.append('id', id);
  try {
    const res = await fetch(API, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.ok) { btn.closest('.media-card').remove(); toast('Verwijderd'); }
  } catch (e) { toast('Fout', true); }
}

// ═══ MOOD BOARD DRAG & DROP ═══
(function() {
  const board = document.getElementById('mood-board');
  if (!board) return;
  let dragItem = null, startX, startY, startLeft, startTop, hasMoved;

  function onDown(e) {
    const item = e.target.closest('.board-item');
    if (!item || e.target.closest('.edit-btn,.del-btn')) return;
    e.preventDefault();
    dragItem = item;
    hasMoved = false;
    const touch = e.touches ? e.touches[0] : e;
    startX = touch.clientX;
    startY = touch.clientY;
    startLeft = parseFloat(item.style.left);
    startTop = parseFloat(item.style.top);
    item.classList.add('dragging');
  }

  function onMove(e) {
    if (!dragItem) return;
    e.preventDefault();
    const touch = e.touches ? e.touches[0] : e;
    const dx = touch.clientX - startX;
    const dy = touch.clientY - startY;
    if (Math.abs(dx) > 3 || Math.abs(dy) > 3) hasMoved = true;
    const boardRect = board.getBoundingClientRect();
    const newX = startLeft + (dx / boardRect.width) * 100;
    const newY = startTop + (dy / boardRect.height) * 100;
    dragItem.style.left = Math.max(0, Math.min(85, newX)) + '%';
    dragItem.style.top = Math.max(0, Math.min(85, newY)) + '%';
  }

  function onUp() {
    if (!dragItem) return;
    dragItem.classList.remove('dragging');
    if (hasMoved) {
      const id = dragItem.dataset.id;
      const posX = parseFloat(dragItem.style.left);
      const posY = parseFloat(dragItem.style.top);
      const fd = new FormData();
      fd.append('action', 'update_mood_position');
      fd.append('id', id);
      fd.append('pos_x', posX);
      fd.append('pos_y', posY);
      fetch(API, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => { if (d.ok) toast('Positie opgeslagen ✦'); });
    }
    dragItem = null;
  }

  board.addEventListener('mousedown', onDown);
  document.addEventListener('mousemove', onMove);
  document.addEventListener('mouseup', onUp);
  board.addEventListener('touchstart', onDown, { passive: false });
  document.addEventListener('touchmove', onMove, { passive: false });
  document.addEventListener('touchend', onUp);
})();

// ═══ MOOD EDIT MODAL ═══
const moodData = <?= json_encode(array_map(function($m) {
  return ['id' => $m['id'], 'type' => $m['type'], 'caption_nl' => $m['caption_nl'], 'caption_en' => $m['caption_en']];
}, $moodItems), JSON_UNESCAPED_UNICODE) ?>;

function openMoodEdit(id) {
  const item = moodData.find(m => m.id == id);
  if (!item) return;
  document.getElementById('mood-edit-id').value = id;
  document.getElementById('mood-edit-nl').value = item.caption_nl || '';
  document.getElementById('mood-edit-en').value = item.caption_en || '';
  document.getElementById('mood-edit-photo-field').style.display = item.type === 'photo' ? 'block' : 'none';
  document.getElementById('mood-modal').classList.add('open');
}

function closeMoodEdit() {
  document.getElementById('mood-modal').classList.remove('open');
}

async function saveMoodEdit(form) {
  const fd = new FormData(form);
  fd.append('action', 'update_mood');
  try {
    const res = await fetch(API, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.ok) {
      toast('Opgeslagen ✦');
      closeMoodEdit();
      location.reload();
    }
  } catch (e) {
    toast('Fout: ' + e.message, true);
  }
  return false;
}

// ═══ ADD FUNCTIONS ═══
function addSkill() {
  const html = `<div class="skill-card" style="--bc:var(--pink)" onclick="toggleEdit(this)">
    <div class="skill-card-top"><span class="sq"></span><span class="skill-card-name">Nieuwe skill</span></div>
    <p class="skill-card-use">Klik om te bewerken</p>
    <div class="edit-form open" onclick="event.stopPropagation()">
      <form onsubmit="return saveForm(this,'create_skill')">
        <div class="field"><label>Naam</label><input name="name" required></div>
        <div class="dual">
          <div class="field"><label>Beschrijving</label><input name="description_nl" required></div>
          <div class="field"><label>Description</label><input name="description_en" required></div>
        </div>
        <div class="field"><label>Kleur</label>
          <div class="color-opts">
            <div class="color-opt active" style="background:#ff2e97" onclick="pickColor(this,'var(--pink)')"></div>
            <div class="color-opt" style="background:#3b6cff" onclick="pickColor(this,'var(--blue)')"></div>
            <div class="color-opt" style="background:#aef23a" onclick="pickColor(this,'var(--lime)')"></div>
            <div class="color-opt" style="background:#ffe01a" onclick="pickColor(this,'var(--yellow)')"></div>
          </div>
          <input type="hidden" name="color" value="var(--pink)">
        </div>
        <button type="submit" class="btn btn-pink">Toevoegen ✦</button>
      </form>
    </div>
  </div>`;
  document.getElementById('skills-list').insertAdjacentHTML('beforeend', html);
}

function addEducation() {
  const html = `<div class="tl-item" onclick="toggleEdit(this)">
    <span class="yr">nieuw</span><h3>Nieuwe opleiding</h3>
    <div class="edit-form open" onclick="event.stopPropagation()">
      <form onsubmit="return saveForm(this,'create_education')">
        <div class="triple">
          <div class="field"><label>Start jaar</label><input name="year_start" type="number" required></div>
          <div class="field"><label>Eind jaar</label><input name="year_end" type="number" placeholder="leeg = nu"></div>
          <div class="field"><label>Organisatie</label><input name="organization" required></div>
        </div>
        <div class="dual">
          <div class="field"><label>Opleiding</label><input name="degree_nl" required></div>
          <div class="field"><label>Degree</label><input name="degree_en" required></div>
        </div>
        <div class="dual">
          <div class="field"><label>Omschrijving</label><textarea name="description_nl" rows="2"></textarea></div>
          <div class="field"><label>Description</label><textarea name="description_en" rows="2"></textarea></div>
        </div>
        <button type="submit" class="btn btn-pink">Toevoegen ✦</button>
      </form>
    </div>
  </div>`;
  document.getElementById('education-list').insertAdjacentHTML('beforeend', html);
}

function addExperience() {
  const html = `<div class="tl-item" onclick="toggleEdit(this)">
    <span class="yr">nieuw</span><h3>Nieuwe ervaring</h3>
    <div class="edit-form open" onclick="event.stopPropagation()">
      <form onsubmit="return saveForm(this,'create_experience')">
        <div class="triple">
          <div class="field"><label>Start jaar</label><input name="year_start" type="number" required></div>
          <div class="field"><label>Eind jaar</label><input name="year_end" type="number" placeholder="leeg = nu"></div>
          <div class="field"><label>Bedrijf</label><input name="company" required></div>
        </div>
        <div class="dual">
          <div class="field"><label>Functie</label><input name="position_nl" required></div>
          <div class="field"><label>Position</label><input name="position_en" required></div>
        </div>
        <div class="dual">
          <div class="field"><label>Omschrijving</label><textarea name="description_nl" rows="2"></textarea></div>
          <div class="field"><label>Description</label><textarea name="description_en" rows="2"></textarea></div>
        </div>
        <button type="submit" class="btn btn-pink">Toevoegen ✦</button>
      </form>
    </div>
  </div>`;
  document.getElementById('experience-list').insertAdjacentHTML('beforeend', html);
}

function addLanguage() {
  const html = `<div class="lang-card" style="--bc:var(--pink)" onclick="toggleEdit(this)">
    <div class="lang-card-top"><span class="sq"></span><span class="lang-card-name">Nieuwe taal</span></div>
    <div class="dots"><i></i><i></i><i></i><i></i><i></i></div>
    <div class="edit-form open" onclick="event.stopPropagation()">
      <form onsubmit="return saveForm(this,'create_language')">
        <div class="field"><label>Taal</label><input name="name" required></div>
        <div class="dual">
          <div class="field"><label>Niveau</label><input name="level_nl" required></div>
          <div class="field"><label>Level</label><input name="level_en" required></div>
        </div>
        <div class="field"><label>Beheersing</label>
          <div class="prof-dots">
            <div class="prof-dot" onclick="pickProf(this,1)"></div>
            <div class="prof-dot" onclick="pickProf(this,2)"></div>
            <div class="prof-dot" onclick="pickProf(this,3)"></div>
            <div class="prof-dot" onclick="pickProf(this,4)"></div>
            <div class="prof-dot" onclick="pickProf(this,5)"></div>
          </div>
          <input type="hidden" name="proficiency" value="1">
        </div>
        <button type="submit" class="btn btn-pink">Toevoegen ✦</button>
      </form>
    </div>
  </div>`;
  document.getElementById('languages-list').insertAdjacentHTML('beforeend', html);
}

function addContact() {
  const html = `<div class="contact-row" onclick="toggleEdit(this)">
    <span class="ci" style="background:var(--muted)">?</span>
    <span class="ct"><b>nieuw</b><span>klik om in te vullen</span></span>
    <div class="edit-form open" onclick="event.stopPropagation()" style="width:100%">
      <form onsubmit="return saveForm(this,'create_contact')">
        <div class="triple">
          <div class="field"><label>Platform</label>
            <select name="platform"><option>email</option><option>linkedin</option><option>github</option><option>instagram</option></select>
          </div>
          <div class="field"><label>URL</label><input name="url" required></div>
          <div class="field"><label>Label</label><input name="label" required></div>
        </div>
        <button type="submit" class="btn btn-pink">Toevoegen ✦</button>
      </form>
    </div>
  </div>`;
  document.getElementById('contact-list').insertAdjacentHTML('beforeend', html);
}

function addMoodItem() {
  const type = prompt('Type: photo of note?', 'photo');
  if (!type || !['photo','note'].includes(type)) return;
  const key = prompt('Key naam (uniek, bijv: "selfie", "tekening"):');
  if (!key) return;
  const html = `<div class="board-item" style="left:40%;top:40%;transform:rotate(-2deg)" data-id="">
    <span class="pin pink"><span class="head"></span></span>
    ${type === 'photo'
      ? `<div class="pcard"><div class="pcard-photo"><div style="width:100%;height:100%;background:#e7e2d4;display:flex;align-items:center;justify-content:center;font-family:var(--font-mono);font-size:11px;color:var(--muted)">foto</div></div><div class="pcard-cap">nieuw</div></div>`
      : `<div class="snote cream">nieuw item</div>`}
  </div>`;
  const board = document.getElementById('mood-board');
  board.insertAdjacentHTML('beforeend', html);

  const fd = new FormData();
  fd.append('action', 'create_mood');
  fd.append('type', type);
  fd.append('key_name', key);
  fd.append('caption_nl', '');
  fd.append('caption_en', '');
  fetch(API, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      if (d.ok) {
        toast('Item toegevoegd ✦');
        location.reload();
      }
    });
}

function addFact() {
  const html = `<div class="fact-row">
    <form onsubmit="return saveForm(this,'create_fact')" style="display:flex;gap:8px;flex:1;align-items:center">
      <input name="text_nl" placeholder="NL" required style="flex:1;padding:8px 10px;border:2.5px solid var(--cream-3);border-radius:6px;font-family:var(--font-body);font-size:13px">
      <input name="text_en" placeholder="EN" required style="flex:1;padding:8px 10px;border:2.5px solid var(--cream-3);border-radius:6px;font-family:var(--font-body);font-size:13px">
      <button type="submit" class="btn btn-pink" style="padding:6px 12px">+</button>
    </form>
  </div>`;
  document.getElementById('facts-list').insertAdjacentHTML('beforeend', html);
}

function addProject() {
  const html = `<div class="tl-item work-item">
    <span class="yr">nieuw</span>
    <div class="work-card" onclick="this.nextElementSibling.classList.toggle('open')">
      <div class="work-shot"><span style="font-family:var(--font-mono);font-size:11px;color:var(--muted)">thumbnail</span></div>
      <div class="work-meta"><h3>Nieuw project</h3><p>Klik om te bewerken</p></div>
    </div>
    <div class="proj-edit open">
      <form onsubmit="return saveForm(this,'create_project')">
        <div class="dual">
          <div class="field"><label>Titel</label><input name="title_nl" required></div>
          <div class="field"><label>Title</label><input name="title_en" required></div>
        </div>
        <div class="dual">
          <div class="field"><label>Beschrijving</label><textarea name="description_nl" rows="2" required></textarea></div>
          <div class="field"><label>Description</label><textarea name="description_en" rows="2" required></textarea></div>
        </div>
        <div class="dual">
          <div class="field"><label>Datum</label><input name="date_nl"></div>
          <div class="field"><label>Date</label><input name="date_en"></div>
        </div>
        <div class="dual">
          <div class="field"><label>Rol</label><input name="role_nl"></div>
          <div class="field"><label>Role</label><input name="role_en"></div>
        </div>
        <div class="dual">
          <div class="field"><label>Type</label><input name="type_nl"></div>
          <div class="field"><label>Type</label><input name="type_en"></div>
        </div>
        <div class="field"><label>Duur</label><input name="duration"></div>
        <div class="dual">
          <div class="field"><label>Intro</label><textarea name="intro_nl" rows="3"></textarea></div>
          <div class="field"><label>Intro</label><textarea name="intro_en" rows="3"></textarea></div>
        </div>
        <div class="dual">
          <div class="field"><label>Outcome</label><input name="outcome_nl"></div>
          <div class="field"><label>Outcome</label><input name="outcome_en"></div>
        </div>
        <div class="field"><label>Tags (komma-gescheiden)</label><input name="tags"></div>
        <div class="dual">
          <div class="field"><label>Highlights (1 per regel)</label><textarea name="highlights_nl" rows="3"></textarea></div>
          <div class="field"><label>Highlights (1 per line)</label><textarea name="highlights_en" rows="3"></textarea></div>
        </div>
        <div class="field"><label>Thumbnail</label><div class="file-area"><input type="file" name="thumbnail" accept="image/*"><p>klik om te uploaden</p></div></div>
        <div class="dual">
          <div class="field"><label>Live website (optioneel)</label><input name="live_url" placeholder="https://..."></div>
          <div class="field"><label>GitHub link</label><input name="source_url" placeholder="https://github.com/..."></div>
        </div>
        <div class="field"><label>Video (optioneel — YouTube/Vimeo link)</label><input name="video_url" placeholder="https://youtube.com/watch?v=..."></div>
        <p class="media-hint">Foto's &amp; code kun je toevoegen zodra het project is opgeslagen.</p>
        <button type="submit" class="btn btn-pink">Project toevoegen ✦</button>
      </form>
    </div>
  </div>`;
  document.querySelector('#tab-works .work-timeline').insertAdjacentHTML('beforeend', html);
}
</script>
</body>
</html>
<?php
function showLoginPage(bool $error = false): void {
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LianneOS — Login</title>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@700;900&family=DM+Sans:wght@400;500;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --navy:#161d7a;--navy-2:#101663;--navy-deep:#0a0f47;--ink:#10122e;
  --cream:#f5f1e6;--cream-2:#e9e3d1;--cream-3:#dcd4bd;
  --pink:#ff2e97;--pink-deep:#d6157a;--yellow:#ffe01a;--lime:#aef23a;--blue:#3b6cff;
  --font-display:'Archivo',sans-serif;--font-body:'DM Sans',sans-serif;--font-mono:'Space Mono',monospace;
  --shadow-hard:7px 7px 0 rgba(8,10,40,.45);--shadow-soft:0 18px 40px -12px rgba(8,10,40,.7);
}
body{
  background:
    radial-gradient(120% 90% at 85% -10%,#28309e 0%,rgba(40,48,158,0) 55%),
    radial-gradient(90% 80% at 10% 110%,#1c2492 0%,rgba(28,36,146,0) 55%),
    var(--navy);
  color:var(--cream);font-family:var(--font-body);
  min-height:100vh;display:flex;align-items:center;justify-content:center;
}
body::before{
  content:"";position:fixed;inset:0;pointer-events:none;z-index:0;
  background-image:radial-gradient(rgba(255,255,255,.07) 1.5px,transparent 1.5px);
  background-size:26px 26px;opacity:.9;
}
.login-wrap{position:relative;z-index:1;width:100%;max-width:420px;padding:20px}
.login-win{
  background:var(--cream);border:2.5px solid var(--ink);
  box-shadow:var(--shadow-hard),var(--shadow-soft);border-radius:10px;overflow:hidden;
}
.login-bar{
  height:38px;display:flex;align-items:center;gap:10px;
  padding:0 12px;background:var(--ink);color:var(--cream);
}
.login-bar .dot{width:11px;height:11px;border-radius:50%;background:var(--pink);border:1.5px solid rgba(255,255,255,.5)}
.login-bar .path{font-family:var(--font-mono);font-size:12.5px;font-weight:700;letter-spacing:.01em}
.login-body{padding:32px 28px 28px}
.login-body .avatar{
  width:80px;height:80px;margin:0 auto 18px;border-radius:50%;
  background:var(--pink);border:3px solid var(--ink);box-shadow:var(--shadow-hard);
  display:flex;align-items:center;justify-content:center;
  font-family:var(--font-display);font-weight:900;font-size:32px;color:#fff;
}
.login-body h2{
  font-family:var(--font-display);font-weight:900;font-size:22px;color:var(--ink);
  text-align:center;margin:0 0 4px;
}
.login-body .sub{
  font-family:var(--font-mono);font-size:11px;color:#5a5d77;text-align:center;
  margin:0 0 22px;letter-spacing:.03em;
}
.login-body .field{margin-bottom:14px}
.login-body label{
  display:block;font-family:var(--font-mono);font-size:10.5px;font-weight:700;
  text-transform:uppercase;letter-spacing:.12em;color:#5a5d77;margin-bottom:5px;
}
.login-body input{
  width:100%;padding:11px 14px;border:2.5px solid var(--cream-3);border-radius:8px;
  font-family:var(--font-body);font-size:14px;background:#fff;color:var(--ink);
  transition:border-color .15s;
}
.login-body input:focus{outline:none;border-color:var(--pink)}
.login-body .btn-login{
  width:100%;padding:12px;border:2.5px solid var(--ink);border-radius:8px;
  background:var(--pink);color:#fff;font-family:var(--font-mono);font-weight:700;
  font-size:13px;cursor:pointer;box-shadow:var(--shadow-hard);
  transition:all .12s;margin-top:6px;
}
.login-body .btn-login:hover{background:var(--pink-deep);transform:translate(2px,2px);box-shadow:none}
.login-error{
  background:#c0392b;color:#fff;text-align:center;padding:8px;
  font-family:var(--font-mono);font-size:12px;font-weight:700;
  border-radius:6px;margin-bottom:14px;
}
.sticker{
  position:fixed;pointer-events:none;user-select:none;z-index:0;
  font-family:var(--font-mono);font-weight:700;font-size:13px;
  padding:6px 12px;border:2.5px solid var(--ink);box-shadow:3px 3px 0 rgba(8,10,40,.4);
}
.s1{right:12%;top:18%;background:var(--yellow);color:var(--ink);transform:rotate(7deg)}
.s2{left:8%;bottom:22%;background:var(--lime);color:var(--ink);transform:rotate(-5deg)}
@media(max-width:500px){.sticker{display:none}.login-body{padding:24px 20px 22px}}
</style>
</head>
<body>

<span class="sticker s1">login ✦</span>
<span class="sticker s2">admin</span>

<div class="login-wrap">
  <div class="login-win">
    <div class="login-bar">
      <span class="dot"></span>
      <span class="path">C:\LIANNE\login</span>
    </div>
    <div class="login-body">
      <div class="avatar">L</div>
      <h2>LianneOS</h2>
      <p class="sub">voer je gegevens in om verder te gaan</p>

      <?php if ($error): ?>
      <div class="login-error">Onjuiste gebruikersnaam of wachtwoord</div>
      <?php endif; ?>

      <form method="POST" action="admin.php">
        <div class="field">
          <label>Gebruikersnaam</label>
          <input type="text" name="username" required autofocus autocomplete="username">
        </div>
        <div class="field">
          <label>Wachtwoord</label>
          <input type="password" name="password" required autocomplete="current-password">
        </div>
        <input type="hidden" name="admin_login" value="1">
        <button type="submit" class="btn-login">Inloggen ✦</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
<?php } ?>
