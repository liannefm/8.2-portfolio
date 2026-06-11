import type { LangStrings } from '@/i18n/strings';
import charImg from '@/assets/lianne-character.png';
import '@/styles/personal.css';

function Pin({ color }: { color: string }) {
  return (
    <span className={`pin ${color}`}>
      <span className="head" /><span className="stem" />
    </span>
  );
}

export function PersonalSection({ T }: { T: LangStrings }) {
  const p = T.personal;

  return (
    <div className="section personal-sec" style={{ maxWidth: 880 }}>
      <p className="eyebrow">C:\LIANNE\personal</p>
      <h1 className="sec-title">{p.title[0]}<span className="hl">{p.title[1]}</span></h1>
      <p className="lead">{p.lead}</p>

      <div className="frame">
        <div className="board">
          <div className="board-items">

            <div className="pinned">
              <Pin color="pink" />
              <div className="pcard">
                <div className="pcard-photo">
                  <img src={charImg} alt="Lianne" draggable={false} />
                </div>
                <div className="pcard-cap">{p.caps.me}</div>
              </div>
            </div>

            <div className="pinned">
              <Pin color="yellow" />
              <div className="snote cream">{p.notes.music}</div>
            </div>

            <div className="pinned">
              <Pin color="lime" />
              <div className="pcard">
                <div className="image-placeholder">foto</div>
                <div className="pcard-cap">{p.caps.weekend}</div>
              </div>
            </div>

            <div className="pinned">
              <span className="washi pink" />
              <div className="pcard">
                <div className="image-placeholder">foto</div>
                <div className="pcard-cap">{p.caps.setup}</div>
              </div>
            </div>

            <div className="pinned">
              <Pin color="blue" />
              <div className="snote lime">{p.notes.todo}</div>
            </div>

            <div className="pinned">
              <Pin color="pink" />
              <div className="pcard">
                <div className="image-placeholder">foto</div>
                <div className="pcard-cap">{p.caps.coffee}</div>
              </div>
            </div>

            <div className="pinned">
              <span className="washi blue" />
              <div className="pcard">
                <div className="image-placeholder">foto</div>
                <div className="pcard-cap">{p.caps.creative}</div>
              </div>
            </div>

            <div className="pinned">
              <Pin color="yellow" />
              <div className="snote pink">{p.notes.quote}</div>
            </div>

            <div className="pinned">
              <Pin color="lime" />
              <div className="pcard">
                <div className="image-placeholder">foto</div>
                <div className="pcard-cap">{p.caps.friends}</div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <p className="cv-note">{p.note}</p>
    </div>
  );
}
