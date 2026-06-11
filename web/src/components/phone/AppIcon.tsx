import type { SectionDef, SectionId } from '@/types';
import { useLang } from '@/i18n/LangContext';
import { STRINGS } from '@/i18n/strings';
import { APP_META } from '@/data/sections';
import { AppGlyph } from './AppGlyph';

interface Props {
  sec: SectionDef;
  onOpen: (id: SectionId) => void;
}

export function AppIcon({ sec, onOpen }: Props) {
  const m = APP_META[sec.id];
  const { lang } = useLang();
  const label = STRINGS[lang].titles[sec.id];

  return (
    <button className="app" onClick={() => onOpen(sec.id)} aria-label={label}>
      <span className="app-ico" style={{ '--app-bg': m.bg, '--app-fg': m.fg } as React.CSSProperties}>
        <AppGlyph id={sec.id} />
      </span>
      <span className="app-label">{label}</span>
    </button>
  );
}
