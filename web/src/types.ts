export type Lang = 'nl' | 'en';
export type SectionId = 'about' | 'personal' | 'works' | 'skills' | 'education' | 'experience' | 'languages' | 'contact' | 'cv';

export interface SectionDef {
  id: SectionId;
  label: string;
  icon: string;
  acc: string;
}

export interface WindowState {
  id: string;
  section: SectionId;
  x: number;
  y: number;
  w: number;
  h: number;
  z: number;
  minimized: boolean;
}
