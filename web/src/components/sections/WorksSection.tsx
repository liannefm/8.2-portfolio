import { useState } from 'react';
import type { LangStrings } from '@/i18n/strings';
import type { Lang } from '@/types';
import type { PortfolioData } from '@/hooks/usePortfolio';

import workOwnWebpage from '@/assets/work-own-webpage.png';
import workWebshop from '@/assets/work-webshop.png';
import workWordpress from '@/assets/work-wordpress.png';
import workFirewater from '@/assets/work-firewater.png';
import workKiosk from '@/assets/work-kiosk.png';
import workRating from '@/assets/work-rating.png';

const WORK_IMAGES: Record<string, string> = {
  'work-own-webpage.png': workOwnWebpage,
  'work-webshop.png': workWebshop,
  'work-wordpress.png': workWordpress,
  'work-firewater.png': workFirewater,
  'work-kiosk.png': workKiosk,
  'work-rating.png': workRating,
};

const DETAIL_UI: Record<Lang, Record<string, string>> = {
  nl: { back: 'terug naar projecten', role: 'Rol', type: 'Type', dur: 'Duur', date: 'Datum', done: 'Wat ik deed', stack: 'Gebruikte technieken', gallery: 'Meer beelden', galleryNote: '', live: 'Live bekijken', source: 'Broncode' },
  en: { back: 'back to projects', role: 'Role', type: 'Type', dur: 'Duration', date: 'Date', done: 'What I did', stack: 'Tech used', gallery: 'More visuals', galleryNote: '', live: 'View live', source: 'Source code' },
};

function workSlug(name: string) {
  return name.toLowerCase().replace(/&/g, 'en').replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

interface WorkDetailProps {
  p: { name: string; img: string; date: string; tags: string[] };
  d: { role: string; type: string; dur: string; intro: string; bullets: string[]; outcome: string };
  ui: Record<string, string>;
  onBack: () => void;
  onToast: (msg: string) => void;
}

function WorkDetail({ p, d, ui, onBack, onToast }: WorkDetailProps) {
  const imgSrc = WORK_IMAGES[p.img];
  return (
    <div className="section work-detail" style={{ maxWidth: 820 }}>
      <button className="wd-back" onClick={onBack}><span aria-hidden="true">&larr;</span> {ui.back}</button>
      <p className="eyebrow">C:\LIANNE\works\{workSlug(p.name)}</p>
      <h1 className="sec-title">{p.name}</h1>

      <div className="wd-meta">
        <span><b>{ui.date}</b>{p.date}</span>
        <span><b>{ui.role}</b>{d.role}</span>
        <span><b>{ui.type}</b>{d.type}</span>
        <span><b>{ui.dur}</b>{d.dur}</span>
      </div>

      <div className="wd-hero">
        {imgSrc
          ? <img src={imgSrc} alt={p.name} draggable={false} />
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

export function WorksSection({ T, lang, onToast, portfolio }: { T: LangStrings; lang: Lang; onToast: (msg: string) => void; portfolio: PortfolioData }) {
  const w = T.works;
  const dates = portfolio.workDates[lang];
  const projects = w.items.map((it, i) => ({ ...it, date: dates[i], tags: portfolio.workTags[i], idx: i }));
  const [sel, setSel] = useState<number | null>(null);

  if (sel !== null) {
    return <WorkDetail p={projects[sel]} d={portfolio.workDetail[lang][sel]} ui={DETAIL_UI[lang]} onBack={() => setSel(null)} onToast={onToast} />;
  }

  return (
    <div className="section" style={{ maxWidth: 820 }}>
      <p className="eyebrow">C:\LIANNE\works</p>
      <h1 className="sec-title">{w.title[0]}<span className="hl">{w.title[1]}</span></h1>
      <p className="lead">{w.lead}</p>
      <div className="timeline work-timeline">
        {projects.slice().reverse().map(p => {
          const imgSrc = WORK_IMAGES[p.img];
          return (
            <div className="tl-item work-item" key={p.idx}>
              <span className="yr">{p.date}</span>
              <div className="work-card" role="button" tabIndex={0}
                onClick={() => setSel(p.idx)}
                onKeyDown={e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); setSel(p.idx); } }}>
                <div className="work-shot">
                  {imgSrc
                    ? <img src={imgSrc} alt={p.name} />
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
          );
        })}
      </div>
    </div>
  );
}
