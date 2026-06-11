/* ============================================================
   phone.jsx — LianneOS Mobile
   Phone re-skin: folders become apps. Reuses SectionBody,
   SECTIONS, SEC, STRINGS, LangCtx/useLang from the other files.
   ============================================================ */
const { useEffect: useEffectP, useState: useStateP, useCallback: useCbP } = React;

/* per-app icon look (real accent colors + readable glyph color) */
const APP_META = {
  about:      { bg:'var(--pink)',   fg:'#fff' },
  personal:   { bg:'var(--blue)',   fg:'#fff' },
  works:      { bg:'var(--lime)',   fg:'var(--ink)' },
  skills:     { bg:'var(--yellow)', fg:'var(--ink)' },
  education:  { bg:'var(--blue)',   fg:'#fff' },
  experience: { bg:'var(--pink)',   fg:'#fff' },
  languages:  { bg:'var(--lime)',   fg:'var(--ink)' },
  contact:    { bg:'var(--blue)',   fg:'#fff' },
  cv:         { bg:'var(--yellow)', fg:'var(--ink)' },
};

/* simple line glyphs (UI icons, stroke = currentColor) */
function AppGlyph({ id }){
  const p = { fill:'none', stroke:'currentColor', strokeWidth:2, strokeLinecap:'round', strokeLinejoin:'round' };
  switch(id){
    case 'about': return <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.6" {...p}/><path d="M5 20c0-3.6 3.1-6 7-6s7 2.4 7 6" {...p}/></svg>;
    case 'personal': return <svg viewBox="0 0 24 24"><path d="M12 20s-6.5-4.3-6.5-9A3.5 3.5 0 0 1 12 8.2 3.5 3.5 0 0 1 18.5 11c0 4.7-6.5 9-6.5 9z" {...p}/></svg>;
    case 'works': return <svg viewBox="0 0 24 24"><path d="M3 8.5C3 7.7 3.7 7 4.5 7H9l2 2h8.5c.8 0 1.5.7 1.5 1.5v7c0 .8-.7 1.5-1.5 1.5h-15C3.7 19 3 18.3 3 17.5v-9z" {...p}/></svg>;
    case 'skills': return <svg viewBox="0 0 24 24"><path d="M12 3l8 4.5-8 4.5-8-4.5L12 3z" {...p}/><path d="M4 12l8 4.5 8-4.5M4 16.5L12 21l8-4.5" {...p}/></svg>;
    case 'education': return <svg viewBox="0 0 24 24"><path d="M12 4l9 4.5-9 4.5-9-4.5L12 4z" {...p}/><path d="M7 11v4.2c0 .9 2.4 2.3 5 2.3s5-1.4 5-2.3V11" {...p}/><path d="M21 8.5V14" {...p}/></svg>;
    case 'experience': return <svg viewBox="0 0 24 24"><rect x="3.5" y="8" width="17" height="11" rx="2" {...p}/><path d="M9 8V6.5C9 5.7 9.7 5 10.5 5h3c.8 0 1.5.7 1.5 1.5V8M3.5 13h17" {...p}/></svg>;
    case 'languages': return <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5" {...p}/><path d="M3.5 12h17M12 3.5c2.5 2.4 2.5 14.6 0 17M12 3.5c-2.5 2.4-2.5 14.6 0 17" {...p}/></svg>;
    case 'contact': return <svg viewBox="0 0 24 24"><rect x="3.5" y="5.5" width="17" height="13" rx="2" {...p}/><path d="M4 7.5l8 5.5 8-5.5" {...p}/></svg>;
    case 'cv': return <svg viewBox="0 0 24 24"><path d="M6.5 3.5h7L18 8v12a1 1 0 0 1-1 1H6.5a1 1 0 0 1-1-1V4.5a1 1 0 0 1 1-1z" {...p}/><path d="M13 3.5V8h4.5M8.5 12.5h7M8.5 16h7" {...p}/></svg>;
    default: return null;
  }
}

function StatusBar(){
  const [t, setT] = useStateP(() => new Date());
  useEffectP(() => { const i = setInterval(() => setT(new Date()), 20000); return () => clearInterval(i); }, []);
  const hh = String(t.getHours()).padStart(2,'0');
  const mm = String(t.getMinutes()).padStart(2,'0');
  return (
    <div className="statusbar">
      <span>{hh}:{mm}</span>
      <span className="sb-right">
        <span className="sb-bars"><i/><i/><i/><i/></span>
        <span className="sb-batt"><span/></span>
      </span>
    </div>
  );
}

