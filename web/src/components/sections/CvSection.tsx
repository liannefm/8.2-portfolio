import type { LangStrings } from '@/i18n/strings';
import type { Lang } from '@/types';
import type { PortfolioData } from '@/hooks/usePortfolio';
import { Dots } from '@/components/shared/Dots';

interface Props {
  T: LangStrings;
  lang: Lang;
  onToast: (msg: string) => void;
  portfolio: PortfolioData;
}

function CvEntry({ e }: { e: { yr: string; h: string; org?: string; p?: string } }) {
  return (
    <div className="cvx-entry">
      <span className="cvx-yr">{e.yr}</span>
      <h4>{e.h}</h4>
      {e.org && <div className="cvx-org">{e.org}</div>}
      {e.p && <p>{e.p}</p>}
    </div>
  );
}

export function CvSection({ T, lang, onToast, portfolio }: Props) {
  const v = T.cv;
  const dates = portfolio.workDates[lang];
  const projects = T.works.items.map((it, i) => ({ ...it, date: dates[i] })).slice().reverse();

  return (
    <div className="section cv-wrap">
      <p className="eyebrow" style={{ textAlign: 'left' }}>C:\LIANNE\resume.pdf</p>
      <div className="cvx-head">
        <h1 className="sec-title" style={{ textAlign: 'left', margin: 0 }}>{v.title[0]}<span className="hl">{v.title[1]}</span></h1>
        <button className="btn-primary" onClick={() => onToast(v.dlToast)}>↓ {v.download}</button>
      </div>

      <div className="cvx-doc">
        <aside className="cvx-side">
          <div className="cvx-av">L</div>
          <div className="cvx-name">Lianne</div>
          <div className="cvx-role">{T.role}</div>

          <h3 className="cvx-h">{T.titles.contact}</h3>
          {portfolio.contacts.map(c => (
            <span className="cvx-ci" key={c.platform}>{c.label}</span>
          ))}

          <h3 className="cvx-h">{T.titles.skills}</h3>
          <div className="cvx-pills">
            {T.skills.items.map(s => <span className="cvx-pill" key={s.name}>{s.name}</span>)}
          </div>

          <h3 className="cvx-h">{T.titles.languages}</h3>
          {T.languages.rows.map(r => (
            <div className="cvx-lang" key={r.name}><span>{r.name}</span><Dots n={r.n} /></div>
          ))}
        </aside>

        <main className="cvx-main">
          <h3 className="cvx-mh">{lang === 'en' ? 'Profile' : 'Profiel'}</h3>
          <p className="cvx-lead">{T.about.lead}</p>

          <h3 className="cvx-mh">{T.titles.experience}</h3>
          {T.experience.items.map((e, i) => <CvEntry key={'x' + i} e={e} />)}

          <h3 className="cvx-mh">{T.titles.education}</h3>
          {T.education.items.map((e, i) => <CvEntry key={'d' + i} e={e} />)}

          <h3 className="cvx-mh">{T.titles.works}</h3>
          {projects.map((p, i) => <CvEntry key={'p' + i} e={{ yr: p.date, h: p.name, p: p.desc }} />)}
        </main>
      </div>

      <p className="cv-note">{v.note}</p>
    </div>
  );
}
