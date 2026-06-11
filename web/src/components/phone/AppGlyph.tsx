import type { SectionId } from '@/types';

const P = { fill: 'none', stroke: 'currentColor', strokeWidth: 2, strokeLinecap: 'round' as const, strokeLinejoin: 'round' as const };

export function AppGlyph({ id }: { id: SectionId }) {
  switch (id) {
    case 'about': return <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.6" {...P} /><path d="M5 20c0-3.6 3.1-6 7-6s7 2.4 7 6" {...P} /></svg>;
    case 'personal': return <svg viewBox="0 0 24 24"><path d="M12 20s-6.5-4.3-6.5-9A3.5 3.5 0 0 1 12 8.2 3.5 3.5 0 0 1 18.5 11c0 4.7-6.5 9-6.5 9z" {...P} /></svg>;
    case 'works': return <svg viewBox="0 0 24 24"><path d="M3 8.5C3 7.7 3.7 7 4.5 7H9l2 2h8.5c.8 0 1.5.7 1.5 1.5v7c0 .8-.7 1.5-1.5 1.5h-15C3.7 19 3 18.3 3 17.5v-9z" {...P} /></svg>;
    case 'skills': return <svg viewBox="0 0 24 24"><path d="M12 3l8 4.5-8 4.5-8-4.5L12 3z" {...P} /><path d="M4 12l8 4.5 8-4.5M4 16.5L12 21l8-4.5" {...P} /></svg>;
    case 'education': return <svg viewBox="0 0 24 24"><path d="M12 4l9 4.5-9 4.5-9-4.5L12 4z" {...P} /><path d="M7 11v4.2c0 .9 2.4 2.3 5 2.3s5-1.4 5-2.3V11" {...P} /><path d="M21 8.5V14" {...P} /></svg>;
    case 'experience': return <svg viewBox="0 0 24 24"><rect x="3.5" y="8" width="17" height="11" rx="2" {...P} /><path d="M9 8V6.5C9 5.7 9.7 5 10.5 5h3c.8 0 1.5.7 1.5 1.5V8M3.5 13h17" {...P} /></svg>;
    case 'languages': return <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5" {...P} /><path d="M3.5 12h17M12 3.5c2.5 2.4 2.5 14.6 0 17M12 3.5c-2.5 2.4-2.5 14.6 0 17" {...P} /></svg>;
    case 'contact': return <svg viewBox="0 0 24 24"><rect x="3.5" y="5.5" width="17" height="13" rx="2" {...P} /><path d="M4 7.5l8 5.5 8-5.5" {...P} /></svg>;
    case 'cv': return <svg viewBox="0 0 24 24"><path d="M6.5 3.5h7L18 8v12a1 1 0 0 1-1 1H6.5a1 1 0 0 1-1-1V4.5a1 1 0 0 1 1-1z" {...P} /><path d="M13 3.5V8h4.5M8.5 12.5h7M8.5 16h7" {...P} /></svg>;
    default: return null;
  }
}
