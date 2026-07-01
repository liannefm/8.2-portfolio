import { useState } from 'react';
import type { LangStrings } from '@/i18n/strings';
import type { Lang } from '@/types';
import type { PortfolioData, MediaItem, WorkLinks } from '@/hooks/usePortfolio';

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
  nl: { back: 'terug naar projecten', role: 'Rol', type: 'Type', dur: 'Duur', date: 'Datum', done: 'Wat ik deed', stack: 'Gebruikte technieken', gallery: "Foto's", code: 'Code uitgelicht', video: 'Video', galleryNote: '', live: 'Live bekijken', source: 'GitHub' },
  en: { back: 'back to projects', role: 'Role', type: 'Type', dur: 'Duration', date: 'Date', done: 'What I did', stack: 'Tech used', gallery: 'Photos', code: 'Code highlights', video: 'Video', galleryNote: '', live: 'View live', source: 'GitHub' },
};

const VIDEO_EXT = /\.(mp4|webm|ogg|mov)$/i;

function videoEmbed(url: string): string | null {
  const yt = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]+)/);
  if (yt) return `https://www.youtube.com/embed/${yt[1]}`;
  const vm = url.match(/vimeo\.com\/(?:video\/)?(\d+)/);
  if (vm) return `https://player.vimeo.com/video/${vm[1]}`;
  return null;
}

function workSlug(name: string) {
  return name.toLowerCase().replace(/&/g, 'en').replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

interface WorkDetailProps {
  p: { name: string; img: string; date: string; tags: string[] };
  d: { role: string; type: string; dur: string; intro: string; bullets: string[]; outcome: string };
  media: { photos: MediaItem[]; code: MediaItem[] };
  links: WorkLinks;
  ui: Record<string, string>;
  onBack: () => void;
}

function WorkDetail({ p, d, media, links, ui, onBack }: WorkDetailProps) {
  const imgSrc = WORK_IMAGES[p.img];
  const embed = links.video ? videoEmbed(links.video) : null;
  const isVideoFile = links.video && !embed && VIDEO_EXT.test(links.video);

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

      {(embed || isVideoFile) && (
        <>
          <h3 className="wd-gh">{ui.video}</h3>
          <div className="wd-video">
            {embed
              ? <iframe src={embed} title={`${p.name} — video`} allow="accelerated-motion; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowFullScreen />
              : <video src={links.video} controls preload="metadata" />}
          </div>
        </>
      )}

      {media.photos.length > 0 && (
        <>
          <h3 className="wd-gh">{ui.gallery}</h3>
          <div className="wd-gallery">
            {media.photos.map((m, i) => (
              <figure className="wd-shot" key={i}>
                <img src={m.img} alt={m.cap || p.name} loading="lazy" />
                {m.cap && <figcaption>{m.cap}</figcaption>}
              </figure>
            ))}
          </div>
        </>
      )}

      {media.code.length > 0 && (
        <>
          <h3 className="wd-gh">{ui.code}</h3>
          <div className="wd-code-list">
            {media.code.map((m, i) => (
              <figure className="wd-code" key={i}>
                <img src={m.img} alt={m.cap || 'code'} loading="lazy" />
                {m.cap && <figcaption>{m.cap}</figcaption>}
              </figure>
            ))}
          </div>
        </>
      )}

      {(links.live || links.source) && (
        <div className="wd-cta">
          {links.live && (
            <a className="btn-primary" href={links.live} target="_blank" rel="noreferrer noopener">{ui.live} &rarr;</a>
          )}
          {links.source && (
            <a className="proj-btn" href={links.source} target="_blank" rel="noreferrer noopener">{ui.source}</a>
          )}
        </div>
      )}
    </div>
  );
}

export function WorksSection({ T, lang, portfolio }: { T: LangStrings; lang: Lang; onToast: (msg: string) => void; portfolio: PortfolioData }) {
  const w = T.works;
  const dates = portfolio.workDates[lang];
  const projects = w.items.map((it, i) => ({ ...it, date: dates[i], tags: portfolio.workTags[i], idx: i }));
  const [sel, setSel] = useState<number | null>(null);

  if (sel !== null) {
    return (
      <WorkDetail
        p={projects[sel]}
        d={portfolio.workDetail[lang][sel]}
        media={portfolio.workMedia[lang][sel]}
        links={portfolio.workLinks[sel]}
        ui={DETAIL_UI[lang]}
        onBack={() => setSel(null)}
      />
    );
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
