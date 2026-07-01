import { useRef } from 'react';
import type { SectionId, WindowState } from '@/types';
import { SECTIONS, SEC } from '@/data/sections';
import { SectionBody } from '@/components/sections/SectionBody';
import '@/styles/window.css';

interface Props {
  win: WindowState;
  focused: boolean;
  isMobile: boolean;
  onFocus: (wid: string) => void;
  onClose: (wid: string) => void;
  onMin: (wid: string) => void;
  onMove: (wid: string, x: number, y: number) => void;
  onResize: (wid: string, w: number, h: number) => void;
  onTab: (wid: string, sectionId: SectionId) => void;
  onToast: (msg: string) => void;
}

export function Window({ win, focused, isMobile, onFocus, onClose, onMin, onMove, onResize, onTab, onToast }: Props) {
  const sec = SEC[win.section];
  const _dragRef = useRef<HTMLDivElement>(null);

  const startDrag = (e: React.MouseEvent | React.TouchEvent) => {
    if (isMobile) return;
    onFocus(win.id);
    const ev = 'touches' in e ? e.touches[0] : e;
    const start = { x: ev.clientX, y: ev.clientY, wx: win.x, wy: win.y };
    const moveHandler = (m: MouseEvent | TouchEvent) => {
      const mv = 'touches' in m ? (m as TouchEvent).touches[0] : (m as MouseEvent);
      onMove(win.id, start.wx + (mv.clientX - start.x), start.wy + (mv.clientY - start.y));
    };
    const upHandler = () => {
      window.removeEventListener('mousemove', moveHandler);
      window.removeEventListener('mouseup', upHandler);
      window.removeEventListener('touchmove', moveHandler);
      window.removeEventListener('touchend', upHandler);
    };
    window.addEventListener('mousemove', moveHandler);
    window.addEventListener('mouseup', upHandler);
    window.addEventListener('touchmove', moveHandler, { passive: false });
    window.addEventListener('touchend', upHandler);
  };

  const startResize = (e: React.MouseEvent) => {
    if (isMobile) return;
    e.stopPropagation();
    onFocus(win.id);
    const start = { x: e.clientX, y: e.clientY, w: win.w, h: win.h };
    const moveHandler = (m: MouseEvent) => {
      onResize(win.id, Math.max(300, start.w + (m.clientX - start.x)), Math.max(220, start.h + (m.clientY - start.y)));
    };
    const upHandler = () => {
      window.removeEventListener('mousemove', moveHandler);
      window.removeEventListener('mouseup', upHandler);
    };
    window.addEventListener('mousemove', moveHandler);
    window.addEventListener('mouseup', upHandler);
  };

  const style: React.CSSProperties = isMobile
    ? { zIndex: win.z }
    : { left: win.x, top: win.y, width: win.w, height: win.h, zIndex: win.z };

  return (
    <div ref={_dragRef} className={'window' + (focused ? ' focused' : '')} style={style}
      onMouseDown={() => onFocus(win.id)}>
      <div className="titlebar" style={{ '--acc': sec.acc } as React.CSSProperties}
        onMouseDown={startDrag} onTouchStart={startDrag}>
        <span className="dot" />
        <span className="path">C:\LIANNE\{sec.label}</span>
        <span className="win-controls">
          <button title="minimize" onMouseDown={e => e.stopPropagation()} onClick={() => onMin(win.id)}>&minus;</button>
          <button className="close" title="close" onMouseDown={e => e.stopPropagation()} onClick={() => onClose(win.id)}>&times;</button>
        </span>
      </div>

      <div className="tabs">
        {SECTIONS.map(s => (
          <button key={s.id}
            className={'tab-btn' + (s.id === win.section ? ' active' : '')}
            style={{ '--tab-acc': s.acc } as React.CSSProperties}
            onMouseDown={e => e.stopPropagation()}
            onClick={() => onTab(win.id, s.id)}>
            {s.label}
          </button>
        ))}
      </div>

      <div className="win-body" style={{ '--sec-acc': sec.acc } as React.CSSProperties}>
        <SectionBody id={win.section} onToast={onToast} />
      </div>

      {!isMobile && <div className="resize" onMouseDown={startResize} />}
    </div>
  );
}
