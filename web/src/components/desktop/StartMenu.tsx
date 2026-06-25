import type { SectionId } from '@/types';
import { useLang } from '@/i18n/LangContext';
import { usePortfolioData } from '@/i18n/PortfolioContext';
import { SECTIONS } from '@/data/sections';
import { Glyph } from './Glyph';
import '@/styles/startmenu.css';

export function StartMenu({ onOpen }: { onOpen: (id: SectionId) => void }) {
  const { lang } = useLang();
  const portfolio = usePortfolioData();
  const T = portfolio.strings[lang];

  return (
    <div className="startmenu">
      <div className="sm-head">
        <span className="av">L</span>
        <span><b>Lianne</b><span>{T.role}</span></span>
      </div>
      <div className="sm-list">
        {SECTIONS.map(s => (
          <button key={s.id} onClick={() => onOpen(s.id)}>
            <span className="si" style={{ width: 26, height: 22 }}><Glyph kind={s.icon} /></span>
            {T.titles[s.id]}
          </button>
        ))}
      </div>
      <div className="sm-foot">LianneOS &middot; v2025</div>
    </div>
  );
}
