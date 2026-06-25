import type { SectionDef, SectionId } from '@/types';
import { useLang } from '@/i18n/LangContext';
import { usePortfolioData } from '@/i18n/PortfolioContext';
import { APP_META } from '@/data/sections';
import { AppGlyph } from './AppGlyph';

interface Props {
  sec: SectionDef;
  onOpen: (id: SectionId) => void;
}

export function AppIcon({ sec, onOpen }: Props) {
  const m = APP_META[sec.id];
  const { lang } = useLang();
  const portfolio = usePortfolioData();
  const label = portfolio.strings[lang].titles[sec.id];

  return (
    <button className="app" onClick={() => onOpen(sec.id)} aria-label={label}>
      <span className="app-ico" style={{ '--app-bg': m.bg, '--app-fg': m.fg } as React.CSSProperties}>
        <AppGlyph id={sec.id} />
      </span>
      <span className="app-label">{label}</span>
    </button>
  );
}
