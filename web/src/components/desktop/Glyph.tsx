const FOLDER_COLORS: Record<string, string> = {
  yellow: '#ffd02e',
  pink: '#ff7ac0',
  lime: '#c2f25a',
  blue: '#7aa6ff',
};

export function Glyph({ kind }: { kind: string }) {
  if (kind === 'photo') {
    return <div className="photochip">L</div>;
  }
  const color = FOLDER_COLORS[kind] || '#ffd02e';
  return (
    <div className="folder" style={{ '--fc': color } as React.CSSProperties}>
      <span className="tab" /><span className="body" /><span className="seam" />
    </div>
  );
}