function AppIcon({ sec, onOpen }){
  const m = APP_META[sec.id];
  const { lang } = useLang();
  const label = STRINGS[lang].titles[sec.id];
  return (
    <button className="app" onClick={() => onOpen(sec.id)} aria-label={label}>
      <span className="app-ico" style={{ '--app-bg':m.bg, '--app-fg':m.fg }}><AppGlyph id={sec.id} /></span>
      <span className="app-label">{label}</span>
    </button>
  );
}

const DOCK_IDS = ['about','works','contact','cv'];

function PhoneApp(){
  const [lang, setLangRaw] = useStateP(() => localStorage.getItem('lianneos.lang') || 'nl');
  const setLang = useCbP((l) => { setLangRaw(l); try{ localStorage.setItem('lianneos.lang', l); }catch(e){} }, []);

  const [active, setActive] = useStateP(null);   // section id of open app
  const [shown, setShown]   = useStateP(false);  // drives enter/exit transition
  const [toast, setToast]   = useStateP(null);

  const showToast = useCbP((msg) => {
    setToast(msg); clearTimeout(showToast._t);
    showToast._t = setTimeout(() => setToast(null), 2000);
  }, []);

  const open = useCbP((id) => {
    setActive(id);
    setShown(false);
    setTimeout(() => setShown(true), 20);
  }, []);

  /* scale the whole phone uniformly so nothing overflows on short/narrow viewports */
  useEffectP(() => {
    const W = 404, H = 876, pad = 24;
    const fit = () => {
      const s = Math.min((window.innerWidth - pad) / W, (window.innerHeight - pad) / H, 1.08);
      document.documentElement.style.setProperty('--phone-scale', s);
    };
    fit();
    window.addEventListener('resize', fit);
    return () => window.removeEventListener('resize', fit);
  }, []);
  const home = useCbP(() => {
    setShown(false);
    setTimeout(() => setActive(null), 300);
  }, []);

  const sec = active ? SEC[active] : null;
  const titles = STRINGS[lang].titles;
  const today = new Date().toLocaleDateString(lang === 'en' ? 'en-GB' : 'nl-NL', { weekday:'short', day:'2-digit', month:'short' });

  return (
   <LangCtx.Provider value={{ lang, setLang }}>
    <div className="phone-stage">
      <div className="phone">
        <div className="phone-screen">
          <div className="scr-grid"></div>
          <div className="island"></div>

          {/* ---------- HOME ---------- */}
          <div className={'home' + (active ? ' dimmed' : '')}>
            <StatusBar />
            <div className="home-head">
              <span className="home-av">L</span>
              <span className="home-hi">
                <b>Lianne</b>
                <span>{STRINGS[lang].role}</span>
              </span>
              <LangSwitch />
            </div>

            <div className="app-grid">
              {SECTIONS.map(s => <AppIcon key={s.id} sec={s} onOpen={open} />)}
            </div>

            <div className="home-sticker s1">★ 2025</div>
            <div className="home-sticker s2">front-end</div>
            <div className="home-sticker s3">tap an app ↑</div>
            <div className="home-sticker s4">portfolio<small>{today}</small></div>

            <div className="dock">
              {DOCK_IDS.map(id => <AppIcon key={id} sec={SEC[id]} onOpen={open} />)}
            </div>

            <div className="home-bar"><button aria-label="home" /></div>
          </div>

          {/* ---------- APP ---------- */}
          {active && (
            <div className={'phone-app' + (shown ? ' show' : '')} style={{ '--accent':sec.acc }}>
              <div className="app-head">
                <StatusBar />
                <div className="app-nav">
                  <button className="back" onClick={home} aria-label="home">&#8249;</button>
                  <span className="a-dot"></span>
                  <span className="a-title">{titles[active]}</span>
                </div>
              </div>
              <div className="phone-body">
                <SectionBody id={active} onToast={showToast} />
              </div>
              <div className="app-home-bar"><button onClick={home} aria-label="home" /></div>
            </div>
          )}

          {toast && <div className="toast">{toast}</div>}
        </div>
      </div>
    </div>
   </LangCtx.Provider>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(<PhoneApp />);
