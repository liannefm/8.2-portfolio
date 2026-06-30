/* ============================================================
   i18n.jsx — taal (NL / EN) context + alle teksten
   ============================================================ */
const LangCtx = React.createContext({ lang:'nl', setLang:()=>{} });
const useLang = () => React.useContext(LangCtx);

const STRINGS = {
  nl: {
    role: 'front-end developer',
    titles: {
      about:'Over mij', works:'Projecten', skills:'Skills', education:'Opleiding',
      experience:'Werkervaring', languages:'Talen', contact:'Contact', cv:'Resume',
      personal:'Persoonlijk',
    },
    about: {
      greet:['hi! ik ben ', 'Lianne'],
      lead:'Ik ben een front-end developer met een passie voor gebruiksvriendelijke interfaces en responsive design. Ik geniet ervan om ideeën om te zetten in snelle, toegankelijke en aantrekkelijke webervaringen.',
      chips:['responsive','toegankelijk','snel','clean code'],
      callout:['Ik bouw interfaces die ', 'soepel', ' aanvoelen — van idee tot werkend product.'],
      langCard:'Talen',
    },
    personal: {
      title:['Even ', 'voorstellen'],
      lead:'Een klein prikbord met de dingen waar ik blij van word. Sleep je eigen foto\u2019s op het bord.',
      caps:{ me:'dit ben ik :)', weekend:'weekend vibes', setup:'mijn setup', coffee:'koffie \u2615', creative:'even creatief', friends:'met vriendinnen' },
      notes:{ music:'altijd muziek aan terwijl ik code \uD83C\uDFA7', todo:'to-do: vaker naar buiten \uD83C\uDF3F', quote:'\u201Cmaak het simpel, maar bijzonder.\u201D' },
      facts:['koffieliefhebber','gamen','fotografie','muziek 24/7','tekenen','reizen'],
      note:'',
    },
    skills: { title:['Mijn ', 'stack'], lead:'De talen en frameworks waar ik dagelijks mee werk.',
      items:[
        { name:'HTML',  use:'Semantische, toegankelijke structuur voor elke pagina.', color:'var(--pink)' },
        { name:'CSS',   use:'Responsive layouts, animaties en dat net even strakke detail.', color:'var(--blue)' },
        { name:'React', use:'Componenten en interactie voor dynamische interfaces.', color:'var(--lime)' },
        { name:'PHP',   use:'Server-side logica en formulieren die gewoon werken.', color:'var(--yellow)' },
        { name:'SQL',   use:'Data opslaan, koppelen en ophalen uit databases.', color:'var(--pink)' },
      ] },
    works: {
      title:['Mijn ', 'tijdlijn'], lead:'Mijn projecten op een rij — van mijn eerste webpagina tot nu.',
      view:'Bekijk', shot:'screenshot —',
      items:[
        { name:'YOUR own webpage', img:'assets/work-own-webpage.png', desc:'Mijn eerste statische webpagina — de basis van HTML & CSS in de praktijk gebracht.' },
        { name:'Webshop', img:'assets/work-webshop.png', desc:'Een werkende webshop met productoverzicht, winkelmandje en afrekenflow.' },
        { name:'WordPress website', img:'assets/work-wordpress.png', desc:'Een volledig ingerichte website gebouwd in WordPress — met thema, paginabeheer en aanpasbare content.' },
        { name:'Vuurmeisje & Watermeisje game', img:'assets/work-firewater.png', desc:'Een browserspel waarin twee spelers samenwerken — geïnspireerd op het bekende co-op concept.' },
        { name:'Kiosk', img:'assets/work-kiosk.png', desc:'Een bestel-kiosk voor een vegetarische zaak. Touch-vriendelijke interface om zelf te bestellen en af te rekenen.' },
        { name:'Waarderingsapp', img:'assets/work-rating.png', desc:'Een app waarmee gebruikers studenten kunnen beoordelen. Scores en feedback worden opgeslagen en teruggekoppeld.' },
      ],
    },
    education: {
      title:['Mijn ', 'opleiding'], 
      items:[
        { yr:'2024 — 2028', h:'Creative Software Developer', org:'Grafisch Lyceum Utrecht', p:'' },
        { yr:'2019 — 2024', h:'VMBO-TL', org:'Revius Lyceum Wijk bij Duurstede', p:'' },
      ],
    },
    experience: {
      title:['Werk', 'ervaring'], 
      items:[
        { yr:'2024 — heden', h:'Front-end Developer', org:'Het BUREAU', p:'Bouwen en onderhouden van responsive web-interfaces in React. Samenwerken met design en backend.' },
        { yr:'2023 — 2024', h:'Stage Front-end Developer', org:'Het BUREAU', p:'Meegebouwd aan klantprojecten met HTML, CSS en PHP. Eerste ervaring met databases (SQL).' },
      ],
    },
    languages: {
      title:['', 'Talen'], lead:'Talen waarin ik communiceer.',
      rows:[
        { name:'Nederlands', level:'Moedertaal', n:5, note:'Mijn dagelijkse taal — spreken, schrijven en denken.', color:'var(--pink)' },
        { name:'Engels', level:'Vloeiend · C1', n:4, note:'Vloeiend in woord en geschrift, ook technisch.', color:'var(--blue)' },
      ],
      note:'Ik schakel moeiteloos tussen beide talen — in overleg, in code en in documentatie.',
    },
    contact: {
      title:["Let's ", 'connect'], lead:'Zin om samen te werken? Stuur gerust een bericht. ',
      rows:[ ['Email','lianne@example.com'], ['LinkedIn','linkedin.com/in/lianne-dev'], ['GitHub','github.com/lianne-dev'], ['Instagram','@lianne.codes'] ],
    },
    cv: {
      title:['Mijn ', 'CV'], open:'Open in browser', download:'Download PDF',
      
      openToast:'Resume openen in nieuw tabblad ✦', dlToast:'Koppel hier je eigen PDF-bestand ✦',
    },
  },

  en: {
    role: 'front-end developer',
    titles: {
      about:'About me', works:'Works', skills:'Skills', education:'Education',
      experience:'Experience', languages:'Languages', contact:'Contact', cv:'Resume',
      personal:'Personal',
    },
    about: {
      greet:["hi! I'm ", 'Lianne'],
      lead:"I'm a front-end developer with a passion for user-friendly interfaces and responsive design. I love turning ideas into fast, accessible and attractive web experiences.",
      chips:['responsive','accessible','fast','clean code'],
      callout:['I build interfaces that feel ', 'smooth', ' — from idea to working product.'],
      langCard:'Languages',
    },
    personal: {
      title:['A little ', 'about me'],
      lead:'A little board of the things that make me happy. Drag your own photos onto it.',
      caps:{ me:'this is me :)', weekend:'weekend vibes', setup:'my setup', coffee:'coffee \u2615', creative:'getting creative', friends:'with friends' },
      notes:{ music:'always music on while I code \uD83C\uDFA7', todo:'to-do: get outside more \uD83C\uDF3F', quote:'\u201Ckeep it simple, but special.\u201D' },
      facts:['coffee lover','gaming','photography','music 24/7','drawing','travel'],
      note:'',
    },
    skills: { title:['My ', 'stack'], lead:'The languages and frameworks I work with every day.',
      items:[
        { name:'HTML',  use:'Semantic, accessible structure for every page.', color:'var(--pink)' },
        { name:'CSS',   use:'Responsive layouts, animation and that extra-crisp detail.', color:'var(--blue)' },
        { name:'React', use:'Components and interaction for dynamic interfaces.', color:'var(--lime)' },
        { name:'PHP',   use:'Server-side logic and forms that just work.', color:'var(--yellow)' },
        { name:'SQL',   use:'Storing, linking and querying data in databases.', color:'var(--pink)' },
      ] },
    works: {
      title:['My ', 'timeline'], lead:'My projects in order — from my first web page to now.',
      view:'View', shot:'screenshot —',
      items:[
        { name:'YOUR own webpage', img:'assets/work-own-webpage.png', desc:'My very first static web page — putting the basics of HTML & CSS into practice.' },
        { name:'Webshop', img:'assets/work-webshop.png', desc:'A working webshop with a product overview, shopping cart and checkout flow.' },
        { name:'WordPress website', img:'assets/work-wordpress.png', desc:'A fully built website made in WordPress — with a theme, page management and customisable content.' },
        { name:'Fireboy & Watergirl game', img:'assets/work-firewater.png', desc:'A browser game where two players cooperate — inspired by the well-known co-op concept.' },
        { name:'Kiosk', img:'assets/work-kiosk.png', desc:'A self-order kiosk for a vegetarian eatery. Touch-friendly interface to order and pay yourself.' },
        { name:'Rating app', img:'assets/work-rating.png', desc:'An app that lets users rate students. Scores and feedback are stored and reported back clearly.' },
      ],
    },
    education: {
      title:['My ', 'education'],
      items:[
        { yr:'2024 — 2028', h:'Creative Software Developer', org:'Grafisch Lyceum Utrecht', p:'' },
        { yr:'2019 — 2024', h:'VMBO-TL', org:'Revius Lyceum Wijk bij Duurstede', p:'' },
      ],
    },
    experience: {
      title:['Work ', 'experience'],
      items:[
        { yr:'2024 — now', h:'Front-end Developer', org:'Het BUREAU', p:'Building and maintaining responsive web interfaces in React. Working closely with design and backend.' },
        { yr:'2023 — 2024', h:'Front-end Developer Intern', org:'Het BUREAU', p:'Helped build client projects with HTML, CSS and PHP. First experience with databases (SQL).' },
      ],
    },
    languages: {
      title:['', 'Languages'], lead:'Languages I communicate in.',
      rows:[
        { name:'Dutch', level:'Native', n:5, note:'My everyday language — speaking, writing and thinking.', color:'var(--pink)' },
        { name:'English', level:'Fluent · C1', n:4, note:'Fluent in speech and writing, technical too.', color:'var(--blue)' },
      ],
      note:'I switch effortlessly between both — in meetings, in code and in documentation.',
    },
    contact: {
      title:["Let's ", 'connect'], lead:"Want to work together? Feel free to drop a message. ",
      rows:[ ['Email','lianne@example.com'], ['LinkedIn','linkedin.com/in/lianne-dev'], ['GitHub','github.com/lianne-dev'], ['Instagram','@lianne.codes'] ],
    },
    cv: {
      title:['My ', 'CV'], open:'Open in browser', download:'Download PDF',
      openToast:'Opening resume in a new tab ✦', dlToast:'Link your own PDF file here ✦',
    },
  },
};

/* month labels per language for the works timeline */
const WORK_DATES = {
  nl:['dec 2024','mei 2025','sep 2025','jan 2026','apr 2026','mei 2026'],
  en:['Dec 2024','May 2025','Sep 2025','Jan 2026','Apr 2026','May 2026'],
};

Object.assign(window, { LangCtx, useLang, STRINGS, WORK_DATES });
