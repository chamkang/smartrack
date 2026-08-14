# Deploying SMARTRACK to Namecheap

The public site and the admin panel are **one application**, not two. The admin
lives in the `admin/` folder of the same upload and is protected by a login, so
there is a single deployment and a single database.

| Part | Where it ends up | URL |
|------|------------------|-----|
| Client website | `public_html/` | `https://yourdomain.com` |
| Admin panel | `public_html/admin/` | `https://yourdomain.com/admin/login.php` |
| Database | **above** `public_html/` (preferred) | never reachable over HTTP |

---

## 1. Buy hosting and point the domain

1. Namecheap → **Shared Hosting** (Stellar is enough) and a domain.
2. If the domain is registered elsewhere, set its nameservers to Namecheap's.
3. Wait for cPanel access details by email.

## 2. Set the PHP version

cPanel → **Select PHP Version** → choose **8.1 or 8.2**.
Enable these extensions (usually on by default): `pdo`, `pdo_sqlite`, `mbstring`,
`fileinfo`, `gd`.

> The site uses `match()` and typed properties, so PHP 8.0+ is required.

## 3. Upload the site

cPanel → **File Manager** → open `public_html`, then upload the project.
Easiest route: zip the project locally, upload the zip, then **Extract**.

**Upload these:**

```
admin/  assets/  includes/  uploads/
*.php   (index, about, contact, blog, blog-post, career, devices,
         service, SmartFleet, SmartSolution, sitemap, config,
         contact-submit, contact-unified, quote-submit, apply-submit)
.htaccess   robots.txt
```

**Do NOT upload** (not used at runtime, and some are unsafe to publish):

| Skip | Why |
|------|-----|
| `db/` | contains SQL dumps **including the admin password hash** |
| `_img_originals/` | 33 MB of local image backups |
| `.git/` | 61 MB of history; publishing it exposes your source |
| `setup.php` | can recreate the default admin account |
| `datas.php`, `cms/`, `backend/`, `forms/`, `cms-integration.php` | legacy, not referenced by any page |
| `vercel.json`, `api/`, `package-lock.json` | Vercel-only |

> `.htaccess` starts with a dot, so tick **Show Hidden Files** in File Manager
> (Settings) or it will look like it did not upload.

## 4. Put the database in place

`smartrack.db` is deliberately **not** in Git — it holds your admin password
hash and every contact submission. Upload it by hand.

**Preferred (safer).** Put it *outside* the public folder:

```
/home/youruser/
├── smartrack-data/
│   └── smartrack.db        ← upload here
└── public_html/            ← the website
```

`config.php` looks for `../smartrack-data/smartrack.db` first and uses it
automatically. Nothing to configure.

**Fallback.** If your plan does not allow files above `public_html`, upload
`smartrack.db` into `public_html/`. `.htaccess` blocks direct download — but
only while `.htaccess` is honoured, which is why outside is preferred.

## 5. Set permissions

In File Manager, right-click → **Change Permissions**:

| Path | Permission |
|------|-----------|
| `smartrack.db` | **644** |
| the folder holding it (`smartrack-data/` or `public_html/`) | **755** |
| `uploads/` and every folder inside it | **755** |

SQLite must write a temporary `-wal`/`-shm` file **next to the database**, so the
folder must be writable, not just the file. If saving in the admin fails, this is
almost always the cause.

## 6. Turn on HTTPS

cPanel → **SSL/TLS Status** → *Run AutoSSL* (free Let's Encrypt).
Then force HTTPS by adding this to the **top** of `.htaccess`:

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

Without HTTPS your admin password travels in plain text.

## 7. Secure the admin

1. Visit `https://yourdomain.com/admin/login.php`
2. Sign in with `admin` / `admin123`
3. Go straight to **My Account** and set a real password
4. **Delete `setup.php`** from the server if you uploaded it

## 8. Check it worked

- [ ] Home page loads over `https://`
- [ ] Language switch EN ⇄ FR works
- [ ] Logo shows black **SMAR**, split **T**, red **RACK**
- [ ] Blog and a blog post open
- [ ] Contact form submits and appears in admin → Messages
- [ ] Admin login works with the **new** password
- [ ] Edit the homepage carousel, save, and see it change on the site
      *(this proves the database is writable — the most common failure)*
- [ ] `https://yourdomain.com/smartrack.db` returns **403/404**, not a download
- [ ] `https://yourdomain.com/sitemap.xml` returns XML

## 9. After going live

Submit the site to **Google Search Console** and add `sitemap.xml`.

---

## Keeping content in sync

The database is not in Git, so **pushing code never moves content**.

- Code (layout, styling, wording in PHP files) → deploys when you upload files.
- Content (translations, blog posts, services, carousel) → lives in the database.

Once live, **make content edits in the live admin panel**. Editing locally will
not appear on the site, and re-uploading your local `smartrack.db` would
overwrite everything customers submitted.

## If something breaks

| Symptom | Cause |
|---------|-------|
| Blank white page | PHP error. cPanel → **Errors**, or set PHP to 8.1+ |
| "unable to open database file" | Wrong path or permissions — see step 5 |
| Admin reads but cannot save | The **folder** holding the database is not writable |
| Images 404 | `uploads/` not uploaded, or wrong permissions |
| CSS missing | `.htaccess` or `assets/` not uploaded (check hidden files) |
