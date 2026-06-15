document.addEventListener('DOMContentLoaded', () => {
  // Use the server cookie language if available, else localStorage, else 'en'
  function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
  }
  const savedLanguage = getCookie('site_lang') || localStorage.getItem('preferredLanguage') || 'en';
  loadTranslations(savedLanguage);

  const enLink = document.getElementById('lang-en');
  const frLink = document.getElementById('lang-fr');
  if (enLink) enLink.addEventListener('click', (e) => { e.preventDefault(); setLanguage('en'); });
  if (frLink) frLink.addEventListener('click', (e) => { e.preventDefault(); setLanguage('fr'); });
});

function setLanguage(language) {
  localStorage.setItem('preferredLanguage', language);
  // Set cookie and reload so PHP also switches
  document.cookie = 'site_lang=' + language + ';path=/;max-age=' + (60*60*24*30);
  // Preserve existing query params (e.g. ?id=5 on service/blog-post pages)
  const params = new URLSearchParams(window.location.search);
  params.set('lang', language);
  window.location.search = params.toString();
}

function loadTranslations(language) {
  // Determine base path
  const base = document.querySelector('meta[name="base-url"]')
    ? document.querySelector('meta[name="base-url"]').content
    : '';

  fetch(base + '/translations.php?lang=' + language)
    .then(r => r.json())
    .then(translations => {
      const ids = [
        "n1","n2","n3","n4","welcome","description","start",
        "trans","camer","offer","quote","request","sent","get",
        "benefit","stay","real","monitor","con","langu","two",
        "man","rel","acc","form","ft","tor","veh","gain",
        "rity","tect","fire","ahead","remote","entry","hen",
        "detail","adv","empower","auto","geo","driven","han",
        "time","driver","advan","custom","sgps","gtrack","livel",
        "efi","ins","dbe","safety","fuel","perf","advanced",
        "scam","control","alerts","customreport","reporttools",
        "tailor","decision","automated","testimony","here","blog",
        "ho","ab","carr","terms","pri","fmanage","fumanage",
        "firedet","network","vsurv","timeand","access","abo",
        "hom","abbo","abou","since","who","lead","watch",
        "stats","commitment","happ","proj","hours","hard",
        "tem","strong","fuelmanagement","fleetmanagement",
        "tracking","ourfuel","ourfleet","ourtracking","videosurv",
        "accessit","fireit","nas","timeman","videosys","accesscon",
        "networksys","firesys","timesys"
      ];
      ids.forEach(id => {
        const el = document.getElementById(id);
        if (el && translations[id]) el.innerText = translations[id];
      });
      // Update language display
      const langu = document.getElementById('langu');
      if (langu) langu.innerText = language.toUpperCase();
    })
    .catch(err => console.warn('Translation load error:', err));
}
