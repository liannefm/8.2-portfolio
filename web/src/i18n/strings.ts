import type { Lang, SectionId } from '@/types';

export interface LangStrings {
  role: string;
  titles: Record<SectionId, string>;
  about: {
    greet: [string, string];
    lead: string;
    chips: string[];
    callout: [string, string, string];
    langCard: string;
  };
  personal: {
    title: [string, string];
    lead: string;
    caps: Record<string, string>;
    notes: Record<string, string>;
    facts: string[];
    note: string;
  };
  skills: {
    title: [string, string];
    lead: string;
    items: Array<{ name: string; use: string; color: string }>;
  };
  works: {
    title: [string, string];
    lead: string;
    view: string;
    shot: string;
    items: Array<{ name: string; img: string; desc: string }>;
  };
  education: {
    title: [string, string];
    note: string;
    items: Array<{ yr: string; h: string; org: string; p: string }>;
  };
  experience: {
    title: [string, string];
    note: string;
    items: Array<{ yr: string; h: string; org: string; p: string }>;
  };
  languages: {
    title: [string, string];
    lead: string;
    rows: Array<{ name: string; level: string; n: number; note: string; color: string }>;
    note: string;
  };
  contact: {
    title: [string, string];
    lead: string;
    rows: Array<[string, string]>;
  };
  cv: {
    title: [string, string];
    open: string;
    download: string;
    note: string;
    openToast: string;
    dlToast: string;
  };
}

