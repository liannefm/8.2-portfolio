import { useState, useEffect } from 'react';
import type { Lang } from '@/types';
import type { LangStrings, WorkDetailData } from '@/i18n/strings';

const API_URL = 'http://localhost/8.2-portfolio/api/portfolio.php';
const MEDIA_BASE = 'http://localhost/8.2-portfolio/uploads/projects/';

interface ApiProfile {
  name: string;
  role: string;
  bio_nl: string;
  bio_en: string;
  avatar_url: string;
  greeting_nl: string;
  greeting_en: string;
}

interface ApiSkill {
  name: string;
  description_nl: string;
  description_en: string;
  color: string;
}

interface ApiLanguage {
  name: string;
  level_nl: string;
  level_en: string;
  proficiency: number;
}

interface ApiEducation {
  year_start: string;
  year_end: string | null;
  degree_nl: string;
  degree_en: string;
  organization: string;
  description_nl: string | null;
  description_en: string | null;
}

interface ApiExperience {
  year_start: string;
  year_end: string | null;
  position_nl: string;
  position_en: string;
  company: string;
  description_nl: string | null;
  description_en: string | null;
}

interface ApiContact {
  platform: string;
  url: string;
  label: string;
}

interface ApiMedia {
  kind: 'photo' | 'code';
  image_url: string;
  caption_nl: string | null;
  caption_en: string | null;
}

interface ApiProject {
  title_nl: string;
  title_en: string;
  description_nl: string;
  description_en: string;
  thumbnail_url: string | null;
  date_nl: string | null;
  date_en: string | null;
  role_nl: string | null;
  role_en: string | null;
  type_nl: string | null;
  type_en: string | null;
  duration: string | null;
  intro_nl: string | null;
  intro_en: string | null;
  outcome_nl: string | null;
  outcome_en: string | null;
  live_url: string | null;
  source_url: string | null;
  video_url: string | null;
  tags: string[];
  highlights: Array<{ text_nl: string; text_en: string }>;
  media: ApiMedia[];
}

interface ApiMoodItem {
  type: 'photo' | 'note';
  key_name: string;
  caption_nl: string | null;
  caption_en: string | null;
  image_url: string | null;
  pos_x: number | null;
  pos_y: number | null;
}

interface ApiFact {
  text_nl: string;
  text_en: string;
}

interface ApiData {
  profile: ApiProfile;
  skills: ApiSkill[];
  languages: ApiLanguage[];
  education: ApiEducation[];
  experience: ApiExperience[];
  contacts: ApiContact[];
  projects: ApiProject[];
  moodItems: ApiMoodItem[];
  facts: ApiFact[];
}

function yearRange(start: string, end: string | null, lang: Lang): string {
  const s = start;
  if (!end) return `${s} — ${lang === 'en' ? 'now' : 'heden'}`;
  return `${s} — ${end}`;
}

