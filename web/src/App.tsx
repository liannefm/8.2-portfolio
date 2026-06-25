import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { LangProvider } from '@/i18n/LangContext';
import { PortfolioProvider } from '@/i18n/PortfolioContext';
import { PortfolioPage } from '@/pages/PortfolioPage';

export default function App() {
  return (
    <BrowserRouter>
      <LangProvider>
        <PortfolioProvider>
          <Routes>
            <Route path="/" element={<PortfolioPage />} />
          </Routes>
        </PortfolioProvider>
      </LangProvider>
    </BrowserRouter>
  );
}
