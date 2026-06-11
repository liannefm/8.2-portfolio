import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { LangProvider } from '@/i18n/LangContext';
import { PortfolioPage } from '@/pages/PortfolioPage';

export default function App() {
  return (
    <BrowserRouter>
      <LangProvider>
        <Routes>
          <Route path="/" element={<PortfolioPage />} />
          {/* Toekomstige CRUD routes hier toevoegen, bijv: */}
          {/* <Route path="/admin/*" element={<AdminLayout />} /> */}
        </Routes>
      </LangProvider>
    </BrowserRouter>
  );
}
