/* ============================================================
   sections.jsx — data + content renderers for each section
   ============================================================ */
const { useEffect, useRef, useState } = React;

/* ---- section registry (order = desktop icon order) ---- */
const SECTIONS = [
  { id:'about',      label:'about-me',   icon:'photo',  acc:'var(--pink)' },
  { id:'personal',   label:'personal',   icon:'pink',   acc:'var(--pink)' },
  { id:'works',      label:'works',      icon:'lime',   acc:'var(--lime)' },
  { id:'skills',     label:'skills',     icon:'yellow', acc:'var(--yellow)' },
  { id:'education',  label:'education',  icon:'blue',   acc:'var(--blue)' },
  { id:'experience', label:'experience', icon:'pink',   acc:'var(--pink)' },
  { id:'languages',  label:'languages',  icon:'lime',   acc:'var(--lime)' },
  { id:'contact',    label:'contact',    icon:'blue',   acc:'var(--blue)' },
  { id:'cv',         label:'resume.pdf', icon:'yellow', acc:'var(--yellow)' },
];
const SEC = Object.fromEntries(SECTIONS.map(s => [s.id, s]));

/* ---- animated proficiency bar ---- */
function Bar({ name, pct, color }){
  const ref = useRef(null);
  useEffect(() => {
    const t = setTimeout(() => { if(ref.current) ref.current.style.width = pct + '%'; }, 80);
    return () => clearTimeout(t);
  }, [pct]);
  return (
    <div className="skill">
      <div className="top"><span className="name">{name}</span><span className="pct">{pct}%</span></div>
      <div className="bar"><span ref={ref} style={{ '--bc': color }}></span></div>
    </div>
  );
}

/* ---- proficiency dots (languages) ---- */
function Dots({ n }){
  return <span className="dots">{[0,1,2,3,4].map(i => <i key={i} className={i < n ? 'on' : ''}/>)}</span>;
}

/* ---- works: shared tags + per-project case-study content ---- */
const WORK_TAGS = [
  ['HTML','CSS','JS'],
  ['PHP','CSS','SQL'],
  ['WordPress','PHP','CSS','SQL'],
  ['HTML','PHP','CSS','JS'],
  ['React','SCSS','TypeScript','JSON','SQL'],
  ['Vue','Nuxt','SQL','TypeScript'],
];

const DETAIL_UI = {
  nl:{ back:'terug naar projecten', role:'Rol', type:'Type', dur:'Duur', date:'Datum',
       done:'Wat ik deed', stack:'Gebruikte technieken', gallery:'Meer beelden',
       galleryNote:'voorbeeld — ruimte voor extra screenshots', live:'Live bekijken', source:'Broncode' },
  en:{ back:'back to projects', role:'Role', type:'Type', dur:'Duration', date:'Date',
       done:'What I did', stack:'Tech used', gallery:'More visuals',
       galleryNote:'placeholder — space for extra screenshots', live:'View live', source:'Source code' },
};

