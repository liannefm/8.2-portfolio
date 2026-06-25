<?php
require_once __DIR__ . '/config.php';
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
}
unset($p);

$TAB_COLORS = [
  'about' => 'var(--pink)', 'personal' => 'var(--pink)', 'works' => 'var(--lime)',
  'skills' => 'var(--yellow)', 'education' => 'var(--blue)', 'experience' => 'var(--pink)',
  'languages' => 'var(--lime)', 'contact' => 'var(--blue)',
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

/* ═══ TASKBAR (top) ═══ */
.admin-bar{
  display:flex;align-items:center;gap:10px;padding:0 14px;height:48px;
  background:var(--navy-deep);border:2.5px solid #000;border-radius:10px;
  box-shadow:0 4px 16px rgba(0,0,0,.35);margin-bottom:28px;
}
.admin-bar .start{
  display:flex;align-items:center;gap:9px;height:34px;padding:0 14px 0 10px;
  background:var(--pink);color:#fff;border:2.5px solid var(--ink);
  border-radius:8px;box-shadow:2px 2px 0 rgba(0,0,0,.4);
  font-family:var(--font-display);font-weight:800;font-size:14px;letter-spacing:.01em;
  cursor:default;
}
.admin-bar .start .gem{width:12px;height:12px;background:var(--yellow);border:2px solid var(--ink);transform:rotate(45deg)}
.admin-bar .spacer{flex:1}
.admin-bar .clock{
  font-family:var(--font-mono);font-weight:700;font-size:12px;color:var(--cream);
  background:var(--navy);border:2px solid rgba(255,255,255,.18);border-radius:7px;
  padding:6px 12px;line-height:1;text-align:center;
}
.admin-bar .clock small{display:block;font-size:9px;color:rgba(255,255,255,.55);margin-top:2px}

/* ═══ WINDOW ═══ */
.window{
  background:var(--cream);border:2.5px solid var(--ink);
  box-shadow:var(--shadow-hard),var(--shadow-soft);
  border-radius:10px;overflow:hidden;
}

/* titlebar */
.titlebar{
  height:38px;display:flex;align-items:center;gap:10px;
  padding:0 12px;background:var(--ink);color:var(--cream);user-select:none;
}
.titlebar .dot{width:11px;height:11px;border-radius:50%;background:var(--acc,var(--pink));flex:0 0 auto;border:1.5px solid rgba(255,255,255,.5)}
.titlebar .path{
  font-family:var(--font-mono);font-size:12.5px;font-weight:700;
  letter-spacing:.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex:1 1 auto;
}
.titlebar .win-controls{display:flex;gap:6px;flex:0 0 auto}
.titlebar .win-controls button{
  width:18px;height:18px;border:1.5px solid var(--cream);background:transparent;
  border-radius:3px;color:var(--cream);display:flex;align-items:center;justify-content:center;
  font-size:11px;line-height:1;padding:0;cursor:pointer;
}
.titlebar .win-controls button:hover{background:var(--cream);color:var(--ink)}

/* folder tabs */
.tabs{
  display:flex;gap:6px;padding:9px 10px 0;
  background:var(--cream);overflow-x:auto;scrollbar-width:none;
}
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
.win-body{
  overflow:auto;background:var(--cream);
  border-top:2.5px solid var(--ink);padding:24px;
}
.win-body::-webkit-scrollbar{width:12px}
.win-body::-webkit-scrollbar-thumb{background:var(--cream-3);border:2px solid var(--cream);border-radius:8px}

/* ═══ SECTION CONTENT ═══ */
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

/* ═══ CARDS ═══ */
.card{
  background:var(--cream);border:2.5px solid var(--ink);border-radius:12px;
  padding:16px 18px 16px 28px;margin-bottom:14px;
  box-shadow:3px 3px 0 rgba(8,10,40,.28);position:relative;
  transition:transform .12s ease,box-shadow .12s ease;
}
.card:hover{transform:translate(-1px,-1px);box-shadow:5px 5px 0 rgba(8,10,40,.35)}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.card-head h3{font-family:var(--font-display);font-weight:800;font-size:17px;color:var(--ink)}
.card .btn-del{position:absolute;top:12px;right:12px}

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

/* ═══ COLOR PICKER ═══ */
.color-opts{display:flex;gap:8px;margin-top:4px}
.color-opt{
  width:28px;height:28px;border-radius:50%;border:3px solid transparent;
  cursor:pointer;transition:all .12s;
}
.color-opt:hover,.color-opt.active{border-color:var(--ink);transform:scale(1.15)}

/* ═══ PROFICIENCY ═══ */
.prof-dots{display:flex;gap:6px;margin-top:4px}
.prof-dot{
  width:16px;height:16px;border-radius:50%;border:2.5px solid var(--ink);
  background:var(--cream);cursor:pointer;transition:all .12s;
}
.prof-dot.filled{background:var(--pink)}

/* ═══ FILE UPLOAD ═══ */
.file-area{
  border:2.5px dashed var(--cream-3);border-radius:10px;padding:18px;
  text-align:center;cursor:pointer;transition:all .15s;position:relative;
}
.file-area:hover{border-color:var(--pink);background:rgba(255,46,151,.04)}
.file-area input{position:absolute;inset:0;opacity:0;cursor:pointer}
.file-area p{font-family:var(--font-mono);font-size:11px;color:var(--muted)}

/* ═══ DRAG HANDLE ═══ */
.drag-handle{
  position:absolute;left:-2px;top:50%;transform:translateY(-50%);
  width:22px;display:flex;flex-direction:column;align-items:center;gap:2px;
  cursor:grab;padding:8px 4px;border-radius:4px 0 0 4px;opacity:.35;transition:opacity .15s;
}
.drag-handle:active{cursor:grabbing}
.card:hover .drag-handle{opacity:.7}
.drag-handle i{display:block;width:12px;height:2.5px;background:var(--ink);border-radius:1px}
.card.dragging{opacity:.5;box-shadow:none;transform:scale(.97)}
.card.drag-over{border-color:var(--pink);box-shadow:0 0 0 3px rgba(255,46,151,.25)}
.sortable-list{min-height:20px}

/* ═══ PROJECT TOGGLE ═══ */
.proj-toggle{cursor:pointer;user-select:none}
.proj-toggle::before{content:'▶  ';font-size:10px}
.proj-toggle.open::before{content:'▼  '}
.proj-body{display:none;margin-top:14px;padding-top:14px;border-top:2px dashed var(--cream-3)}
.proj-body.open{display:block}

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
  padding:6px 12px;border:2.5px solid var(--ink);
  box-shadow:3px 3px 0 rgba(8,10,40,.4);
}
.sticker.s1{right:5%;top:14%;background:var(--yellow);color:var(--ink);transform:rotate(7deg)}
.sticker.s2{left:3%;top:42%;background:var(--lime);color:var(--ink);transform:rotate(-5deg)}
.sticker.s3{right:8%;bottom:18%;background:var(--pink);color:#fff;transform:rotate(4deg)}

/* ═══ RESPONSIVE ═══ */
@media(max-width:700px){
  .dual,.triple{grid-template-columns:1fr}
  .tabs{gap:3px;padding:7px 8px 0}
  .tab-btn{padding:5px 10px;font-size:11px}
  .win-body{padding:18px 14px}
  .sticker{display:none}
  .admin{padding:14px 10px 40px}
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

      <!-- ═══ PERSONAL ═══ -->
      <div class="panel" id="tab-personal">
        <p class="eyebrow">C:\LIANNE\admin\personal</p>
        <h1 class="sec-title">Mood <span class="hl">board</span></h1>

        <div class="chip-row">
          <span class="chip fill-blue">foto's</span>
          <span class="chip fill-yellow">notities</span>
          <span class="chip">feitjes</span>
        </div>

        <div id="mood-list" class="sortable-list" data-table="mood_items">
          <?php foreach ($moodItems as $m): ?>
          <div class="card" data-id="<?= $m['id'] ?>">
            <span class="drag-handle"><i></i><i></i><i></i></span>
            <button class="btn-del" onclick="deleteItem('mood',<?= $m['id'] ?>,this)">×</button>
            <form onsubmit="return saveForm(this,'update_mood')">
              <input type="hidden" name="id" value="<?= $m['id'] ?>">
              <div class="chip-row">
                <span class="chip <?= $m['type']==='photo'?'fill-blue':'fill-yellow' ?>"><?= $m['type'] ?></span>
                <span class="chip"><?= htmlspecialchars($m['key_name']) ?></span>
              </div>
              <div class="dual">
                <div class="field"><label>Tekst</label><input name="caption_nl" value="<?= htmlspecialchars($m['caption_nl'] ?? '') ?>"></div>
                <div class="field"><label>Tekst</label><input name="caption_en" value="<?= htmlspecialchars($m['caption_en'] ?? '') ?>"></div>
              </div>
              <?php if ($m['type'] === 'photo'): ?>
              <div class="field">
                <label>Foto</label>
                <div class="file-area">
                  <input type="file" name="image" accept="image/*">
                  <p><?= $m['image_url'] ? htmlspecialchars($m['image_url']) : 'klik om te uploaden' ?></p>
                </div>
              </div>
              <?php endif; ?>
              <button type="submit" class="btn btn-lime">opslaan</button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addMoodItem()">+ nieuw item toevoegen</button>

        <h3 style="font-family:var(--font-display);font-weight:800;font-size:17px;color:var(--ink);margin:28px 0 14px">Feitjes</h3>
        <div id="facts-list" class="sortable-list" data-table="personality_facts">
          <?php foreach ($facts as $f): ?>
          <div class="card" data-id="<?= $f['id'] ?>" style="display:flex;gap:10px;align-items:center;padding:12px 16px 12px 28px">
            <span class="drag-handle" style="left:-2px;top:50%"><i></i><i></i><i></i></span>
            <form onsubmit="return saveForm(this,'update_fact')" style="display:flex;gap:8px;flex:1;align-items:center">
              <input type="hidden" name="id" value="<?= $f['id'] ?>">
              <input name="text_nl" value="<?= htmlspecialchars($f['text_nl']) ?>" placeholder="NL" style="flex:1;padding:8px 10px;border:2.5px solid var(--cream-3);border-radius:6px;font-family:var(--font-body);font-size:13px">
              <input name="text_en" value="<?= htmlspecialchars($f['text_en']) ?>" placeholder="EN" style="flex:1;padding:8px 10px;border:2.5px solid var(--cream-3);border-radius:6px;font-family:var(--font-body);font-size:13px">
              <button type="submit" class="btn btn-lime" style="padding:6px 12px">&#10003;</button>
            </form>
            <button class="btn-del" style="position:static" onclick="deleteItem('fact',<?= $f['id'] ?>,this)">×</button>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addFact()">+ feitje toevoegen</button>
      </div>

      <!-- ═══ WORKS ═══ -->
      <div class="panel" id="tab-works">
        <p class="eyebrow">C:\LIANNE\admin\works</p>
        <h1 class="sec-title">Mijn <span class="hl">projecten</span></h1>
        <div id="projects-list" class="sortable-list" data-table="projects">
          <?php foreach ($projects as $proj): ?>
          <div class="card" data-id="<?= $proj['id'] ?>">
            <span class="drag-handle"><i></i><i></i><i></i></span>
            <div class="card-head">
              <h3 class="proj-toggle" onclick="this.classList.toggle('open');this.closest('.card').querySelector('.proj-body').classList.toggle('open')"><?= htmlspecialchars($proj['title_nl']) ?></h3>
              <button class="btn-del" style="position:static" onclick="deleteItem('project',<?= $proj['id'] ?>,this)">×</button>
            </div>
            <div class="proj-body">
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
                <button type="submit" class="btn btn-pink">Project opslaan ✦</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addProject()">+ nieuw project toevoegen</button>
      </div>

      <!-- ═══ SKILLS ═══ -->
      <div class="panel" id="tab-skills">
        <p class="eyebrow">C:\LIANNE\admin\skills</p>
        <h1 class="sec-title">Mijn <span class="hl">skills</span></h1>
        <div id="skills-list" class="sortable-list" data-table="skills">
          <?php foreach ($skills as $s): ?>
          <div class="card" data-id="<?= $s['id'] ?>">
            <span class="drag-handle"><i></i><i></i><i></i></span>
            <button class="btn-del" onclick="deleteItem('skill',<?= $s['id'] ?>,this)">×</button>
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
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addSkill()">+ skill toevoegen</button>
      </div>

      <!-- ═══ EDUCATION ═══ -->
      <div class="panel" id="tab-education">
        <p class="eyebrow">C:\LIANNE\admin\education</p>
        <h1 class="sec-title">Mijn <span class="hl">opleiding</span></h1>
        <div id="education-list" class="sortable-list" data-table="education">
          <?php foreach ($education as $e): ?>
          <div class="card" data-id="<?= $e['id'] ?>">
            <span class="drag-handle"><i></i><i></i><i></i></span>
            <button class="btn-del" onclick="deleteItem('education',<?= $e['id'] ?>,this)">×</button>
            <form onsubmit="return saveForm(this,'update_education')">
              <input type="hidden" name="id" value="<?= $e['id'] ?>">
              <div class="triple">
                <div class="field"><label>Start jaar</label><input name="year_start" value="<?= $e['year_start'] ?>" type="number" min="1990" max="2040"></div>
                <div class="field"><label>Eind jaar</label><input name="year_end" value="<?= $e['year_end'] ?? '' ?>" type="number" min="1990" max="2040" placeholder="leeg = nu"></div>
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
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addEducation()">+ opleiding toevoegen</button>
      </div>

      <!-- ═══ EXPERIENCE ═══ -->
      <div class="panel" id="tab-experience">
        <p class="eyebrow">C:\LIANNE\admin\experience</p>
        <h1 class="sec-title">Werk<span class="hl">ervaring</span></h1>
        <div id="experience-list" class="sortable-list" data-table="experience">
          <?php foreach ($experience as $e): ?>
          <div class="card" data-id="<?= $e['id'] ?>">
            <span class="drag-handle"><i></i><i></i><i></i></span>
            <button class="btn-del" onclick="deleteItem('experience',<?= $e['id'] ?>,this)">×</button>
            <form onsubmit="return saveForm(this,'update_experience')">
              <input type="hidden" name="id" value="<?= $e['id'] ?>">
              <div class="triple">
                <div class="field"><label>Start jaar</label><input name="year_start" value="<?= $e['year_start'] ?>" type="number" min="1990" max="2040"></div>
                <div class="field"><label>Eind jaar</label><input name="year_end" value="<?= $e['year_end'] ?? '' ?>" type="number" min="1990" max="2040" placeholder="leeg = nu"></div>
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
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addExperience()">+ ervaring toevoegen</button>
      </div>

      <!-- ═══ LANGUAGES ═══ -->
      <div class="panel" id="tab-languages">
        <p class="eyebrow">C:\LIANNE\admin\languages</p>
        <h1 class="sec-title">Mijn <span class="hl">talen</span></h1>
        <div id="languages-list" class="sortable-list" data-table="languages">
          <?php foreach ($languages as $l): ?>
          <div class="card" data-id="<?= $l['id'] ?>">
            <span class="drag-handle"><i></i><i></i><i></i></span>
            <button class="btn-del" onclick="deleteItem('language',<?= $l['id'] ?>,this)">×</button>
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
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addLanguage()">+ taal toevoegen</button>
      </div>

      <!-- ═══ CONTACT ═══ -->
      <div class="panel" id="tab-contact">
        <p class="eyebrow">C:\LIANNE\admin\contact</p>
        <h1 class="sec-title">Contact <span class="hl">gegevens</span></h1>
        <div id="contact-list" class="sortable-list" data-table="contact_links">
          <?php foreach ($contacts as $c): ?>
          <div class="card" data-id="<?= $c['id'] ?>">
            <span class="drag-handle"><i></i><i></i><i></i></span>
            <button class="btn-del" onclick="deleteItem('contact',<?= $c['id'] ?>,this)">×</button>
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
          <?php endforeach; ?>
        </div>
        <button class="btn-add" onclick="addContact()">+ contact toevoegen</button>
      </div>

    </div><!-- /win-body -->
  </div><!-- /window -->
</div><!-- /admin -->

<script>
const API = 'crud.php';

function showTab(id) {
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id).classList.add('active');
  event.target.classList.add('active');
}

function toast(msg, err) {
  const t = document.createElement('div');
  t.className = 'toast' + (err ? ' error' : '');
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 2200);
}

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

