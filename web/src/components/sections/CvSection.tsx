import type { LangStrings } from '@/i18n/strings';
import type { Lang } from '@/types';
import type { PortfolioData } from '@/hooks/usePortfolio';

interface Props {
  T: LangStrings;
  lang: Lang;
  onToast: (msg: string) => void;
  portfolio: PortfolioData;
}

const CV_IMG = '/cv/cv-lianne.png';
const CV_PDF = '/cv/cv-lianne.pdf';

export function CvSection({ T }: Props) {
  const v = T.cv;

  return (
    <div className="section cv-wrap">
      <p className="eyebrow" style={{ textAlign: 'left' }}>C:\LIANNE\resume.pdf</p>
      <div className="cvx-head">
        <h1 className="sec-title" style={{ textAlign: 'left', margin: 0 }}>{v.title[0]}<span className="hl">{v.title[1]}</span></h1>
        <a className="btn-primary" href={CV_PDF} download="cv-lianne.pdf">↓ {v.download}</a>
      </div>

      <img className="cvx-img" src={CV_IMG} alt={`CV Lianne — ${T.role}`} />
    </div>
  );
}