const WORK_DETAIL = {
  nl:[
    { role:'Soloproject', type:'Statische website', dur:'± 1 week',
      intro:'Mijn allereerste webpagina, helemaal vanaf nul opgebouwd met pure HTML en CSS. Een klein project waarin ik de basis van semantische structuur, typografie en kleurgebruik onder de knie kreeg.',
      bullets:['Pagina opgebouwd met semantische HTML','Layout en kleuren volledig in CSS gestyled','Responsive gemaakt voor mobiel en desktop'],
      outcome:'De basis waarop al mijn latere projecten verder bouwen.' },
    { role:'Schoolproject', type:'Webshop', dur:'± 4 weken',
      intro:'Een volledig werkende webshop met productoverzicht, winkelmandje en afrekenflow. Producten en bestellingen worden opgeslagen in een database en aangestuurd met PHP.',
      bullets:['Productoverzicht uit een MySQL-database geladen','Winkelmandje met sessies in PHP','Afrekenflow met formuliervalidatie'],
      outcome:'Mijn eerste echte kennismaking met back-end en databases.' },
    { role:'Soloproject', type:'CMS-website', dur:'± 3 weken',
      intro:'Een volledig ingerichte konijnen-blog gebouwd in WordPress, met een eigen thema, paginabeheer en aanpasbare content zodat de eigenaar zelf blogs kan plaatsen.',
      bullets:['Eigen WordPress-thema opgezet en gestyled','Pagina- en blogstructuur ingericht','Content beheerbaar gemaakt voor de klant'],
      outcome:'Een site die de eigenaar volledig zelf kan onderhouden.' },
    { role:'Duoproject', type:'Browsergame', dur:'± 5 weken',
      intro:'Een co-op browsergame waarin twee spelers samenwerken om door een winters level te komen — geïnspireerd op het bekende Fireboy & Watergirl-concept, volledig speelbaar in de browser.',
      bullets:['Spellogica en collision-detection in JavaScript','Besturing voor twee spelers op één toetsenbord','Levels, obstakels en doelen ontworpen'],
      outcome:'Een speelbaar spel waar je samen doorheen puzzelt.' },
    { role:'Stageproject', type:'Bestel-kiosk', dur:'± 8 weken',
      intro:'Een touch-vriendelijke bestel-kiosk voor een vegetarische zaak. Gebruikers stellen zelf hun bestelling samen en rekenen af. Gebouwd in React met TypeScript.',
      bullets:['Touch-interface ontworpen voor zelf bestellen','Menu en bestellingen via een API gekoppeld','State-beheer met React en TypeScript'],
      outcome:'Een vlotte selfservice-ervaring van menu tot afrekenen.' },
    { role:'Schoolproject', type:'Webapp', dur:'± 6 weken',
      intro:'Een app waarmee gebruikers studenten kunnen beoordelen. Scores en feedback worden opgeslagen en overzichtelijk teruggekoppeld. Gebouwd met Vue en Nuxt.',
      bullets:['Inloggen met rollen (student / docent)','Beoordelingen opslaan in een database','Resultaten visueel teruggekoppeld'],
      outcome:'Inzicht in feedback, in één overzichtelijke app.' },
  ],
  en:[
    { role:'Solo project', type:'Static website', dur:'± 1 week',
      intro:'My very first web page, built completely from scratch with pure HTML and CSS. A small project where I got to grips with semantic structure, typography and colour.',
      bullets:['Built the page with semantic HTML','Styled the full layout and colours in CSS','Made it responsive for mobile and desktop'],
      outcome:'The foundation all my later projects build on.' },
    { role:'School project', type:'Webshop', dur:'± 4 weeks',
      intro:'A fully working webshop with a product overview, shopping cart and checkout flow. Products and orders are stored in a database and driven by PHP.',
      bullets:['Loaded the product overview from a MySQL database','Shopping cart with sessions in PHP','Checkout flow with form validation'],
      outcome:'My first real taste of back-end and databases.' },
    { role:'Solo project', type:'CMS website', dur:'± 3 weeks',
      intro:'A fully built rabbit blog made in WordPress, with a custom theme, page management and editable content so the owner can post blogs themselves.',
      bullets:['Set up and styled a custom WordPress theme','Arranged the page and blog structure','Made content manageable for the client'],
      outcome:'A site the owner can fully maintain on their own.' },
    { role:'Duo project', type:'Browser game', dur:'± 5 weeks',
      intro:'A co-op browser game where two players work together to get through a wintry level — inspired by the well-known Fireboy & Watergirl concept, fully playable in the browser.',
      bullets:['Game logic and collision detection in JavaScript','Two-player controls on a single keyboard','Designed levels, obstacles and goals'],
      outcome:'A playable game you puzzle through together.' },
    { role:'Internship project', type:'Order kiosk', dur:'± 8 weeks',
      intro:'A touch-friendly order kiosk for a vegetarian eatery. Users put together their own order and pay. Built in React with TypeScript.',
      bullets:['Designed a touch interface for self-ordering','Connected menu and orders through an API','State management with React and TypeScript'],
      outcome:'A smooth self-service experience from menu to checkout.' },
    { role:'School project', type:'Web app', dur:'± 6 weeks',
      intro:'An app that lets users rate students. Scores and feedback are stored and reported back clearly. Built with Vue and Nuxt.',
      bullets:['Login with roles (student / teacher)','Stored ratings in a database','Reported results back visually'],
      outcome:'Insight into feedback, in one clear app.' },
  ],
};