async function deleteItem(type, id, btn) {
  if (!confirm('Weet je het zeker?')) return;
  const fd = new FormData();
  fd.append('action', 'delete_' + type);
  fd.append('id', id);
  try {
    const res = await fetch(API, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.ok) {
      btn.closest('.card').remove();
      toast('Verwijderd');
    }
  } catch (e) {
    toast('Fout', true);
  }
}

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

// Clock
function updateClock() {
  const now = new Date();
  document.getElementById('clock-time').textContent = now.toLocaleTimeString('nl-NL', {hour:'2-digit',minute:'2-digit'});
  document.getElementById('clock-date').textContent = now.toLocaleDateString('nl-NL', {day:'numeric',month:'short'});
}
updateClock();
setInterval(updateClock, 30000);

// ═══ ADD functions ═══
function addSkill() {
  const html = `<div class="card"><form onsubmit="return saveForm(this,'create_skill')">
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
  </form></div>`;
  document.getElementById('skills-list').insertAdjacentHTML('beforeend', html);
}

function addEducation() {
  const html = `<div class="card"><form onsubmit="return saveForm(this,'create_education')">
    <div class="triple">
      <div class="field"><label>Start jaar</label><input name="year_start" type="number" min="1990" max="2040" required></div>
      <div class="field"><label>Eind jaar</label><input name="year_end" type="number" min="1990" max="2040" placeholder="leeg = nu"></div>
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
  </form></div>`;
  document.getElementById('education-list').insertAdjacentHTML('beforeend', html);
}

