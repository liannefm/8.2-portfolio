import { createContext, useContext, type ReactNode } from 'react';
import { usePortfolio, type PortfolioData } from '@/hooks/usePortfolio';

const PortfolioCtx = createContext<PortfolioData | null>(null);

export function usePortfolioData() {
  const ctx = useContext(PortfolioCtx);
  if (!ctx) throw new Error('usePortfolioData must be used within PortfolioProvider');
  return ctx;
}

export function PortfolioProvider({ children }: { children: ReactNode }) {
  const { data, loading, error } = usePortfolio();

  if (loading) {
    return <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: '100vh', fontFamily: 'var(--font-mono)', color: 'var(--muted)' }}>laden...</div>;
  }

  if (error || !data) {
    return <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: '100vh', fontFamily: 'var(--font-mono)', color: 'var(--pink)' }}>Fout bij laden: {error}</div>;
  }

  return <PortfolioCtx.Provider value={data}>{children}</PortfolioCtx.Provider>;
}
