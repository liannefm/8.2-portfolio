import type { LangStrings } from '@/i18n/strings';

const CONTACT_META = [
  { bg: 'var(--pink)', icon: '@', href: 'mailto:lianne@example.com' },
  { bg: 'var(--blue)', icon: 'in', href: '#' },
  { bg: 'var(--ink)', icon: '</>', href: '#' },
  { bg: 'var(--lime)', icon: 'ig', color: 'var(--ink)', href: '#' },
];

export function ContactSection({ T }: { T: LangStrings }) {
  const c = T.contact;
  return (
    <div className="section">
      <p className="eyebrow">C:\LIANNE\contact</p>
      <h1 className="sec-title">{c.title[0]}<span className="hl">{c.title[1]}</span></h1>
      <p className="lead">{c.lead}</p>
      <div className="contact-list">
        {c.rows.map(([label, val], i) => (
          <a className="contact-row" key={label} href={CONTACT_META[i].href}
            onClick={e => { if (CONTACT_META[i].href === '#') e.preventDefault(); }}>
            <span className="ci" style={{ background: CONTACT_META[i].bg, color: CONTACT_META[i].color || '#fff' }}>{CONTACT_META[i].icon}</span>
            <span className="ct"><b>{label}</b><span>{val}</span></span>
          </a>
        ))}
      </div>
    </div>
  );
}