function buildStrings(data: ApiData, lang: Lang): LangStrings {
  const l = lang;
  const p = data.profile;

  const photos = data.moodItems.filter(m => m.type === 'photo');
  const notes = data.moodItems.filter(m => m.type === 'note');

  const caps: Record<string, string> = {};
  photos.forEach(m => { caps[m.key_name] = (l === 'nl' ? m.caption_nl : m.caption_en) || ''; });

  const noteMap: Record<string, string> = {};
  notes.forEach(m => { noteMap[m.key_name] = (l === 'nl' ? m.caption_nl : m.caption_en) || ''; });

  return {
    role: p.role,
    titles: {
      about: l === 'en' ? 'About me' : 'Over mij',
      works: l === 'en' ? 'Works' : 'Projecten',
      skills: l === 'en' ? 'Skills' : 'Skills',
      education: l === 'en' ? 'Education' : 'Opleiding',
      experience: l === 'en' ? 'Experience' : 'Werkervaring',
      languages: l === 'en' ? 'Languages' : 'Talen',
      contact: 'Contact',
      cv: 'Resume',
      personal: l === 'en' ? 'Personal' : 'Persoonlijk',
    },
    about: {
      greet: l === 'en'
        ? ['Welcome! I am ', p.name + ' ✨']
        : ['Welkom! Ik ben ', p.name + ' ✨'],
      lead: l === 'nl' ? p.bio_nl : p.bio_en,
      chips: l === 'en'
        ? ['responsive', 'accessible', 'creative', 'detail-oriented']
        : ['responsive', 'toegankelijk', 'creatief', 'detailgericht'],
      callout: l === 'en'
        ? ['From idea to a fully refined website, down to the very last ', 'detail', '.']
        : ['Van idee naar een website die klopt tot in de kleinste ', 'details', '.'],
      langCard: l === 'en' ? 'Languages' : 'Talen',
    },
    personal: {
      title: l === 'en' ? ['A little ', 'about me'] : ['Even ', 'voorstellen'],
      lead: l === 'en'
        ? 'A little mood board of things that make me happy.'
        : 'Een klein prikbord met de dingen waar ik blij van word.',
      caps,
      notes: noteMap,
      facts: data.facts.map(f => l === 'nl' ? f.text_nl : f.text_en),
      note: '',
    },
    skills: {
      title: l === 'en' ? ['My ', 'stack'] : ['Mijn ', 'stack'],
      lead: l === 'en'
        ? 'The languages and frameworks I work with every day.'
        : 'De talen en frameworks waar ik dagelijks mee werk.',
      items: data.skills.map(s => ({
        name: s.name,
        use: l === 'nl' ? s.description_nl : s.description_en,
        color: s.color,
      })),
    },
    works: {
      title: l === 'en' ? ['My ', 'timeline'] : ['Mijn ', 'tijdlijn'],
      lead: l === 'en'
        ? 'My projects in order — from my first web page to now.'
        : 'Mijn projecten op een rij — van mijn eerste webpagina tot nu.',
      view: l === 'en' ? 'View' : 'Bekijk',
      shot: 'screenshot —',
      items: data.projects.map(proj => ({
        name: l === 'nl' ? proj.title_nl : proj.title_en,
        img: proj.thumbnail_url || '',
        desc: l === 'nl' ? proj.description_nl : proj.description_en,
      })),
    },
    education: {
      title: l === 'en' ? ['My ', 'education'] : ['Mijn ', 'opleiding'],
      items: data.education.map(e => ({
        yr: yearRange(e.year_start, e.year_end, l),
        h: l === 'nl' ? e.degree_nl : e.degree_en,
        org: e.organization,
        p: (l === 'nl' ? e.description_nl : e.description_en) || '',
      })),
    },
    experience: {
      title: l === 'en' ? ['Work ', 'experience'] : ['Werk', 'ervaring'],
      items: data.experience.map(e => ({
        yr: yearRange(e.year_start, e.year_end, l),
        h: l === 'nl' ? e.position_nl : e.position_en,
        org: e.company,
        p: (l === 'nl' ? e.description_nl : e.description_en) || '',
      })),
    },
    languages: {
      title: l === 'en' ? ['', 'Languages'] : ['', 'Talen'],
      lead: l === 'en' ? 'Languages I communicate in.' : 'Talen waarin ik communiceer.',
      rows: data.languages.map(r => ({
        name: r.name,
        level: l === 'nl' ? r.level_nl : r.level_en,
        n: r.proficiency,
        note: '',
        color: 'var(--pink)',
      })),
      note: l === 'en'
        ? 'I switch effortlessly between both — in meetings, in code and in documentation.'
        : 'Ik schakel moeiteloos tussen beide talen — in overleg, in code en in documentatie.',
    },
    contact: {
      title: ["Let's ", 'connect'],
      lead: l === 'en'
        ? "Want to work together? Feel free to drop a message."
        : 'Zin om samen te werken? Stuur gerust een bericht.',
      rows: data.contacts.map(c => [c.platform, c.label] as [string, string]),
    },
    cv: {
      title: l === 'en' ? ['My ', 'CV'] : ['Mijn ', 'CV'],
      open: l === 'en' ? 'Open in browser' : 'Open in browser',
      download: l === 'en' ? 'Download PDF' : 'Download PDF',
      openToast: l === 'en' ? 'Opening resume in a new tab ✦' : 'Resume openen in nieuw tabblad ✦',
      dlToast: l === 'en' ? 'Link your own PDF file here ✦' : 'Koppel hier je eigen PDF-bestand ✦',
    },
  };
}

