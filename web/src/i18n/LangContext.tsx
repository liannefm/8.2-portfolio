import { createContext, useContext, useState, useCallback, type ReactNode } from 'react';
import type { Lang } from '@/types';

interface LangContextValue {
  lang: Lang;
  setLang: (l: Lang) => void;
}

const LangCtx = createContext<LangContextValue>({ lang: 'nl', setLang: () => {} });

export const useLang = () => useContext(LangCtx);

export function LangProvider({ children }: { children: ReactNode }) {
  const [lang, setLangRaw] = useState<Lang>(() => {
    try {
      const stored = localStorage.getItem('lianneos.lang');
      if (stored === 'en' || stored === 'nl') return stored;
    } catch { /* ignore */ }
    return 'nl';
  });

  const setLang = useCallback((l: Lang) => {
    setLangRaw(l);
    try { localStorage.setItem('lianneos.lang', l); } catch { /* ignore */ }
  }, []);

  return <LangCtx.Provider value={{ lang, setLang }}>{children}</LangCtx.Provider>;
}
