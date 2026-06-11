import { useState, useCallback } from 'react';
import { useLang } from '@/i18n/LangContext';
import charImg from '@/assets/lianne-character.png';

let mascotTimer: ReturnType<typeof setTimeout> | undefined;

export function Mascot({ onToast }: { onToast: (msg: string) => void }) {
  const { lang } = useLang();
  const [greet, setGreet] = useState(false);
  const line = lang === 'en' ? "hi, I'm Lianne! ✨" : 'hoi, ik ben Lianne! ✨';
  const toastMsg = lang === 'en' ? 'Happy coding! ✨' : 'Veel codeerplezier! ✨';

  const wave = useCallback(() => {
    setGreet(true);
    onToast(toastMsg);
    clearTimeout(mascotTimer);
    mascotTimer = setTimeout(() => setGreet(false), 2400);
  }, [onToast, toastMsg]);

  return (
    <div className={'mascot' + (greet ? ' greet' : '')}
      onMouseEnter={() => setGreet(true)} onMouseLeave={() => setGreet(false)}>
      <div className="bubble">{line}</div>
      <img className="char" src={charImg} alt="Lianne mascot" draggable={false} onClick={wave} />
      <div className="platform">
        <div className="coin" />
        <span className="tagchip">Lianne</span>
      </div>
    </div>
  );
}
