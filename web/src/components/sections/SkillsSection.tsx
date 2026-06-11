import type { LangStrings } from '@/i18n/strings';

export function SkillsSection({ T }: { T: LangStrings }) {
  return (
    <div className="section">
      <p className="eyebrow">C:\LIANNE\skills</p>
      <h1 className="sec-title">{T.skills.title[0]}<span className="hl">{T.skills.title[1]}</span></h1>
      <p className="lead">{T.skills.lead}</p>
      <div className="skill-grid">
        {T.skills.items.map(s => (
          <div className="skill-card" key={s.name} style={{ '--bc': s.color } as React.CSSProperties}>
            <div className="skill-card-top">
              <span className="sq" />
              <span className="skill-card-name">{s.name}</span>
            </div>
            <p className="skill-card-use">{s.use}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
