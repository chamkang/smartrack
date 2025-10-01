document.addEventListener('DOMContentLoaded', () => {
  // Load saved language or default to French
  const savedLanguage = localStorage.getItem('preferredLanguage') || 'fr';
  loadTranslations(savedLanguage);

  // Set up event listeners for language links
  document.getElementById('lang-en').addEventListener('click', (e) => {
      e.preventDefault(); // Prevent default anchor behavior
      setLanguage('en');
  });
  document.getElementById('lang-fr').addEventListener('click', (e) => {
      e.preventDefault(); // Prevent default anchor behavior
      setLanguage('fr');
  });
});

function setLanguage(language) {
  // Save the selected language in local storage
  localStorage.setItem('preferredLanguage', language);
  // Load translations for the selected language
  loadTranslations(language);
}

function loadTranslations(language) {
  fetch('assets/js/translations.json')
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok: ' + response.statusText);
          }
          return response.json();
      })
      .then(data => {
          const translations = data[language];
          if (!translations) {
              throw new Error('Translations not found for: ' + language);
          }

          // List of IDs to update
          const ids = [
              "n1", "n2", "n3", "n4", "welcome", "description", "start",
              "trans", "camer", "offer", "quote", "request", "sent", "get",
              "benefit", "stay", "real", "monitor", "con", "langu", "two",
              "man", "rel", "acc", "form", "ft", "tor", "veh", "gain",
              "rity", "tect", "fire", "ahead", "remote", "entry", "hen",
              "detail", "adv", "empower", "auto", "geo", "driven", "han",
              "time", "driver", "advan", "custom", "sgps", "gtrack", "livel",
              "efi", "ins", "dbe", "safety", "fuel", "perf", "advanced",
              "scam", "control", "alerts", "customreport", "reporttools",
              "tailor", "decision", "automated", "testimony", "here", "blog",
              "ho", "ab", "carr", "terms", "pri", "fmanage", "fumanage",
              "firedet", "network", "vsurv", "timeand", "access", "abo",
              "hom", "abbo", "abou", "since", "who", "lead", "watch",
              "stats", "commitment", "happ", "proj", "hours", "hard", 
              "tem", "strong", "fuelmanagement", "fleetmanagement",
               "tracking", "ourfuel", "ourfleet","ourtracking", "videosurv", "accessit", 
               "fireit", "nas", "timeman", "videosys", "accesscon", "networksys", "firesys",
               "timesys"
               
          ];

          // Update the content for each ID
          ids.forEach(id => {
              const element = document.getElementById(id);
              if (element) {
                  element.innerText = translations[id];
              }
          });

          // Hide loading message and show body
          //document.getElementById('loading').style.display = 'none';
          //document.body.classList.add('loaded');
      })
      .catch(error => {
          console.error('Error loading translations:', error);
          document.getElementById('loading').textContent = 'Failed to load translations.';
      });
}

 