function buildWorkDates(data: ApiData): Record<Lang, string[]> {
  return {
    nl: data.projects.map(p => p.date_nl || ''),
    en: data.projects.map(p => p.date_en || ''),
  };
}

function buildWorkTags(data: ApiData): string[][] {
  return data.projects.map(p => p.tags);
}

function buildWorkDetail(data: ApiData): Record<Lang, WorkDetailData[]> {
  const build = (l: Lang): WorkDetailData[] =>
    data.projects.map(p => ({
      role: (l === 'nl' ? p.role_nl : p.role_en) || '',
      type: (l === 'nl' ? p.type_nl : p.type_en) || '',
      dur: p.duration || '',
      intro: (l === 'nl' ? p.intro_nl : p.intro_en) || '',
      bullets: p.highlights.map(h => l === 'nl' ? h.text_nl : h.text_en),
      outcome: (l === 'nl' ? p.outcome_nl : p.outcome_en) || '',
    }));
  return { nl: build('nl'), en: build('en') };
}

export interface MediaItem {
  img: string;
  cap: string;
}

export interface WorkLinks {
  live: string;
  source: string;
  video: string;
}

function buildWorkLinks(data: ApiData): WorkLinks[] {
  // video_url is óf een externe link (YouTube/Vimeo) óf een geüpload bestand in uploads/projects
  return data.projects.map(p => ({
    live: p.live_url || '',
    source: p.source_url || '',
    video: p.video_url ? (/^https?:\/\//i.test(p.video_url) ? p.video_url : MEDIA_BASE + p.video_url) : '',
  }));
}

function buildWorkMedia(data: ApiData): Record<Lang, Array<{ photos: MediaItem[]; code: MediaItem[] }>> {
  const build = (l: Lang) =>
    data.projects.map(p => {
      const toItem = (m: ApiMedia): MediaItem => ({
        img: MEDIA_BASE + m.image_url,
        cap: (l === 'nl' ? m.caption_nl : m.caption_en) || '',
      });
      return {
        photos: (p.media || []).filter(m => m.kind === 'photo').map(toItem),
        code: (p.media || []).filter(m => m.kind === 'code').map(toItem),
      };
    });
  return { nl: build('nl'), en: build('en') };
}

export interface PortfolioData {
  strings: Record<Lang, LangStrings>;
  workDates: Record<Lang, string[]>;
  workTags: string[][];
  workDetail: Record<Lang, WorkDetailData[]>;
  workLinks: WorkLinks[];
  workMedia: Record<Lang, Array<{ photos: MediaItem[]; code: MediaItem[] }>>;
  contacts: ApiContact[];
  moodItems: ApiMoodItem[];
}

export function usePortfolio() {
  const [data, setData] = useState<PortfolioData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetch(API_URL)
      .then(res => {
        if (!res.ok) throw new Error('API error');
        return res.json();
      })
      .then((api: ApiData) => {
        setData({
          strings: { nl: buildStrings(api, 'nl'), en: buildStrings(api, 'en') },
          workDates: buildWorkDates(api),
          workTags: buildWorkTags(api),
          workDetail: buildWorkDetail(api),
          workLinks: buildWorkLinks(api),
          workMedia: buildWorkMedia(api),
          contacts: api.contacts,
          moodItems: api.moodItems,
        });
        setLoading(false);
      })
      .catch(err => {
        setError(err.message);
        setLoading(false);
      });
  }, []);

  return { data, loading, error };
}
