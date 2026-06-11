import { useState, useEffect } from 'react';
import type { WindowState } from '@/types';
import { SEC } from '@/data/sections';
import { LangSwitch } from './LangSwitch';
import '@/styles/taskbar.css';

interface Props {
  windows: WindowState[];
  focusedId: string | null;
  startOpen: boolean;
  onStart: (e?: React.MouseEvent) => void;
  onFocus: (wid: string) => void;
  onToast: (msg: string) => void;
}

export function Taskbar({ windows, focusedId, startOpen, onStart, onFocus, onToast }: Props) {
  const [time, setTime] = useState(() => new Date());
  useEffect(() => {
    const t = setInterval(() => setTime(new Date()), 30_000);
    return () => clearInterval(t);
  }, []);

  const hh = String(time.getHours()).padStart(2, '0');
  const mm = String(time.getMinutes()).padStart(2, '0');
  const date = time.toLocaleDateString('nl-NL', { day: '2-digit', month: 'short' });

  return (
    <div className="taskbar">
      <button className={'start' + (startOpen ? ' open' : '')}
        onMouseDown={e => e.stopPropagation()}
        onClick={e => onStart(e)}>
        <span className="gem" /><span className="stxt">Lianne</span>
      </button>

      <div className="task-open">
        {windows.map(w => (
          <button key={w.id} className={'task-chip' + (w.id === focusedId ? ' active' : '')}
            onClick={() => onFocus(w.id)}>
            <span className="cdot" style={{ '--acc': SEC[w.section].acc } as React.CSSProperties} />{SEC[w.section].label}
          </button>
        ))}
      </div>

      <div className="tray">
        <LangSwitch />
        <a href="#" onClick={e => { e.preventDefault(); onToast('LinkedIn — voorbeeld'); }} title="LinkedIn">in</a>
        <a href="#" onClick={e => { e.preventDefault(); onToast('GitHub — voorbeeld'); }} title="GitHub" style={{ background: 'var(--cream)' }}>{'</>'}</a>
        <a href="#" onClick={e => { e.preventDefault(); onToast('Behance — voorbeeld'); }} title="Behance" style={{ background: 'var(--blue)', color: '#fff' }}>Be</a>
        <a href="#" onClick={e => { e.preventDefault(); onToast('Instagram — voorbeeld'); }} title="Instagram" style={{ background: 'var(--pink)', color: '#fff' }}>ig</a>
        <span className="clock">{hh}:{mm}<small>{date}</small></span>
      </div>
    </div>
  );
}
