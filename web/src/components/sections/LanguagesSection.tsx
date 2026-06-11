import type { LangStrings } from '@/i18n/strings';
import { Dots } from '@/components/shared/Dots';

export function LanguagesSection({ T }: { T: LangStrings }) {
  const l = T.languages;
  return (
    <div className="section">
      <p className="eyebrow">C:\LIANNE\languages</p>
      <h1 className="sec-title">{l.title[0]}<span className="hl">{l.title[1]}</span></h1>
      <p className="lead">{l.lead}</p>
      <div className="lang-grid">
        {l.rows.map(r => (
          <div className="lang-card" key={r.name} style={{ '--bc': r.color } as React.CSSProperties}>
            <div className="lang-card-top">
              <span className="sq" />
              <span className="lang-card-name">{r.name}</span>
              <span className="lang-card-level">{r.level}</span>
            </div>
            <Dots n={r.n} />
            <p className="lang-card-note">{r.note}</p>
          </div>
        ))}
      </div>
      <div className="lang-note">{l.note}</div>
    </div>
  );
}
