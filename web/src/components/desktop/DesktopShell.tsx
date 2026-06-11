import { useState, useCallback, useEffect } from 'react';
import { SECTIONS } from '@/data/sections';
import { useWindowManager } from '@/hooks/useWindowManager';
import { LangSwitch } from './LangSwitch';
import { DesktopIcon, TapIcon } from './DesktopIcon';
import { Window } from './Window';
import { Taskbar } from './Taskbar';
import { StartMenu } from './StartMenu';
import { Toast } from '@/components/shared/Toast';
import '@/styles/desktop.css';
import '@/styles/responsive.css';

export function DesktopShell({ isMobile }: { isMobile: boolean }) {
  const { windows, focusedId, openSection, focus, close, minimize, move, resize, switchTab } = useWindowManager(isMobile);
  const [startOpen, setStartOpen] = useState(false);
  const [toast, setToast] = useState<string | null>(null);

  const showToast = useCallback((msg: string) => {
    setToast(msg);
    const t = setTimeout(() => setToast(null), 2200);
    return () => clearTimeout(t);
  }, []);

  const handleOpenSection = useCallback((id: Parameters<typeof openSection>[0]) => {
    setStartOpen(false);
    openSection(id);
  }, [openSection]);

  useEffect(() => {
    openSection('about');
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isMobile]);

  const visible = windows.filter(w => !w.minimized);

  return (
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
          {SECTIONS.map(s =>
            isMobile
              ? <TapIcon key={s.id} sec={s} onOpen={handleOpenSection} />
              : <DesktopIcon key={s.id} sec={s} onOpen={handleOpenSection} />
          )}
        </div>

        {visible.map(w => (
          <Window key={w.id} win={w} focused={w.id === focusedId} isMobile={isMobile}
            onFocus={focus} onClose={close} onMin={minimize}
            onMove={move} onResize={resize} onTab={switchTab} onToast={showToast} />
        ))}
      </div>

      {startOpen && <StartMenu onOpen={handleOpenSection} />}

      <Taskbar windows={windows} focusedId={focusedId} startOpen={startOpen}
        onStart={e => { if (e) e.stopPropagation(); setStartOpen(o => !o); }}
        onFocus={focus} onToast={showToast} />

      {toast && <Toast message={toast} />}
    </div>
  );
}