function addExperience() {
  const html = `<div class="card"><form onsubmit="return saveForm(this,'create_experience')">
    <div class="triple">
      <div class="field"><label>Start jaar</label><input name="year_start" type="number" min="1990" max="2040" required></div>
      <div class="field"><label>Eind jaar</label><input name="year_end" type="number" min="1990" max="2040" placeholder="leeg = nu"></div>
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
  </form></div>`;
  document.getElementById('experience-list').insertAdjacentHTML('beforeend', html);
}

function addLanguage() {
  const html = `<div class="card"><form onsubmit="return saveForm(this,'create_language')">
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
  </form></div>`;
  document.getElementById('languages-list').insertAdjacentHTML('beforeend', html);
}

function addContact() {
  const html = `<div class="card"><form onsubmit="return saveForm(this,'create_contact')">
    <div class="triple">
      <div class="field"><label>Platform</label>
        <select name="platform"><option>email</option><option>linkedin</option><option>github</option><option>instagram</option></select>
      </div>
      <div class="field"><label>URL</label><input name="url" required></div>
      <div class="field"><label>Label</label><input name="label" required></div>
    </div>
    <button type="submit" class="btn btn-pink">Toevoegen ✦</button>
  </form></div>`;
  document.getElementById('contact-list').insertAdjacentHTML('beforeend', html);
}

