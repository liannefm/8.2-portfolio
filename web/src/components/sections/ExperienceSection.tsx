import type { LangStrings } from '@/i18n/strings';

export function ExperienceSection({ T }: { T: LangStrings }) {
  const e = T.experience;
  return (
    <div className="section">
      <p className="eyebrow">C:\LIANNE\experience</p>
      <h1 className="sec-title">{e.title[0]}<span className="hl">{e.title[1]}</span></h1>
      <div className="timeline">
        {e.items.map((it, i) => (
          <div className="tl-item" key={i}>
            <span className="yr">{it.yr}</span>
            <h3>{it.h}</h3>
            <div className="org">{it.org}</div>
            {it.p && <p>{it.p}</p>}
          </div>
        ))}
      </div>
    </div>
  );
}
