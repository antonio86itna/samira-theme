# README.md - Samira Theme

## 🎨 Samira Mahmoodi WordPress Theme

Un tema WordPress moderno, elegante e completamente personalizzabile, progettato specificamente per scrittori, artisti e creatori di contenuti. 

### ✨ Caratteristiche Principali

- **🌙 Dark Mode Funzionale** - Toggle dark/light con persistenza preferenze utente
- **📧 Integrazione Newsletter** - Supporto nativo per Mailchimp e Brevo  
- **🎛️ Pannello Admin Personalizzato** - Interfaccia intuitiva per personalizzazioni
- **📱 Design Responsivo** - Ottimizzato per tutti i dispositivi
- **⚡ Performance Ottimizzate** - Lazy loading, CSS/JS minificati, caching-ready
- **♿ Accessibilità WCAG** - Navigazione da tastiera, screen reader friendly
- **🎨 Personalizzazione Avanzata** - Colori, tipografia, layout modificabili

### 🚀 Demo dal Vivo

Visualizza il tema in azione: [Demo Samira Theme](https://samira-theme-demo.com)

### 📋 Requisiti

- WordPress 5.0+
- PHP 7.4+  
- MySQL 5.6+
- Memoria PHP: 128MB+ (raccomandato 256MB)

### ⚙️ Installazione Rapida

1. **Scarica il tema** come file ZIP
2. **WordPress Admin** → Aspetto → Temi → Aggiungi Nuovo → Carica Tema
3. **Attiva il tema** "Samira Theme" 
4. **Configura** tramite il pannello "Samira Theme" nel menu admin

### 🛠️ Configurazione

#### Pannello di Controllo
Il tema include un pannello admin completo accessibile da `WordPress Admin > Samira Theme`:

- **Impostazioni Generali** - Logo, copyright, footer, ottimizzazioni
- **Sezione Hero** - Titolo, sottotitolo, immagine principale  
- **Chi Sono** - Biografia e storia personale
- **Scrittura** - Libri pubblicati, copertine, descrizioni
- **Social Media** - Link a profili Instagram, Goodreads, LinkedIn, etc.
- **Personalizzazione Stile** - Colori, dark mode, tipografia
- **Newsletter** - Configurazione Mailchimp/Brevo con test connessione
- **Statistiche** - Metriche tema e newsletter
- **Import/Export** - Backup e ripristino configurazioni

#### Newsletter Setup

**Mailchimp:**
1. Crea account e Audience  
2. Genera API Key da Account → Extras → API keys
3. Inserisci API Key e Audience ID nel pannello Newsletter
4. Testa connessione

**Brevo (SendinBlue):**  
1. Crea account e Lista contatti
2. Genera API Key da Account → SMTP & API → API Keys  
3. Inserisci API Key e List ID nel pannello Newsletter
4. Testa connessione

### 📚 Struttura File

```
samira-theme/
├── style.css                    # CSS principale + header tema
├── functions.php                # Funzioni principali tema
├── index.php                    # Template principale
├── header.php                   # Header template  
├── footer.php                   # Footer template
├── inc/
│   ├── theme-options.php        # Gestione opzioni tema
│   ├── newsletter-integration.php # Integrazione newsletter
│   └── customizer.php           # WordPress Customizer
├── admin/
│   ├── theme-admin.php          # Pannello amministrazione
│   ├── admin-style.css          # Stili pannello admin
│   └── admin-script.js          # JavaScript pannello admin  
├── js/
│   ├── main.js                  # JavaScript principale
│   └── dark-mode.js             # Funzionalità dark mode
├── css/
│   └── [file CSS aggiuntivi]
├── images/
│   └── [placeholder e assets]
├── template-parts/
│   └── [template parziali]
└── screenshot.png               # Screenshot tema
```

### 🎨 Personalizzazione

#### Colori
Il tema usa variabili CSS per facile personalizzazione:

```css
:root {
  --color-accent: #D4A574;        /* Colore principale */
  --color-background: #fcfcf9;    /* Sfondo chiaro */
  --color-text: #1f2121;          /* Testo principale */
}

/* Dark mode */
.dark-mode {
  --color-background: #1a1a1a;    /* Sfondo scuro */
  --color-text: #f5f5f5;          /* Testo chiaro */
}
```

#### Child Theme (Raccomandato)
Per modifiche avanzate, crea un child theme:

```php
<?php
/*
Theme Name: Samira Child  
Template: samira-theme
Version: 1.0.0
*/

// Enqueue parent theme styles
function samira_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'samira_child_enqueue_styles');
```

### 🔌 Hook e Filter

Il tema fornisce hook personalizzati per sviluppatori:

```php
// Modificare opzioni di default
add_filter('samira_get_option', 'my_custom_option', 10, 3);

// Dopo salvataggio opzioni
add_action('samira_option_updated', 'my_callback', 10, 3);  

// Dopo reset opzioni
add_action('samira_options_reset', 'my_reset_callback');

// Dopo import configurazioni  
add_action('samira_options_imported', 'my_import_callback', 10, 1);
```

### 📊 Performance

Il tema include ottimizzazioni automatiche:
- **Lazy loading** immagini
- **Minificazione** CSS/JS automatica
- **Pulizia wp_head** da tag non necessari
- **Disabilitazione emoji** WordPress (opzionale)
- **Supporto caching** browser e plugin
- **Ottimizzazione database** query

#### Considerazioni sulle Performance

- **Compatibilità con la cache**: testato con plugin come W3 Total Cache e WP Rocket; svuota la cache dopo gli aggiornamenti del tema.
- **Minimizzazione degli asset**: vengono caricati solo script e stili essenziali per ridurre le richieste HTTP.

### ♿ Accessibilità  

- Markup **HTML5 semantico**
- **Navigazione da tastiera** completa
- **Screen reader** compatible
- **Contrasti colori** WCAG AA conformi  
- **Focus indicators** visibili
- **Alt text** obbligatori per immagini
- **Skip links** per navigazione rapida

### 📱 Responsive Design

- **Mobile-first** approach
- **Breakpoints** ottimizzati: 480px, 768px, 1024px, 1200px+
- **Touch-friendly** bottoni e link  
- **Swipe gestures** per gallerie (touch devices)
- **Viewport meta** tag corretto
- **Immagini adaptive** con srcset

### 🔧 Risoluzione Problemi

#### Tema non funziona
- Verifica **requisiti minimi** (WordPress, PHP, MySQL)
- Controlla **permessi file** (755 cartelle, 644 file)  
- Abilita **debug WordPress** per vedere errori
- Disattiva **plugin** per test compatibilità

#### Newsletter non invia
- Verifica **API key** corretta nel pannello
- Testa **connessione** con pulsante dedicato
- Controlla **List/Audience ID** corretto
- Verifica **firewall server** per richieste HTTPS esterne

#### Dark Mode non funziona  
- Svuota **cache browser**
- Controlla **JavaScript** abilitato
- Verifica **console errori** browser
- Testa con browser diverso

#### Performance Lente
- **Ottimizza immagini** (WebP raccomandato)  
- Attiva **plugin caching** (W3 Total Cache, WP Rocket)
- **Minifica CSS/JS** tramite plugin
- Usa **CDN** per contenuti statici

### 🛡️ Sicurezza

- **Sanitizzazione** input utente  
- **Validazione** dati forms
- **Nonce verification** per AJAX  
- **Escape** output HTML
- **Prepared statements** database  
- **Capability checks** funzioni admin

### 📈 SEO Ottimizzato

- **Schema.org** structured data
- **Meta tag** ottimizzati
- **Sitemap XML** compatible  
- **Open Graph** social sharing
- **Twitter Cards** supportate
- **Page speed** ottimizzato
- **Mobile-friendly** design

### 🔄 Aggiornamenti

Il tema segue **Semantic Versioning**:
- **Major** (1.x.x): Cambiamenti breaking
- **Minor** (x.1.x): Nuove funzionalità  
- **Patch** (x.x.1): Bug fixes

### 📝 Changelog

#### v1.0.0 - Prima Release
- Tema base con tutte le funzionalità principali
- Pannello amministrazione completo  
- Integrazione newsletter Mailchimp/Brevo
- Dark mode funzionale
- Design responsive ottimizzato
- Performance e accessibilità ottimizzate

### 🤝 Contribuire

Contributi sono benvenuti! Per contribuire:

1. **Fork** il repository
2. Crea **feature branch** (`git checkout -b feature/AmazingFeature`)
3. **Commit** le modifiche (`git commit -m 'Add AmazingFeature'`)  
4. **Push** al branch (`git push origin feature/AmazingFeature`)
5. Apri **Pull Request**

### 📄 Licenza

Questo tema è rilasciato sotto **GPL v2.0 License**. Vedi file `LICENSE` per dettagli completi.

### 💬 Supporto

- **Documentazione**: File `INSTALLATION-GUIDE.md` per setup dettagliato
- **Sviluppatori**: File `AGENTS.md` per contribuzioni  
- **Issues**: GitHub Issues per bug reports
- **Community**: Forum WordPress per discussioni
- **Email**: support@samira-theme.com per supporto diretto

### 👥 Crediti

- **Design**: Ispirato ai principi di design minimal moderno
- **Typography**: Google Fonts (Montserrat, Playfair Display)
- **Icons**: Custom SVG icons ottimizzati
- **Testing**: Testato su WordPress 5.0-6.3, PHP 7.4-8.2

### 🎯 Roadmap Futura

- [ ] **Gutenberg blocks** personalizzati
- [ ] **WooCommerce** integration  
- [ ] **RTL languages** support
- [ ] **AMP** compatibility
- [ ] **Page builder** compatibility (Elementor, Beaver Builder)
- [ ] **Membership** integration
- [ ] **Event management** features  
- [ ] **Multilingual** support (WPML/Polylang)

---

**Grazie per aver scelto Samira Theme!** 🙏

Se il tema ti piace, considera di lasciare una recensione su WordPress.org o condividerlo con altri creativi che potrebbero beneficiarne.

**Happy blogging!** ✍️