function workSlug(name){
  return name.toLowerCase().replace(/&/g,'en').replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'');
}

/* ---- works detail (case study) ---- */
function WorkDetail({ p, d, ui, onBack, onToast }){
  return (
    <div className="section work-detail" style={{ maxWidth:820 }}>
      <button className="wd-back" onClick={onBack}><span aria-hidden="true">←</span> {ui.back}</button>
      <p className="eyebrow">C:\LIANNE\works\{workSlug(p.name)}</p>
      <h1 className="sec-title">{p.name}</h1>

      <div className="wd-meta">
        <span><b>{ui.date}</b>{p.date}</span>
        <span><b>{ui.role}</b>{d.role}</span>
        <span><b>{ui.type}</b>{d.type}</span>
        <span><b>{ui.dur}</b>{d.dur}</span>
      </div>

      <div className="wd-hero">
        {p.img
          ? <img src={p.img} alt={p.name} draggable="false" />
          : <span className="wd-ph">{ui.galleryNote}</span>}
      </div>

      <p className="wd-intro">{d.intro}</p>

      <div className="wd-grid">
        <div className="wd-block">
          <h3>{ui.done}</h3>
          <ul>{d.bullets.map((b, i) => <li key={i}>{b}</li>)}</ul>
        </div>
        <div className="wd-block wd-side">
          <h3>{ui.stack}</h3>
          <div className="tagrow">{p.tags.map(t => <span key={t}>{t}</span>)}</div>
          <p className="wd-outcome">{d.outcome}</p>
        </div>
      </div>

      <h3 className="wd-gh">{ui.gallery}</h3>
      <div className="wd-gallery">
        <span className="wd-thumb"><i>{ui.galleryNote}</i></span>
        <span className="wd-thumb"><i>{ui.galleryNote}</i></span>
      </div>

      <div className="wd-cta">
        <button className="btn-primary" onClick={() => onToast('Live demo ✦')}>{ui.live} &rarr;</button>
        <button className="proj-btn" onClick={() => onToast('Broncode ✦')}>{ui.source}</button>
      </div>
    </div>
  );
}