function addMoodItem() {
  const type = prompt('Type: photo of note?', 'photo');
  if (!type || !['photo','note'].includes(type)) return;
  const key = prompt('Key naam (uniek, bijv: "selfie", "tekening"):');
  if (!key) return;
  const html = `<div class="card"><form onsubmit="return saveForm(this,'create_mood')">
    <input type="hidden" name="type" value="${type}">
    <input type="hidden" name="key_name" value="${key}">
    <div class="chip-row">
      <span class="chip ${type==='photo'?'fill-blue':'fill-yellow'}">${type}</span>
      <span class="chip">${key}</span>
    </div>
    <div class="dual">
      <div class="field"><label>Tekst</label><input name="caption_nl"></div>
      <div class="field"><label>Text</label><input name="caption_en"></div>
    </div>
    ${type === 'photo' ? `<div class="field"><label>Foto</label><div class="file-area"><input type="file" name="image" accept="image/*"><p>klik om te uploaden</p></div></div>` : ''}
    <button type="submit" class="btn btn-pink">Toevoegen ✦</button>
  </form></div>`;
  document.getElementById('mood-list').insertAdjacentHTML('beforeend', html);
}

function addFact() {
  const html = `<div class="card" style="display:flex;gap:10px;align-items:center;padding:12px 16px">
    <form onsubmit="return saveForm(this,'create_fact')" style="display:flex;gap:8px;flex:1;align-items:center">
      <input name="text_nl" placeholder="NL" required style="flex:1;padding:8px 10px;border:2.5px solid var(--cream-3);border-radius:6px;font-family:var(--font-body);font-size:13px">
      <input name="text_en" placeholder="EN" required style="flex:1;padding:8px 10px;border:2.5px solid var(--cream-3);border-radius:6px;font-family:var(--font-body);font-size:13px">
      <button type="submit" class="btn btn-pink" style="padding:6px 12px">+</button>
    </form>
  </div>`;
  document.getElementById('facts-list').insertAdjacentHTML('beforeend', html);
}

