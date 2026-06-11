import { useState, useEffect } from 'react';

export function StatusBar() {
  const [t, setT] = useState(() => new Date());
  useEffect(() => {
    const i = setInterval(() => setT(new Date()), 20_000);
    return () => clearInterval(i);
  }, []);

  const hh = String(t.getHours()).padStart(2, '0');
  const mm = String(t.getMinutes()).padStart(2, '0');

  return (
    <div className="statusbar">
      <span>{hh}:{mm}</span>
      <span className="sb-right">
        <span className="sb-bars"><i /><i /><i /><i /></span>
        <span className="sb-batt"><span /></span>
      </span>
    </div>
  );
}