/* ---- works section (timeline + detail switch) ---- */
function WorksSection({ T, lang, onToast }){
  const w = T.works;
  const dates = WORK_DATES[lang];
  const projects = w.items.map((it, i) => ({ ...it, date:dates[i], tags:WORK_TAGS[i], idx:i }));
  const [sel, setSel] = useState(null);

  if(sel !== null){
    return <WorkDetail p={projects[sel]} d={WORK_DETAIL[lang][sel]} ui={DETAIL_UI[lang]}
                       onBack={() => setSel(null)} onToast={onToast} />;
  }

  return (
    <div className="section" style={{ maxWidth:820 }}>
      <p className="eyebrow">C:\LIANNE\works</p>
      <h1 className="sec-title">{w.title[0]}<span className="hl">{w.title[1]}</span></h1>
      <p className="lead">{w.lead}</p>
      <div className="timeline work-timeline">
        {projects.slice().reverse().map((p) => (
          <div className="tl-item work-item" key={p.idx}>
            <span className="yr">{p.date}</span>
            <div className="work-card" role="button" tabIndex={0}
                 onClick={() => setSel(p.idx)}
                 onKeyDown={(e) => { if(e.key === 'Enter' || e.key === ' '){ e.preventDefault(); setSel(p.idx); } }}>
              <div className="work-shot">
                {p.img
                  ? <img src={p.img} alt={p.name} />
                  : <span className="tag">{w.shot} {p.name}</span>}
              </div>
              <div className="work-meta">
                <h3>{p.name}</h3>
                <p>{p.desc}</p>
                <div className="tagrow">{p.tags.map(t => <span key={t}>{t}</span>)}</div>
                <span className="proj-btn" aria-hidden="true">{w.view} &rarr;</span>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

/* ---- section content ---- */
function SectionBody({ id, onToast }){
  const { lang } = useLang();
  const T = STRINGS[lang];

  switch(id){

    case 'about': {
      const a = T.about;
      const hi = lang === 'en' ? "hi! ✨" : "hoi! ✨";
      return (
        <div className="section">
          <p className="eyebrow">C:\LIANNE\about-me</p>

          <div className="about-hero">
            <div className="about-text">
              <h1 className="sec-title">{a.greet[0]}<span className="hl">{a.greet[1]}</span></h1>
              <p className="eyebrow" style={{ marginBottom:14 }}>{T.role}</p>
              <p className="lead">{a.lead}</p>
            </div>

            <div className="about-char">
              <span className="char-bubble">{hi}</span>
              <img className="char-img" src="assets/lianne-char3.png" alt="Lianne" draggable="false" />
              <span className="char-pad"></span>
            </div>
          </div>

          <div className="about-row">
            <div className="polaroid">
              <span className="tape"></span>
              <div className="photo-mini">
                {/* drop your photo: replace label with <img src="..."/> */}
                <span className="ph-label">jouw foto</span>
              </div>
              <span className="cap">Lianne</span>
            </div>
            <div className="about-side">
              <div className="chiprow">
                <span className="chip fill-pink">{a.chips[0]}</span>
                <span className="chip fill-yellow">{a.chips[1]}</span>
                <span className="chip fill-lime">{a.chips[2]}</span>
                <span className="chip">{a.chips[3]}</span>
              </div>
              <div className="callout">
                {a.callout[0]}<span className="q">{a.callout[1]}</span>{a.callout[2]}
              </div>
            </div>
          </div>
        </div>
      );
    }

    case 'personal': {
      const p = T.personal;
      const pin = (c) => <span className={'pin ' + c}><span className="head"></span><span className="stem"></span></span>;
      return (
        <div className="section personal-sec" style={{ maxWidth:880 }}>
          <p className="eyebrow">C:\LIANNE\personal</p>
          <h1 className="sec-title">{p.title[0]}<span className="hl">{p.title[1]}</span></h1>
          <p className="lead">{p.lead}</p>

          <div className="frame">
            <div className="board">
              <div className="board-items">

                <div className="pinned">
                  {pin('pink')}
                  <div className="pcard">
                    <div className="pcard-photo"><img src="assets/lianne-character.png" alt="Lianne" draggable="false" /></div>
                    <div className="pcard-cap">{p.caps.me}</div>
                  </div>
                </div>

                <div className="pinned">
                  {pin('yellow')}
                  <div className="snote cream">{p.notes.music}</div>
                </div>

                <div className="pinned">
                  {pin('lime')}
                  <div className="pcard">
                    <image-slot id="pb-1" placeholder="Sleep een foto hierheen"></image-slot>
                    <div className="pcard-cap">{p.caps.weekend}</div>
                  </div>
                </div>

                <div className="pinned">
                  <span className="washi pink"></span>
                  <div className="pcard">
                    <image-slot id="pb-2" placeholder="Sleep een foto hierheen"></image-slot>
                    <div className="pcard-cap">{p.caps.setup}</div>
                  </div>
                </div>

                <div className="pinned">
                  {pin('blue')}
                  <div className="snote lime">{p.notes.todo}</div>
                </div>

                <div className="pinned">
                  {pin('pink')}
                  <div className="pcard">
                    <image-slot id="pb-3" placeholder="Sleep een foto hierheen"></image-slot>
                    <div className="pcard-cap">{p.caps.coffee}</div>
                  </div>
                </div>

                <div className="pinned">
                  <span className="washi blue"></span>
                  <div className="pcard">
                    <image-slot id="pb-4" placeholder="Sleep een foto hierheen"></image-slot>
                    <div className="pcard-cap">{p.caps.creative}</div>
                  </div>
                </div>

                <div className="pinned">
                  {pin('yellow')}
                  <div className="snote pink">{p.notes.quote}</div>
                </div>

                <div className="pinned">
                  {pin('lime')}
                  <div className="pcard">
                    <image-slot id="pb-5" placeholder="Sleep een foto hierheen"></image-slot>
                    <div className="pcard-cap">{p.caps.friends}</div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <p className="cv-note">{p.note}</p>
        </div>
      );
    }

    case 'skills':
      return (
        <div className="section">
          <p className="eyebrow">C:\LIANNE\skills</p>
          <h1 className="sec-title">{T.skills.title[0]}<span className="hl">{T.skills.title[1]}</span></h1>
          <p className="lead">{T.skills.lead}</p>
          <div className="skill-grid">
            {T.skills.items.map(s => (
              <div className="skill-card" key={s.name} style={{ '--bc':s.color }}>
                <div className="skill-card-top">
                  <span className="sq"/>
                  <span className="skill-card-name">{s.name}</span>
                </div>
                <p className="skill-card-use">{s.use}</p>
              </div>
            ))}
          </div>
        </div>
      );

    case 'works':
      return <WorksSection T={T} lang={lang} onToast={onToast} />;

    case 'education': {
      const e = T.education;
      return (
        <div className="section">
          <p className="eyebrow">C:\LIANNE\education</p>
          <h1 className="sec-title">{e.title[0]}<span className="hl">{e.title[1]}</span></h1>
          <p className="lead" style={{ fontFamily:'var(--font-mono)', fontSize:12.5 }}>{e.note}</p>
          <div className="timeline">
            {e.items.map((it, i) => (
              <div className="tl-item" key={i}>
                <span className="yr">{it.yr}</span>
                <h3>{it.h}</h3>
                <div className="org">{it.org}</div>
                {it.p && <p>{it.p}</p>}
              </div>
            ))}
          </div>
        </div>
      );
    }

    case 'experience': {
      const e = T.experience;
      return (
        <div className="section">
          <p className="eyebrow">C:\LIANNE\experience</p>
          <h1 className="sec-title">{e.title[0]}<span className="hl">{e.title[1]}</span></h1>
          <p className="lead" style={{ fontFamily:'var(--font-mono)', fontSize:12.5 }}>{e.note}</p>
          <div className="timeline">
            {e.items.map((it, i) => (
              <div className="tl-item" key={i}>
                <span className="yr">{it.yr}</span>
                <h3>{it.h}</h3>
                <div className="org">{it.org}</div>
                {it.p && <p>{it.p}</p>}
              </div>
            ))}
          </div>
        </div>
      );
    }

    case 'languages': {
      const l = T.languages;
      return (
        <div className="section">
          <p className="eyebrow">C:\LIANNE\languages</p>
          <h1 className="sec-title">{l.title[0]}<span className="hl">{l.title[1]}</span></h1>
          <p className="lead">{l.lead}</p>
          <div className="lang-grid">
            {l.rows.map(r => (
              <div className="lang-card" key={r.name} style={{ '--bc':r.color }}>
                <div className="lang-card-top">
                  <span className="sq"/>
                  <span className="lang-card-name">{r.name}</span>
                  <span className="lang-card-level">{r.level}</span>
                </div>
                <Dots n={r.n}/>
                <p className="lang-card-note">{r.note}</p>
              </div>
            ))}
          </div>
          <div className="lang-note">{l.note}</div>
        </div>
      );
    }

    case 'contact': {
      const c = T.contact;
      const meta = [
        { bg:'var(--pink)', icon:'@', href:'mailto:lianne@example.com' },
        { bg:'var(--blue)', icon:'in', href:'#' },
        { bg:'var(--ink)',  icon:'</>', href:'#' },
        { bg:'var(--lime)', icon:'ig', color:'var(--ink)', href:'#' },
      ];
      return (
        <div className="section">
          <p className="eyebrow">C:\LIANNE\contact</p>
          <h1 className="sec-title">{c.title[0]}<span className="hl">{c.title[1]}</span></h1>
          <p className="lead">{c.lead}</p>
          <div className="contact-list">
            {c.rows.map(([label,val], i) => (
              <a className="contact-row" key={label} href={meta[i].href}
                 onClick={(ev)=>{ if(meta[i].href==='#'){ ev.preventDefault(); } }}>
                <span className="ci" style={{ background:meta[i].bg, color:meta[i].color || '#fff' }}>{meta[i].icon}</span>
                <span className="ct"><b>{label}</b><span>{val}</span></span>
              </a>
            ))}
          </div>
        </div>
      );
    }

    case 'cv': {
      const v = T.cv;
      const skills = ['HTML','CSS','React','PHP','SQL'];
      const dates = WORK_DATES[lang];
      const projects = T.works.items.map((it, i) => ({ ...it, date:dates[i] }))
        .slice().reverse();
      const entry = (e, key) => (
        <div className="cvx-entry" key={key}>
          <span className="cvx-yr">{e.yr}</span>
          <h4>{e.h}</h4>
          {e.org && <div className="cvx-org">{e.org}</div>}
          {e.p && <p>{e.p}</p>}
        </div>
      );
      return (
        <div className="section cv-wrap">
          <p className="eyebrow" style={{ textAlign:'left' }}>C:\LIANNE\resume.pdf</p>
          <div className="cvx-head">
            <h1 className="sec-title" style={{ textAlign:'left', margin:0 }}>{v.title[0]}<span className="hl">{v.title[1]}</span></h1>
            <button className="btn-primary" onClick={() => onToast(v.dlToast)}>↓ {v.download}</button>
          </div>

          <div className="cvx-doc">
            <aside className="cvx-side">
              <div className="cvx-av">L</div>
              <div className="cvx-name">Lianne</div>
              <div className="cvx-role">{T.role}</div>

              <h3 className="cvx-h">{T.titles.contact}</h3>
              {T.contact.rows.map(([k, val]) => (
                <span className="cvx-ci" key={k}>{val}</span>
              ))}

              <h3 className="cvx-h">{T.titles.skills}</h3>
              <div className="cvx-pills">
                {skills.map(s => <span className="cvx-pill" key={s}>{s}</span>)}
              </div>

              <h3 className="cvx-h">{T.titles.languages}</h3>
              {T.languages.rows.map(r => (
                <div className="cvx-lang" key={r.name}><span>{r.name}</span><Dots n={r.n} /></div>
              ))}
            </aside>

            <main className="cvx-main">
              <h3 className="cvx-mh">{lang === 'en' ? 'Profile' : 'Profiel'}</h3>
              <p className="cvx-lead">{T.about.lead}</p>

              <h3 className="cvx-mh">{T.titles.experience}</h3>
              {T.experience.items.map((e, i) => entry(e, 'x' + i))}

              <h3 className="cvx-mh">{T.titles.education}</h3>
              {T.education.items.map((e, i) => entry(e, 'd' + i))}

              <h3 className="cvx-mh">{T.titles.works}</h3>
              {projects.map((p, i) => entry({ yr:p.date, h:p.name, p:p.desc }, 'p' + i))}
            </main>
          </div>

          <p className="cv-note">{v.note}</p>
        </div>
      );
    }

    default: return null;
  }
}

Object.assign(window, { SECTIONS, SEC, SectionBody, Bar, Dots });
