import type { SectionDef, SectionId } from '@/types';
import { Glyph } from './Glyph';

interface Props {
  sec: SectionDef;
  onOpen: (id: SectionId) => void;
}

export function DesktopIcon({ sec, onOpen }: Props) {
  return (
    <button className="icon" onDoubleClick={() => onOpen(sec.id)}
      onClick={e => { if (e.detail === 0) onOpen(sec.id); }}
      title={`Open ${sec.label}`}>
      <span className="glyph"><Glyph kind={sec.icon} /></span>
      <span className="label">{sec.label}</span>
    </button>
  );
}

export function TapIcon({ sec, onOpen }: Props) {
  return (
    <button className="icon" onClick={() => onOpen(sec.id)} title={`Open ${sec.label}`}>
      <span className="glyph"><Glyph kind={sec.icon} /></span>
      <span className="label">{sec.label}</span>
    </button>
  );
}
