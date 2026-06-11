import { useState, useCallback } from 'react';
import type { SectionId, WindowState } from '@/types';

let WIN_SEQ = 1;

export function useWindowManager(isMobile: boolean) {
  const [windows, setWindows] = useState<WindowState[]>([]);
  const [focusedId, setFocusedId] = useState<string | null>(null);
  const [topZ, setTopZ] = useState(10);

  const nextPos = (n: number) => {
    const vw = window.innerWidth;
    const vh = window.innerHeight;
    const tb = vw >= 1500 ? 64 : 56;
    const w = Math.min(1140, vw - 24, Math.max(940, Math.round(vw * 0.66)));
    const h = Math.min(860, Math.max(480, Math.round((vh - tb) * 0.82)));
    const cx = Math.round((vw - w) / 2);
    const cy = Math.round(((vh - tb) - h) / 2);
    const off = (n % 5) * 26;
    return { x: Math.max(12, cx + off), y: Math.max(12, cy + off), w, h };
  };

  const openSection = useCallback((id: SectionId) => {
    setWindows(prev => {
      const existing = prev.find(w => w.section === id && !w.minimized);
      if (existing) {
        const z = topZ + 1; setTopZ(z); setFocusedId(existing.id);
        return prev.map(w => w.id === existing.id ? { ...w, z } : w);
      }
      const min = prev.find(w => w.section === id && w.minimized);
      const z = topZ + 1; setTopZ(z);
      if (min) {
        setFocusedId(min.id);
        return prev.map(w => w.id === min.id ? { ...w, minimized: false, z } : w);
      }
      if (isMobile) {
        setFocusedId('m');
        return [{ id: 'm', section: id, x: 0, y: 0, w: 0, h: 0, z: 1, minimized: false }];
      }
      const id2 = 'w' + (WIN_SEQ++);
      const pos = nextPos(prev.length);
      setFocusedId(id2);
      return [...prev, { id: id2, section: id, ...pos, z, minimized: false }];
    });
  }, [topZ, isMobile]);

  const focus = useCallback((wid: string) => {
    setWindows(prev => {
      const z = topZ + 1; setTopZ(z);
      return prev.map(w => w.id === wid ? { ...w, minimized: false, z } : w);
    });
    setFocusedId(wid);
  }, [topZ]);

  const close = useCallback((wid: string) => {
    setWindows(prev => prev.filter(w => w.id !== wid));
  }, []);

  const minimize = useCallback((wid: string) => {
    setWindows(prev => prev.map(w => w.id === wid ? { ...w, minimized: true } : w));
    setFocusedId(f => f === wid ? null : f);
  }, []);

  const move = useCallback((wid: string, x: number, y: number) => {
    setWindows(prev => prev.map(w => w.id === wid ? { ...w, x, y } : w));
  }, []);

  const resize = useCallback((wid: string, w: number, h: number) => {
    setWindows(prev => prev.map(win => win.id === wid ? { ...win, w, h } : win));
  }, []);

  const switchTab = useCallback((wid: string, sectionId: SectionId) => {
    setWindows(prev => prev.map(w => w.id === wid ? { ...w, section: sectionId } : w));
  }, []);

  return { windows, focusedId, openSection, focus, close, minimize, move, resize, switchTab };
}
