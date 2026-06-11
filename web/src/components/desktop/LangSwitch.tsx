import { useLang } from '@/i18n/LangContext';

export function LangSwitch() {
  const { lang, setLang } = useLang();
  return (
    <div className="langswitch" role="group" aria-label="language">
      <button className={lang === 'nl' ? 'on' : ''} onClick={() => setLang('nl')}>NL</button>
      <button className={lang === 'en' ? 'on' : ''} onClick={() => setLang('en')}>EN</button>
    </div>
  );
}