export const STRINGS: Record<Lang, LangStrings> = {
  nl: {
    role: 'front-end developer',
    titles: {
      about: 'Over mij', works: 'Projecten', skills: 'Skills', education: 'Opleiding',
      experience: 'Werkervaring', languages: 'Talen', contact: 'Contact', cv: 'Resume',
      personal: 'Persoonlijk',
    },
    about: {
      greet: ['Welkom! Ik ben ', 'Lianne ✨'],
      lead: 'Een goed ontwerp verdient een goede uitvoering. Daarom zet ik ideeën om in snelle, toegankelijke en interactieve websites waar gebruikers met plezier doorheen klikken.',
      chips: ['responsive', 'toegankelijk', 'creatief', 'detailgericht'],
      callout: ['Van idee naar een website die klopt tot in de kleinste ', 'details', '.'],
      langCard: 'Talen',
    },
    personal: {
      title: ['Even ', 'voorstellen'],
      lead: 'Een klein prikbord met de dingen waar ik blij van word.',
      caps: { me: 'dit ben ik :)', weekend: 'maak kennis met mijn konijnen', setup: 'mijn setup', coffee: 'dit ben ik nog een keer :)', creative: 'mijn gezin', friends: 'met vriendinnen' },
      notes: { music: 'altijd muziek aan terwijl ik code 🎧', todo: 'to-do: alle konijnen aaien die ik tegenkom', quote: '“maak het simpel, maar bijzonder.”' },
      facts: ['koffieliefhebber', 'gamen', 'fotografie', 'muziek 24/7', 'tekenen', 'reizen'],
      note: '',
    },
    skills: {
      title: ['Mijn ', 'stack'], lead: 'De talen en frameworks waar ik dagelijks mee werk.',
      items: [
        { name: 'HTML', use: 'Semantische, toegankelijke structuur voor elke pagina.', color: 'var(--pink)' },
        { name: 'CSS', use: 'Responsive layouts, animaties en dat net even strakke detail.', color: 'var(--blue)' },
        { name: 'React', use: 'Componenten en interactie voor dynamische interfaces.', color: 'var(--lime)' },
        { name: 'PHP', use: 'Server-side logica en formulieren die gewoon werken.', color: 'var(--yellow)' },
        { name: 'SQL', use: 'Data opslaan, koppelen en ophalen uit databases.', color: 'var(--pink)' },
      ],
    },
    works: {
      title: ['Mijn ', 'tijdlijn'], lead: 'Mijn projecten op een rij — van mijn eerste webpagina tot nu.',
      view: 'Bekijk', shot: 'screenshot —',
      items: [
        { name: 'YOUR own webpage', img: 'work-own-webpage.png', desc: 'Mijn eerste statische webpagina — de basis van HTML & CSS in de praktijk gebracht.' },
        { name: 'Webshop', img: 'work-webshop.png', desc: 'Een werkende webshop met productoverzicht, winkelmandje en afrekenflow.' },
        { name: 'WordPress website', img: 'work-wordpress.png', desc: 'Een volledig ingerichte website gebouwd in WordPress — met thema, paginabeheer en aanpasbare content.' },
        { name: 'Vuurmeisje & Watermeisje game', img: 'work-firewater.png', desc: 'Een browserspel waarin twee spelers samenwerken — geïnspireerd op het bekende co-op concept.' },
        { name: 'Kiosk', img: 'work-kiosk.png', desc: 'Een bestel-kiosk voor een vegetarische zaak. Touch-vriendelijke interface om zelf te bestellen en af te rekenen.' },
        { name: 'Waarderingsapp', img: 'work-rating.png', desc: 'Een app waarmee gebruikers studenten kunnen beoordelen. Scores en feedback worden opgeslagen en teruggekoppeld.' },
      ],
    },
    education: {
      title: ['Mijn ', 'opleiding'], note: '↳ voorbeelddata — vul je eigen opleiding in',
      items: [
        { yr: '2024 — 2028', h: 'Creative Software Developer', org: 'Grafisch Lyceum Utrecht', p: '' },
        { yr: '2019 — 2024', h: 'VMBO-TL', org: 'Revius Lyceum Wijk bij Duurstede', p: '' },
      ],
    },
    experience: {
      title: ['Werk', 'ervaring'], note: '↳ voorbeelddata — vul je eigen ervaring in',
      items: [
        { yr: '2024 — heden', h: 'Front-end Developer', org: 'Bedrijfsnaam (voorbeeld)', p: 'Bouwen en onderhouden van responsive web-interfaces in React. Samenwerken met design en backend.' },
        { yr: '2023 — 2024', h: 'Stage Front-end Developer', org: 'Bedrijfsnaam (voorbeeld)', p: 'Meegebouwd aan klantprojecten met HTML, CSS en PHP. Eerste ervaring met databases (SQL).' },
      ],
    },
    languages: {
      title: ['', 'Talen'], lead: 'Talen waarin ik communiceer.',
      rows: [
        { name: 'Nederlands', level: 'Moedertaal', n: 5, note: 'Mijn dagelijkse taal — spreken, schrijven en denken.', color: 'var(--pink)' },
        { name: 'Engels', level: 'Vloeiend · C1', n: 4, note: 'Vloeiend in woord en geschrift, ook technisch.', color: 'var(--blue)' },
      ],
      note: 'Ik schakel moeiteloos tussen beide talen — in overleg, in code en in documentatie.',
    },
    contact: {
      title: ["Let's ", 'connect'], lead: 'Zin om samen te werken? Stuur gerust een bericht. (Voorbeeldgegevens — pas aan naar je echte info.)',
      rows: [['Email', 'lianne@example.com'], ['LinkedIn', 'linkedin.com/in/lianne-dev'], ['GitHub', 'github.com/lianne-dev'], ['Instagram', '@lianne.codes']],
    },
    cv: {
      title: ['Mijn ', 'CV'], open: 'Open in browser', download: 'Download PDF',
      note: 'Voorbeeld-CV — pas de gegevens aan naar je eigen info.',
      openToast: 'Resume openen in nieuw tabblad ✦', dlToast: 'Koppel hier je eigen PDF-bestand ✦',
    },
  },

  en: {
    role: 'front-end developer',
    titles: {
      about: 'About me', works: 'Works', skills: 'Skills', education: 'Education',
      experience: 'Experience', languages: 'Languages', contact: 'Contact', cv: 'Resume',
      personal: 'Personal',
    },
    about: {
      greet: ["Welcome! I am ", 'Lianne✨'],
      lead: "A good design deserves proper execution. That's why I turn ideas into fast, accessible, and interactive websites that people enjoy browsing through.",
      chips: ['responsive', 'accessible', 'creative', 'detail-oriented'],
      callout: ['From idea to a fully refined website, down to the very last ', 'detail', '.'],
      langCard: 'Languages',
    },
    personal: {
      title: ['A little ', 'about me'],
      lead: 'A little mood board of things that make me happy.',
      caps: { me: 'this is me :)', weekend: 'meet the rabbits', setup: 'my setup', coffee: 'this is me again :)', creative: 'my family', friends: 'with friends' },
      notes: { music: 'always music on while I code 🎧', todo: 'to-do: pet all the bunnies I meet', quote: '“keep it simple, but special.”' },
      facts: ['coffee lover', 'gaming', 'photography', 'music 24/7', 'drawing', 'travel'],
      note: '',
    },
    skills: {
      title: ['My ', 'stack'], lead: 'The languages and frameworks I work with every day.',
      items: [
        { name: 'HTML', use: 'Semantic, accessible structure for every page.', color: 'var(--pink)' },
        { name: 'CSS', use: 'Responsive layouts, animation and that extra-crisp detail.', color: 'var(--blue)' },
        { name: 'React', use: 'Components and interaction for dynamic interfaces.', color: 'var(--lime)' },
        { name: 'PHP', use: 'Server-side logic and forms that just work.', color: 'var(--yellow)' },
        { name: 'SQL', use: 'Storing, linking and querying data in databases.', color: 'var(--pink)' },
      ],
    },
    works: {
      title: ['My ', 'timeline'], lead: 'My projects in order — from my first web page to now.',
      view: 'View', shot: 'screenshot —',
      items: [
        { name: 'YOUR own webpage', img: 'work-own-webpage.png', desc: 'My very first static web page — putting the basics of HTML & CSS into practice.' },
        { name: 'Webshop', img: 'work-webshop.png', desc: 'A working webshop with a product overview, shopping cart and checkout flow.' },
        { name: 'WordPress website', img: 'work-wordpress.png', desc: 'A fully built website made in WordPress — with a theme, page management and customisable content.' },
        { name: 'Fireboy & Watergirl game', img: 'work-firewater.png', desc: 'A browser game where two players cooperate — inspired by the well-known co-op concept.' },
        { name: 'Kiosk', img: 'work-kiosk.png', desc: 'A self-order kiosk for a vegetarian eatery. Touch-friendly interface to order and pay yourself.' },
        { name: 'Rating app', img: 'work-rating.png', desc: 'An app that lets users rate students. Scores and feedback are stored and reported back clearly.' },
      ],
    },
    education: {
      title: ['My ', 'education'], note: '↳ sample data — fill in your own education',
      items: [
        { yr: '2024 — 2028', h: 'Creative Software Developer', org: 'Grafisch Lyceum Utrecht', p: '' },
        { yr: '2019 — 2024', h: 'VMBO-TL', org: 'Revius Lyceum Wijk bij Duurstede', p: '' },
      ],
    },
    experience: {
      title: ['Work ', 'experience'], note: '↳ sample data — fill in your own experience',
      items: [
        { yr: '2024 — now', h: 'Front-end Developer', org: 'Company name (sample)', p: 'Building and maintaining responsive web interfaces in React. Working closely with design and backend.' },
        { yr: '2023 — 2024', h: 'Front-end Developer Intern', org: 'Company name (sample)', p: 'Helped build client projects with HTML, CSS and PHP. First experience with databases (SQL).' },
      ],
    },
    languages: {
      title: ['', 'Languages'], lead: 'Languages I communicate in.',
      rows: [
        { name: 'Dutch', level: 'Native', n: 5, note: 'My everyday language — speaking, writing and thinking.', color: 'var(--pink)' },
        { name: 'English', level: 'Fluent · C1', n: 4, note: 'Fluent in speech and writing, technical too.', color: 'var(--blue)' },
      ],
      note: 'I switch effortlessly between both — in meetings, in code and in documentation.',
    },
    contact: {
      title: ["Let's ", 'connect'], lead: "Want to work together? Feel free to drop a message. (Sample details — replace with your real info.)",
      rows: [['Email', 'lianne@example.com'], ['LinkedIn', 'linkedin.com/in/lianne-dev'], ['GitHub', 'github.com/lianne-dev'], ['Instagram', '@lianne.codes']],
    },
    cv: {
      title: ['My ', 'CV'], open: 'Open in browser', download: 'Download PDF',
      note: 'Sample CV — replace the details with your own info.',
      openToast: 'Opening resume in a new tab ✦', dlToast: 'Link your own PDF file here ✦',
    },
  },
};

