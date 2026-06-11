export function Dots({ n }: { n: number }) {
  return (
    <span className="dots">
      {[0, 1, 2, 3, 4].map(i => <i key={i} className={i < n ? 'on' : ''} />)}
    </span>
  );
}