// ═══ DRAG & DROP SORTING ═══
(function() {
  let dragEl = null;
  let dragList = null;
  let placeholder = null;

  function createPlaceholder() {
    const ph = document.createElement('div');
    ph.style.cssText = 'height:4px;background:var(--pink);border-radius:2px;margin:6px 0;transition:none';
    return ph;
  }

  function getCardCenter(card) {
    const r = card.getBoundingClientRect();
    return r.top + r.height / 2;
  }

  document.addEventListener('mousedown', function(e) {
    const handle = e.target.closest('.drag-handle');
    if (!handle) return;
    e.preventDefault();
    dragEl = handle.closest('.card');
    dragList = dragEl.parentElement;
    if (!dragList.classList.contains('sortable-list')) return;
    dragEl.classList.add('dragging');
    placeholder = createPlaceholder();

    const moveHandler = function(e2) {
      const cards = [...dragList.querySelectorAll('.card:not(.dragging)')];
      if (placeholder.parentElement) placeholder.remove();
      let inserted = false;
      for (const card of cards) {
        if (e2.clientY < getCardCenter(card)) {
          dragList.insertBefore(placeholder, card);
          inserted = true;
          break;
        }
      }
      if (!inserted) dragList.appendChild(placeholder);
    };

    const upHandler = function() {
      document.removeEventListener('mousemove', moveHandler);
      document.removeEventListener('mouseup', upHandler);
      if (placeholder.parentElement) {
        dragList.insertBefore(dragEl, placeholder);
        placeholder.remove();
      }
      dragEl.classList.remove('dragging');
      saveOrder(dragList);
      dragEl = null;
      dragList = null;
      placeholder = null;
    };

    document.addEventListener('mousemove', moveHandler);
    document.addEventListener('mouseup', upHandler);
  });

  // Touch support
  document.addEventListener('touchstart', function(e) {
    const handle = e.target.closest('.drag-handle');
    if (!handle) return;
    dragEl = handle.closest('.card');
    dragList = dragEl.parentElement;
    if (!dragList.classList.contains('sortable-list')) return;
    dragEl.classList.add('dragging');
    placeholder = createPlaceholder();

    const moveHandler = function(e2) {
      e2.preventDefault();
      const touch = e2.touches[0];
      const cards = [...dragList.querySelectorAll('.card:not(.dragging)')];
      if (placeholder.parentElement) placeholder.remove();
      let inserted = false;
      for (const card of cards) {
        if (touch.clientY < getCardCenter(card)) {
          dragList.insertBefore(placeholder, card);
          inserted = true;
          break;
        }
      }
      if (!inserted) dragList.appendChild(placeholder);
    };

    const upHandler = function() {
      document.removeEventListener('touchmove', moveHandler);
      document.removeEventListener('touchend', upHandler);
      if (placeholder.parentElement) {
        dragList.insertBefore(dragEl, placeholder);
        placeholder.remove();
      }
      dragEl.classList.remove('dragging');
      saveOrder(dragList);
      dragEl = null;
      dragList = null;
      placeholder = null;
    };

    document.addEventListener('touchmove', moveHandler, { passive: false });
    document.addEventListener('touchend', upHandler);
  }, { passive: true });

  async function saveOrder(list) {
    const table = list.dataset.table;
    const ids = [...list.querySelectorAll('.card[data-id]')].map(c => c.dataset.id);
    if (!table || ids.length === 0) return;
    const fd = new FormData();
    fd.append('action', 'reorder');
    fd.append('table', table);
    fd.append('ids', JSON.stringify(ids));
    try {
      const res = await fetch(API, { method: 'POST', body: fd });
      const data = await res.json();
      if (data.ok) toast('Volgorde opgeslagen ✦');
      else toast(data.error || 'Fout', true);
    } catch (e) {
      toast('Fout bij opslaan volgorde', true);
    }
  }
})();

function addProject() {
  const html = `<div class="card">
    <div class="card-head"><h3>Nieuw project</h3></div>
    <div class="proj-body open">
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
        <button type="submit" class="btn btn-pink">Project toevoegen ✦</button>
      </form>
    </div>
  </div>`;
  document.getElementById('projects-list').insertAdjacentHTML('beforeend', html);
}
</script>
</body>
</html>