export const WORK_DATES: Record<Lang, string[]> = {
  nl: ['dec 2024', 'mei 2025', 'sep 2025', 'jan 2026', 'apr 2026', 'mei 2026'],
  en: ['Dec 2024', 'May 2025', 'Sep 2025', 'Jan 2026', 'Apr 2026', 'May 2026'],
};

export const WORK_TAGS = [
  ['HTML', 'CSS', 'JS'],
  ['PHP', 'CSS', 'SQL'],
  ['WordPress', 'PHP', 'CSS', 'SQL'],
  ['HTML', 'PHP', 'CSS', 'JS'],
  ['React', 'SCSS', 'TypeScript', 'JSON', 'SQL'],
  ['Vue', 'Nuxt', 'SQL', 'TypeScript'],
];

export const DETAIL_UI: Record<Lang, Record<string, string>> = {
  nl: { back: 'terug naar projecten', role: 'Rol', type: 'Type', dur: 'Duur', date: 'Datum', done: 'Wat ik deed', stack: 'Gebruikte technieken', gallery: 'Meer beelden', galleryNote: 'voorbeeld — ruimte voor extra screenshots', live: 'Live bekijken', source: 'Broncode' },
  en: { back: 'back to projects', role: 'Role', type: 'Type', dur: 'Duration', date: 'Date', done: 'What I did', stack: 'Tech used', gallery: 'More visuals', galleryNote: 'placeholder — space for extra screenshots', live: 'View live', source: 'Source code' },
};

