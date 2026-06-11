/* ============================================================
   os.jsx — desktop chrome: icons, windows, taskbar, start menu
   ============================================================ */
const { useEffect: useEffectOS, useRef: useRefOS, useState: useStateOS } = React;

/* ---------- folder / icon glyph ---------- */
function Glyph({ kind }){
  if(kind === 'photo'){
    return <div className="photochip">L</div>;
  }
  const color = { yellow:'#ffd02e', pink:'#ff7ac0', lime:'#c2f25a', blue:'#7aa6ff' }[kind] || '#ffd02e';
  return (
    <div className="folder" style={{ '--fc': color }}>
      <span className="tab"/><span className="body"/><span className="seam"/>
    </div>
  );
}

/* ---------- desktop icon ---------- */
function DesktopIcon({ sec, onOpen }){
  return (
    <button className="icon" onDoubleClick={() => onOpen(sec.id)} onClick={(e)=>{ if(e.detail===0) onOpen(sec.id); }} title={`Open ${sec.title}`}>
      <span className="glyph"><Glyph kind={sec.icon} /></span>
      <span className="label">{sec.label}</span>
    </button>
  );
}
/* single-tap open on touch */
function TapIcon({ sec, onOpen }){
  return (
    <button className="icon" onClick={() => onOpen(sec.id)} title={`Open ${sec.title}`}>
      <span className="glyph"><Glyph kind={sec.icon} /></span>
      <span className="label">{sec.label}</span>
    </button>
  );
}

/* ---------- window ---------- */
function Win({ win, focused, isMobile, onFocus, onClose, onMin, onMove, onResize, onTab, onToast }){
  const sec = SEC[win.section];
  const dragRef = useRefOS(null);

  const startDrag = (e) => {
    if(isMobile) return;
    onFocus(win.id);
    const ev = e.touches ? e.touches[0] : e;
    const start = { x:ev.clientX, y:ev.clientY, wx:win.x, wy:win.y };
    const move = (m) => {
      const mv = m.touches ? m.touches[0] : m;
      onMove(win.id, start.wx + (mv.clientX - start.x), start.wy + (mv.clientY - start.y));
    };
    const up = () => {
      window.removeEventListener('mousemove', move); window.removeEventListener('mouseup', up);
      window.removeEventListener('touchmove', move); window.removeEventListener('touchend', up);
    };
    window.addEventListener('mousemove', move); window.addEventListener('mouseup', up);
    window.addEventListener('touchmove', move, { passive:false }); window.addEventListener('touchend', up);
  };

  const startResize = (e) => {
    if(isMobile) return;
    e.stopPropagation(); onFocus(win.id);
    const ev = e.touches ? e.touches[0] : e;
    const start = { x:ev.clientX, y:ev.clientY, w:win.w, h:win.h };
    const move = (m) => {
      const mv = m.touches ? m.touches[0] : m;
      onResize(win.id, Math.max(300, start.w + (mv.clientX - start.x)), Math.max(220, start.h + (mv.clientY - start.y)));
    };
    const up = () => {
      window.removeEventListener('mousemove', move); window.removeEventListener('mouseup', up);
    };
    window.addEventListener('mousemove', move); window.addEventListener('mouseup', up);
  };

  const style = isMobile ? { zIndex:win.z } : { left:win.x, top:win.y, width:win.w, height:win.h, zIndex:win.z };

  return (
    <div ref={dragRef} className={'window' + (focused ? ' focused' : '')} style={style}
         onMouseDown={() => onFocus(win.id)}>
      <div className="titlebar" style={{ '--acc': sec.acc }} onMouseDown={startDrag} onTouchStart={startDrag}>
        <span className="dot"/>
        <span className="path">C:\LIANNE\{sec.label}</span>
        <span className="win-controls">
          <button title="minimize" onMouseDown={(e)=>e.stopPropagation()} onClick={() => onMin(win.id)}>&minus;</button>
          <button className="close" title="close" onMouseDown={(e)=>e.stopPropagation()} onClick={() => onClose(win.id)}>&times;</button>
        </span>
      </div>

      <div className="tabs">
        {SECTIONS.map(s => (
          <button key={s.id}
            className={'tab-btn' + (s.id === win.section ? ' active' : '')}
            style={{ '--tab-acc': s.acc }}
            onMouseDown={(e)=>e.stopPropagation()}
            onClick={() => onTab(win.id, s.id)}>
            {s.label}
          </button>
        ))}
      </div>

      <div className="win-body">
        <SectionBody id={win.section} onToast={onToast} />
      </div>

      {!isMobile && <div className="resize" onMouseDown={startResize}/>}
    </div>
  );
}

