import type { LangStrings } from '@/i18n/strings';
import type { Lang } from '@/types';
import charImg from '@/assets/lianne-char3.png';

export function AboutSection({ T, lang }: { T: LangStrings; lang: Lang }) {
  const a = T.about;
  const hi = lang === 'en' ? 'hi! ✨' : 'hoi! ✨';

  return (
    <div className="section">
      <p className="eyebrow">C:\LIANNE\about-me</p>

      <div className="about-hero">
        <div className="about-text">
          <h1 className="sec-title">{a.greet[0]}<span className="hl">{a.greet[1]}</span></h1>
          <p className="eyebrow" style={{ marginBottom: 14 }}>{T.role}</p>
          <p className="lead">{a.lead}</p>
        </div>

        <div className="about-char">
          <span className="char-bubble">{hi}</span>
          <img className="char-img" src={charImg} alt="Lianne" draggable={false} />
          <span className="char-pad" />
        </div>
      </div>

      <div className="about-row">
        <div className="polaroid">
          <span className="tape" />
          <div className="photo-mini">
            <span className="ph-label">jouw foto</span>
          </div>
          <span className="cap">Lianne</span>
        </div>
        <div className="about-side">
          <div className="chiprow">
            <span className="chip fill-pink">{a.chips[0]}</span>
            <span className="chip fill-yellow">{a.chips[1]}</span>
            <span className="chip fill-lime">{a.chips[2]}</span>
            <span className="chip">{a.chips[3]}</span>
          </div>
          <div className="callout">
            {a.callout[0]}<span className="q">{a.callout[1]}</span>{a.callout[2]}
          </div>
        </div>
      </div>
    </div>
  );
}
