import type { SectionId } from '@/types';
import { useLang } from '@/i18n/LangContext';
import { usePortfolioData } from '@/i18n/PortfolioContext';
import { AboutSection } from './AboutSection';
import { PersonalSection } from './PersonalSection';
import { SkillsSection } from './SkillsSection';
import { WorksSection } from './WorksSection';
import { EducationSection } from './EducationSection';
import { ExperienceSection } from './ExperienceSection';
import { LanguagesSection } from './LanguagesSection';
import { ContactSection } from './ContactSection';
import { CvSection } from './CvSection';

interface Props {
  id: SectionId;
  onToast: (msg: string) => void;
}

export function SectionBody({ id, onToast }: Props) {
  const { lang } = useLang();
  const portfolio = usePortfolioData();
  const T = portfolio.strings[lang];

  switch (id) {
    case 'about': return <AboutSection T={T} lang={lang} />;
    case 'personal': return <PersonalSection T={T} moodItems={portfolio.moodItems} />;
    case 'skills': return <SkillsSection T={T} />;
    case 'works': return <WorksSection T={T} lang={lang} onToast={onToast} portfolio={portfolio} />;
    case 'education': return <EducationSection T={T} />;
    case 'experience': return <ExperienceSection T={T} />;
    case 'languages': return <LanguagesSection T={T} />;
    case 'contact': return <ContactSection T={T} contacts={portfolio.contacts} />;
    case 'cv': return <CvSection T={T} lang={lang} onToast={onToast} portfolio={portfolio} />;
    default: return null;
  }
}
