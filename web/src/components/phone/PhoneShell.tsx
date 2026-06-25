import { useState, useCallback, useEffect } from 'react';
import type { SectionId } from '@/types';
import { SECTIONS, SEC, DOCK_IDS } from '@/data/sections';
import { useLang } from '@/i18n/LangContext';
import { usePortfolioData } from '@/i18n/PortfolioContext';
import { LangSwitch } from '@/components/desktop/LangSwitch';
import { StatusBar } from './StatusBar';
import { AppIcon } from './AppIcon';
import { SectionBody } from '@/components/sections/SectionBody';
import '@/styles/phone.css';
import '@/styles/sections.css';

export function PhoneShell() {
  const { lang } = useLang();
  const portfolio = usePortfolioData();
  const T = portfolio.strings[lang];
  const [active, setActive] = useState<SectionId | null>(null);
  const [shown, setShown] = useState(false);
  const [toast, setToast] = useState<string | null>(null);

  const showToast = useCallback((msg: string) => {
    setToast(msg);
    const t = setTimeout(() => setToast(null), 2000);
    return () => clearTimeout(t);
  }, []);

  const open = useCallback((id: SectionId) => {
    setActive(id);
    setShown(false);
    setTimeout(() => setShown(true), 20);
  }, []);

  const home = useCallback(() => {
    setShown(false);
    setTimeout(() => setActive(null), 300);
  }, []);

  useEffect(() => {
    const W = 404, H = 876, pad = 24;
    const fit = () => {
      const s = Math.min((window.innerWidth - pad) / W, (window.innerHeight - pad) / H, 1.08);
      document.documentElement.style.setProperty('--phone-scale', String(s));
    };
    fit();
    window.addEventListener('resize', fit);
    return () => window.removeEventListener('resize', fit);
  }, []);

  const sec = active ? SEC[active] : null;
  const titles = T.titles;
  const today = new Date().toLocaleDateString(lang === 'en' ? 'en-GB' : 'nl-NL', { weekday: 'short', day: '2-digit', month: 'short' });

  return (
    <div className="phone-stage">
      <div className="phone">
        <div className="phone-screen">
          <div className="scr-grid" />
          <div className="island" />

          <div className={'home' + (active ? ' dimmed' : '')}>
            <StatusBar />
            <div className="home-head">
              <span className="home-av">L</span>
              <span className="home-hi">
                <b>Lianne</b>
                <span>{T.role}</span>
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

          {active && sec && (
            <div className={'phone-app' + (shown ? ' show' : '')} style={{ '--accent': sec.acc } as React.CSSProperties}>
              <div className="app-head">
                <StatusBar />
                <div className="app-nav">
                  <button className="back" onClick={home} aria-label="home">&#8249;</button>
                  <span className="a-dot" />
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
  );
}