/* ---------- language switcher ---------- */
function LangSwitch(){
  const { lang, setLang } = useLang();
  return (
    <div className="langswitch" role="group" aria-label="language">
      <button className={lang==='nl' ? 'on' : ''} onClick={() => setLang('nl')}>NL</button>
      <button className={lang==='en' ? 'on' : ''} onClick={() => setLang('en')}>EN</button>
    </div>
  );
}

/* ---------- taskbar ---------- */
function Taskbar({ windows, focusedId, startOpen, onStart, onFocus, onToast }){
  const [time, setTime] = useStateOS(() => new Date());
  useEffectOS(() => { const t = setInterval(() => setTime(new Date()), 1000 * 30); return () => clearInterval(t); }, []);
  const hh = String(time.getHours()).padStart(2,'0');
  const mm = String(time.getMinutes()).padStart(2,'0');
  const date = time.toLocaleDateString('nl-NL', { day:'2-digit', month:'short' });

  return (
    <div className="taskbar">
      <button className={'start' + (startOpen ? ' open' : '')} onMouseDown={(e)=>e.stopPropagation()} onClick={onStart}>
        <span className="gem"/><span className="stxt">Lianne</span>
      </button>
      <div className="task-open">
        {windows.map(w => (
          <button key={w.id} className={'task-chip' + (w.id === focusedId ? ' active' : '')}
                  onClick={() => onFocus(w.id)}>
            <span className="cdot" style={{ '--acc': SEC[w.section].acc }}/>{SEC[w.section].label}
          </button>
        ))}
      </div>
      <div className="tray">
        <LangSwitch />
        <a href="#" onClick={(e)=>{e.preventDefault(); onToast('LinkedIn — voorbeeld');}} title="LinkedIn">in</a>
        <a href="#" onClick={(e)=>{e.preventDefault(); onToast('GitHub — voorbeeld');}} title="GitHub" style={{ background:'var(--cream)' }}>{'</>'}</a>
        <a href="#" onClick={(e)=>{e.preventDefault(); onToast('Behance — voorbeeld');}} title="Behance" style={{ background:'var(--blue)', color:'#fff' }}>Be</a>
        <a href="#" onClick={(e)=>{e.preventDefault(); onToast('Instagram — voorbeeld');}} title="Instagram" style={{ background:'var(--pink)', color:'#fff' }}>ig</a>
        <span className="clock">{hh}:{mm}<small>{date}</small></span>
      </div>
    </div>
  );
}

/* ---------- start menu ---------- */
function StartMenu({ onOpen }){
  const { lang } = useLang();
  const titles = STRINGS[lang].titles;
  return (
    <div className="startmenu">
      <div className="sm-head">
        <span className="av">L</span>
        <span><b>Lianne</b><span>{STRINGS[lang].role}</span></span>
      </div>
      <div className="sm-list">
        {SECTIONS.map(s => (
          <button key={s.id} onClick={() => onOpen(s.id)}>
            <span className="si" style={{ width:26, height:22 }}><Glyph kind={s.icon}/></span>
            {titles[s.id]}
          </button>
        ))}
      </div>
      <div className="sm-foot">LianneOS &middot; v2025</div>
    </div>
  );
}

Object.assign(window, { Glyph, DesktopIcon, TapIcon, Win, Taskbar, StartMenu, LangSwitch });
