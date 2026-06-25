import type { LangStrings } from '@/i18n/strings';

interface ApiContact {
  platform: string;
  url: string;
  label: string;
}

const PLATFORM_META: Record<string, { icon: string; bg: string; color?: string }> = {
  email:     { icon: '@',   bg: 'var(--pink)' },
  linkedin:  { icon: 'in',  bg: 'var(--blue)' },
  github:    { icon: '</>', bg: 'var(--ink)' },
  instagram: { icon: 'ig',  bg: 'var(--lime)', color: 'var(--ink)' },
};

export function ContactSection({ T, contacts }: { T: LangStrings; contacts: ApiContact[] }) {
  const c = T.contact;
  return (
    <div className="section">
      <p className="eyebrow">C:\LIANNE\contact</p>
      <h1 className="sec-title">{c.title[0]}<span className="hl">{c.title[1]}</span></h1>
      <p className="lead">{c.lead}</p>
      <div className="contact-list">
        {contacts.map(contact => {
          const meta = PLATFORM_META[contact.platform] || { icon: '?', bg: 'var(--muted)' };
          return (
            <a className="contact-row" key={contact.platform} href={contact.url}
              target={contact.platform === 'email' ? undefined : '_blank'}
              rel={contact.platform === 'email' ? undefined : 'noopener noreferrer'}>
              <span className="ci" style={{ background: meta.bg, color: meta.color || '#fff' }}>{meta.icon}</span>
              <span className="ct"><b>{contact.platform}</b><span>{contact.label}</span></span>
            </a>
          );
        })}
      </div>
    </div>
  );
}
