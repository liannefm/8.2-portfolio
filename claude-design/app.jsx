/* ============================================================
   app.jsx — window manager + mount
   ============================================================ */
const { useEffect: useEffectApp, useState: useStateApp, useCallback } = React;

/* desktop mascot standing on a cool platform */
function Mascot({ onToast }){
  const { lang } = useLang();
  const [greet, setGreet] = useStateApp(false);
  const line = lang === 'en' ? "hi, I'm Lianne! ✨" : 'hoi, ik ben Lianne! ✨';
  const toastMsg = lang === 'en' ? 'Happy coding! ✨' : 'Veel codeerplezier! ✨';
  const wave = () => {
    setGreet(true);
    onToast(toastMsg);
    clearTimeout(Mascot._t); Mascot._t = setTimeout(() => setGreet(false), 2400);
  };
  return (
    <div className={'mascot' + (greet ? ' greet' : '')}
         onMouseEnter={() => setGreet(true)} onMouseLeave={() => setGreet(false)}>
      <div className="bubble">{line}</div>
      <img className="char" src="assets/lianne-character.png" alt="Lianne mascot"
           draggable="false" onClick={wave} />
      <div className="platform">
        <div className="coin"></div>
        <span className="tagchip">Lianne</span>
      </div>
    </div>
  );
}

function useIsMobile(){  const [m, setM] = useStateApp(() => window.matchMedia('(max-width:760px)').matches);
  useEffectApp(() => {
    const mq = window.matchMedia('(max-width:760px)');
    const fn = e => setM(e.matches);
    mq.addEventListener('change', fn);
    return () => mq.removeEventListener('change', fn);
  }, []);
  return m;
}

let WIN_SEQ = 1;

function App(){
  const isMobile = useIsMobile();
  const [lang, setLangRaw] = useStateApp(() => localStorage.getItem('lianneos.lang') || 'nl');
  const setLang = useCallback((l) => { setLangRaw(l); try{ localStorage.setItem('lianneos.lang', l); }catch(e){} }, []);
  const [windows, setWindows] = useStateApp([]);
  const [focusedId, setFocusedId] = useStateApp(null);
  const [topZ, setTopZ] = useStateApp(10);
  const [startOpen, setStartOpen] = useStateApp(false);
  const [toast, setToast] = useStateApp(null);

  const showToast = useCallback((msg) => {
    setToast(msg);
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => setToast(null), 2200);
  }, []);

  /* cascade position for new desktop windows — scales up on big monitors */
  const nextPos = (n) => {
    const vw = window.innerWidth, vh = window.innerHeight;
    const tb = vw >= 1500 ? 64 : 56;            // taskbar height
    // wide enough that all 9 folder tabs (personal … resume.pdf) fit without scrolling
    const w = Math.min(1140, vw - 24, Math.max(940, Math.round(vw * 0.66)));
    const h = Math.min(860, Math.max(480, Math.round((vh - tb) * 0.82)));
    // perfectly centered in the desktop area
    const cx = Math.round((vw - w) / 2);
    const cy = Math.round(((vh - tb) - h) / 2);
    // gentle cascade only for stacking extra windows
    const off = (n % 5) * 26;
    return { x: Math.max(12, cx + off), y: Math.max(12, cy + off), w, h };
  };

  const openSection = useCallback((id) => {
    setStartOpen(false);
    setWindows(prev => {
      const existing = prev.find(w => w.section === id && !w.minimized);
      if(existing){
        const z = topZ + 1; setTopZ(z); setFocusedId(existing.id);
        return prev.map(w => w.id === existing.id ? { ...w, z } : w);
      }
      // if minimized version exists, restore + switch
      const min = prev.find(w => w.section === id && w.minimized);
      const z = topZ + 1; setTopZ(z);
      if(min){
        setFocusedId(min.id);
        return prev.map(w => w.id === min.id ? { ...w, minimized:false, z } : w);
      }
      // on mobile keep a single window — replace section of focused/last, else new
      if(isMobile){
        setFocusedId('m');
        return [{ id:'m', section:id, x:0, y:0, w:0, h:0, z:1, minimized:false }];
      }
      const id2 = 'w' + (WIN_SEQ++);
      const pos = nextPos(prev.length);
      setFocusedId(id2);
      return [...prev, { id:id2, section:id, ...pos, z, minimized:false }];
    });
  }, [topZ, isMobile]);

  const focus = useCallback((wid) => {
    setWindows(prev => {
      const z = topZ + 1; setTopZ(z);
      return prev.map(w => w.id === wid ? { ...w, minimized:false, z } : w);
    });
    setFocusedId(wid);
  }, [topZ]);

  const close = useCallback((wid) => {
    setWindows(prev => prev.filter(w => w.id !== wid));
  }, []);

  const minimize = useCallback((wid) => {
    setWindows(prev => prev.map(w => w.id === wid ? { ...w, minimized:true } : w));
    setFocusedId(f => f === wid ? null : f);
  }, []);

  const move = useCallback((wid, x, y) => {
    setWindows(prev => prev.map(w => w.id === wid ? { ...w, x, y } : w));
  }, []);

  const resize = useCallback((wid, w, h) => {
    setWindows(prev => prev.map(win => win.id === wid ? { ...win, w, h } : win));
  }, []);

  const switchTab = useCallback((wid, sectionId) => {
    setWindows(prev => prev.map(w => w.id === wid ? { ...w, section:sectionId } : w));
  }, []);

  /* open About on first load */
  useEffectApp(() => { openSection('about'); /* eslint-disable-next-line */ }, [isMobile]);

  const visible = windows.filter(w => !w.minimized);

  return (
   <LangCtx.Provider value={{ lang, setLang }}>
    <div className="os" onMouseDown={() => startOpen && setStartOpen(false)}>

      {isMobile && (
        <div className="m-topbar">
          <span className="mb-av">L</span>
          <b>LianneOS</b>
          <LangSwitch />
        </div>
      )}

      <div className="desktop">
        <div className="wallmark">PORT<b>FOLIO</b></div>
        <div className="sticker s1">★ 2025</div>
        <div className="sticker s2">front-end</div>
        <div className="sticker s3">open me ↓</div>

        <div className="icons">
          {SECTIONS.map(s => (
            isMobile
              ? <TapIcon key={s.id} sec={s} onOpen={openSection} />
              : <DesktopIcon key={s.id} sec={s} onOpen={openSection} />
          ))}
        </div>

        {visible.map(w => (
          <Win key={w.id} win={w} focused={w.id === focusedId} isMobile={isMobile}
               onFocus={focus} onClose={close} onMin={minimize}
               onMove={move} onResize={resize} onTab={switchTab} onToast={showToast} />
        ))}
      </div>

      {startOpen && <StartMenu onOpen={openSection} />}

      <Taskbar windows={windows} focusedId={focusedId} startOpen={startOpen}
               onStart={(e) => { if(e) e.stopPropagation(); setStartOpen(o => !o); }}
               onFocus={focus} onToast={showToast} />

      {toast && <div className="toast">{toast}</div>}
    </div>
   </LangCtx.Provider>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