export interface WorkDetailData {
  role: string;
  type: string;
  dur: string;
  intro: string;
  bullets: string[];
  outcome: string;
}

export const WORK_DETAIL: Record<Lang, WorkDetailData[]> = {
  nl: [
    { role: 'Soloproject', type: 'Statische website', dur: '± 1 week', intro: 'Mijn allereerste webpagina, helemaal vanaf nul opgebouwd met pure HTML en CSS. Een klein project waarin ik de basis van semantische structuur, typografie en kleurgebruik onder de knie kreeg.', bullets: ['Pagina opgebouwd met semantische HTML', 'Layout en kleuren volledig in CSS gestyled', 'Responsive gemaakt voor mobiel en desktop'], outcome: 'De basis waarop al mijn latere projecten verder bouwen.' },
    { role: 'Schoolproject', type: 'Webshop', dur: '± 4 weken', intro: 'Een volledig werkende webshop met productoverzicht, winkelmandje en afrekenflow. Producten en bestellingen worden opgeslagen in een database en aangestuurd met PHP.', bullets: ['Productoverzicht uit een MySQL-database geladen', 'Winkelmandje met sessies in PHP', 'Afrekenflow met formuliervalidatie'], outcome: 'Mijn eerste echte kennismaking met back-end en databases.' },
    { role: 'Soloproject', type: 'CMS-website', dur: '± 3 weken', intro: 'Een volledig ingerichte konijnen-blog gebouwd in WordPress, met een eigen thema, paginabeheer en aanpasbare content zodat de eigenaar zelf blogs kan plaatsen.', bullets: ['Eigen WordPress-thema opgezet en gestyled', 'Pagina- en blogstructuur ingericht', 'Content beheerbaar gemaakt voor de klant'], outcome: 'Een site die de eigenaar volledig zelf kan onderhouden.' },
    { role: 'Duoproject', type: 'Browsergame', dur: '± 5 weken', intro: 'Een co-op browsergame waarin twee spelers samenwerken om door een winters level te komen — geïnspireerd op het bekende Fireboy & Watergirl-concept, volledig speelbaar in de browser.', bullets: ['Spellogica en collision-detection in JavaScript', 'Besturing voor twee spelers op één toetsenbord', 'Levels, obstakels en doelen ontworpen'], outcome: 'Een speelbaar spel waar je samen doorheen puzzelt.' },
    { role: 'Stageproject', type: 'Bestel-kiosk', dur: '± 8 weken', intro: 'Een touch-vriendelijke bestel-kiosk voor een vegetarische zaak. Gebruikers stellen zelf hun bestelling samen en rekenen af. Gebouwd in React met TypeScript.', bullets: ['Touch-interface ontworpen voor zelf bestellen', 'Menu en bestellingen via een API gekoppeld', 'State-beheer met React en TypeScript'], outcome: 'Een vlotte selfservice-ervaring van menu tot afrekenen.' },
    { role: 'Schoolproject', type: 'Webapp', dur: '± 6 weken', intro: 'Een app waarmee gebruikers studenten kunnen beoordelen. Scores en feedback worden opgeslagen en overzichtelijk teruggekoppeld. Gebouwd met Vue en Nuxt.', bullets: ['Inloggen met rollen (student / docent)', 'Beoordelingen opslaan in een database', 'Resultaten visueel teruggekoppeld'], outcome: 'Inzicht in feedback, in één overzichtelijke app.' },
  ],
  en: [
    { role: 'Solo project', type: 'Static website', dur: '± 1 week', intro: 'My very first web page, built completely from scratch with pure HTML and CSS. A small project where I got to grips with semantic structure, typography and colour.', bullets: ['Built the page with semantic HTML', 'Styled the full layout and colours in CSS', 'Made it responsive for mobile and desktop'], outcome: 'The foundation all my later projects build on.' },
    { role: 'School project', type: 'Webshop', dur: '± 4 weeks', intro: 'A fully working webshop with a product overview, shopping cart and checkout flow. Products and orders are stored in a database and driven by PHP.', bullets: ['Loaded the product overview from a MySQL database', 'Shopping cart with sessions in PHP', 'Checkout flow with form validation'], outcome: 'My first real taste of back-end and databases.' },
    { role: 'Solo project', type: 'CMS website', dur: '± 3 weeks', intro: 'A fully built rabbit blog made in WordPress, with a custom theme, page management and editable content so the owner can post blogs themselves.', bullets: ['Set up and styled a custom WordPress theme', 'Arranged the page and blog structure', 'Made content manageable for the client'], outcome: 'A site the owner can fully maintain on their own.' },
    { role: 'Duo project', type: 'Browser game', dur: '± 5 weeks', intro: 'A co-op browser game where two players work together to get through a wintry level — inspired by the well-known Fireboy & Watergirl concept, fully playable in the browser.', bullets: ['Game logic and collision detection in JavaScript', 'Two-player controls on a single keyboard', 'Designed levels, obstacles and goals'], outcome: 'A playable game you puzzle through together.' },
    { role: 'Internship project', type: 'Order kiosk', dur: '± 8 weeks', intro: 'A touch-friendly order kiosk for a vegetarian eatery. Users put together their own order and pay. Built in React with TypeScript.', bullets: ['Designed a touch interface for self-ordering', 'Connected menu and orders through an API', 'State management with React and TypeScript'], outcome: 'A smooth self-service experience from menu to checkout.' },
    { role: 'School project', type: 'Web app', dur: '± 6 weeks', intro: 'An app that lets users rate students. Scores and feedback are stored and reported back clearly. Built with Vue and Nuxt.', bullets: ['Login with roles (student / teacher)', 'Stored ratings in a database', 'Reported results back visually'], outcome: 'Insight into feedback, in one clear app.' },
  ],
};
