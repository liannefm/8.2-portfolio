import type { SectionDef, SectionId } from '@/types';

export const SECTIONS: SectionDef[] = [
  { id: 'about',      label: 'about-me',   icon: 'photo',  acc: 'var(--pink)' },
  { id: 'personal',   label: 'personal',   icon: 'pink',   acc: 'var(--pink)' },
  { id: 'works',      label: 'works',      icon: 'lime',   acc: 'var(--lime)' },
  { id: 'skills',     label: 'skills',     icon: 'yellow', acc: 'var(--yellow)' },
  { id: 'education',  label: 'education',  icon: 'blue',   acc: 'var(--blue)' },
  { id: 'experience', label: 'experience', icon: 'pink',   acc: 'var(--pink)' },
  { id: 'languages',  label: 'languages',  icon: 'lime',   acc: 'var(--lime)' },
  { id: 'contact',    label: 'contact',    icon: 'blue',   acc: 'var(--blue)' },
  { id: 'cv',         label: 'resume.pdf', icon: 'yellow', acc: 'var(--yellow)' },
];

export const SEC: Record<SectionId, SectionDef> = Object.fromEntries(
  SECTIONS.map(s => [s.id, s])
) as Record<SectionId, SectionDef>;

export const APP_META: Record<SectionId, { bg: string; fg: string }> = {
  about:      { bg: 'var(--pink)',   fg: '#fff' },
  personal:   { bg: 'var(--blue)',   fg: '#fff' },
  works:      { bg: 'var(--lime)',   fg: 'var(--ink)' },
  skills:     { bg: 'var(--yellow)', fg: 'var(--ink)' },
  education:  { bg: 'var(--blue)',   fg: '#fff' },
  experience: { bg: 'var(--pink)',   fg: '#fff' },
  languages:  { bg: 'var(--lime)',   fg: 'var(--ink)' },
  contact:    { bg: 'var(--blue)',   fg: '#fff' },
  cv:         { bg: 'var(--yellow)', fg: 'var(--ink)' },
};

export const DOCK_IDS: SectionId[] = ['about', 'works', 'contact', 'cv'];
