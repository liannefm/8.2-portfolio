import type { LangStrings } from '@/i18n/strings';
import { useLang } from '@/i18n/LangContext';
import charImg from '@/assets/lianne-character.png';
import mePhoto from '@/assets/ik.png';
import konijnenPhoto from '@/assets/konijnentjes.jpg';
import gezinPhoto from '@/assets/gezin.png';
import '@/styles/personal.css';

interface ApiMoodItem {
  type: 'photo' | 'note';
  key_name: string;
  caption_nl: string | null;
  caption_en: string | null;
  image_url: string | null;
  pos_x: number | null;
  pos_y: number | null;
}

const PHOTO_ASSETS: Record<string, string> = {
  'ik.png': mePhoto,
  'konijnentjes.jpg': konijnenPhoto,
  'gezin.png': gezinPhoto,
};

function Pin({ color }: { color: string }) {
  return (
    <span className={`pin ${color}`}>
      <span className="head" /><span className="stem" />
    </span>
  );
}

const PIN_COLORS = ['pink', 'yellow', 'lime', 'blue', 'pink', 'lime'];
const NOTE_COLORS = ['cream', 'lime', 'pink'];

export function PersonalSection({ T, moodItems }: { T: LangStrings; moodItems: ApiMoodItem[] }) {
  const { lang } = useLang();
  const p = T.personal;

  const hasPositions = moodItems.some(m => m.pos_x !== null && m.pos_y !== null);

  const photos = moodItems.filter(m => m.type === 'photo');
  const notes = moodItems.filter(m => m.type === 'note');

  const getCaption = (item: ApiMoodItem) =>
    (lang === 'nl' ? item.caption_nl : item.caption_en) || '';

  const getImageSrc = (item: ApiMoodItem): string | null => {
    if (!item.image_url) return null;
    if (PHOTO_ASSETS[item.image_url]) return PHOTO_ASSETS[item.image_url];
    if (item.image_url.startsWith('http')) return item.image_url;
    return null;
  };

  const renderItem = (item: ApiMoodItem, idx: number) => {
    const isCharacter = item.key_name === 'me';
    const isGezin = item.key_name === 'creative';

    if (item.type === 'note') {
      return (
        <>
          <Pin color={PIN_COLORS[(idx + 1) % PIN_COLORS.length]} />
          <div className={`snote ${NOTE_COLORS[idx % NOTE_COLORS.length]}`}>
            {getCaption(item)}
          </div>
        </>
      );
    }

    const imgSrc = getImageSrc(item);
    return (
      <>
        {idx % 2 === 0
          ? <Pin color={PIN_COLORS[idx % PIN_COLORS.length]} />
          : <span className={`washi ${PIN_COLORS[idx % PIN_COLORS.length]}`} />}
        <div className="pcard">
          {isCharacter ? (
            <div className="pcard-photo pcard-character">
              <img src={charImg} alt={getCaption(item)} draggable={false} />
            </div>
          ) : imgSrc ? (
            <div className={`pcard-photo${isGezin ? ' pcard-wide' : ''}`}>
              <img src={imgSrc} alt={getCaption(item)} draggable={false}
                style={isGezin ? { objectPosition: 'center 20%' } : undefined} />
            </div>
          ) : (
            <div className="image-placeholder">foto</div>
          )}
          <div className="pcard-cap">{getCaption(item)}</div>
        </div>
      </>
    );
  };

  if (hasPositions) {
    const ROTATIONS = ['-2deg', '2.4deg', '-3deg', '1.6deg', '-1.4deg'];
    return (
      <div className="section personal-sec" style={{ maxWidth: 880 }}>
        <p className="eyebrow">C:\LIANNE\personal</p>
        <h1 className="sec-title">{p.title[0]}<span className="hl">{p.title[1]}</span></h1>
        <p className="lead">{p.lead}</p>

        <div className="frame">
          <div className="board board-free">
            {moodItems.map((item, i) => {
              const x = item.pos_x ?? (10 + (i % 4) * 22);
              const y = item.pos_y ?? (8 + Math.floor(i / 4) * 35);
              return (
                <div
                  className="pinned pinned-abs"
                  key={item.key_name}
                  style={{
                    left: `${x}%`,
                    top: `${y}%`,
                    transform: `rotate(${ROTATIONS[i % ROTATIONS.length]})`,
                  }}
                >
                  {renderItem(item, i)}
                </div>
              );
            })}
          </div>
        </div>

        <p className="cv-note">{p.note}</p>
      </div>
    );
  }

  const interleaved: Array<{ type: 'photo' | 'note'; item: ApiMoodItem; idx: number }> = [];
  let pi = 0, ni = 0;
  const pattern = ['photo', 'note', 'photo', 'photo', 'note', 'photo', 'photo', 'note', 'photo'] as const;
  for (const t of pattern) {
    if (t === 'photo' && pi < photos.length) {
      interleaved.push({ type: 'photo', item: photos[pi], idx: pi });
      pi++;
    } else if (t === 'note' && ni < notes.length) {
      interleaved.push({ type: 'note', item: notes[ni], idx: ni });
      ni++;
    }
  }

  return (
    <div className="section personal-sec" style={{ maxWidth: 880 }}>
      <p className="eyebrow">C:\LIANNE\personal</p>
      <h1 className="sec-title">{p.title[0]}<span className="hl">{p.title[1]}</span></h1>
      <p className="lead">{p.lead}</p>

      <div className="frame">
        <div className="board">
          <div className="board-items">
            {interleaved.map(({ item, idx }) => (
              <div className="pinned" key={item.key_name}>
                {renderItem(item, idx)}
              </div>
            ))}
          </div>
        </div>
      </div>

      <p className="cv-note">{p.note}</p>
    </div>
  );
}
