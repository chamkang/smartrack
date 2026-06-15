# Smartrack CMS - Quick Reference Guide

## 🔗 Important URLs

| Purpose | URL |
|---------|-----|
| CMS Home/Status | `http://localhost/smartrack/cms/` |
| System Status | `http://localhost/smartrack/cms/status.php` |
| Admin Login | `http://localhost/smartrack/cms/auth/login.php` |
| Admin Dashboard | `http://localhost/smartrack/cms/admin/dashboard.php` |
| Content Manager | `http://localhost/smartrack/cms/admin/content-manager.php` |
| Documentation | `http://localhost/smartrack/cms/README.md` |

## 👤 Default Login

```
Email:    admin@smartrack.com
Password: Admin123!
```

## 📂 Important Files

| File | Purpose |
|------|---------|
| `cms/config/database.php` | Database connection & configuration |
| `cms/includes/auth.php` | Authentication & session functions |
| `cms/includes/functions.php` | CMS core functions |
| `cms/database/setup.php` | Database initialization (one-time) |
| `smartrack.db` | SQLite database file |

## 🎯 Common Tasks

### Login to Admin Panel
1. Visit `http://localhost/smartrack/cms/auth/login.php`
2. Enter email: `admin@smartrack.com`
3. Enter password: `Admin123!`
4. Click "Sign In"

### Create New Content
1. Go to Content Manager
2. Fill in **Page Name** (e.g., "index", "about")
3. Fill in **Section Name** (e.g., "hero", "features")
4. Select **Language** (English, French, etc.)
5. Add **Title** and **Content**
6. Upload **Image** (optional)
7. Click **Create**

### Edit Existing Content
1. Go to Content Manager
2. Find content in the list
3. Click **Edit** (pencil icon)
4. Make changes
5. Click **Update**

### Delete Content
1. Go to Content Manager
2. Click **Delete** (trash icon)
3. Confirm deletion
4. Image is automatically deleted

### Upload Images
- Formats allowed: JPG, PNG, WebP
- Maximum size: 5MB
- Alt text is required for accessibility
- Files are auto-renamed with unique names

### Manage Multilingual Content
1. Create content in English (en)
2. Create same content with French (fr) - new entry
3. Create Spanish (es) - new entry
4. Create German (de) - new entry

Each language is a separate database entry with same page and section names.

## 💾 Database Functions

### Create Content
```php
createContent(
    'index',           // page_name
    'hero',            // section_name
    'en',              // language
    'Title',           // title
    'Content...',      // content
    '/path/image.jpg', // image_path
    'Alt text'         // image_alt
);
```

### Get Content
```php
// By ID
getContent(1);

// All content for a page
getPageContent('index', 'en');

// Specific section
getContentBySection('index', 'hero', 'en');
```

### Update Content
```php
updateContent(
    1,                 // id
    'New Title',       // title (or null to keep)
    'New Content',     // content (or null to keep)
    '/new/image.jpg',  // image_path (or null)
    'New alt text'     // image_alt (or null)
);
```

### Delete Content
```php
deleteContent(1); // Deletes content AND image
```

### Get Statistics
```php
getTotalPages();           // Count of unique pages
getTotalSections();        // Count of content sections
getTotalImages();          // Count of images
getLastUpdatedContent(5);  // Last 5 updated items
```

## 🔐 Security

### CSRF Protection
All forms include CSRF tokens:
```php
<input type="hidden" name="csrf_token" 
       value="<?php echo getCsrfToken(); ?>">
```

### Password Security
- Passwords hashed with bcrypt
- Never store plain text passwords
- Change default password immediately

### Image Upload Security
- File type validation (MIME check)
- File extension whitelist
- File size limits (5MB max)
- Unique filename generation
- Directory traversal prevention

## 🛠️ Configuration

### Database Path
Located in `/cms/config/database.php`:
```php
define('DB_PATH', __DIR__ . '/../../smartrack.db');
```

### Upload Directory
```php
'/cms/uploads/images/'
```

### Allowed Image Types
- image/jpeg
- image/png
- image/webp

### Max Upload Size
5MB (5242880 bytes)

## 🚨 Troubleshooting

### Forgot Password?
Currently no password reset. To reset:
1. Delete database file: `smartrack.db`
2. Run `/cms/database/setup.php`
3. Login with default credentials

### Database File Location
`/smartrack/smartrack.db` - SQLite database

### Check System Status
Visit `/cms/status.php` to verify:
- Database connection
- Tables created
- Admin accounts
- PHP version

### Clear Session
- Clear browser cookies
- Close and reopen browser
- Login again

## 📊 Database Tables

### admins
- id, name, email, password_hash, created_at

### website_content
- id, page_name, section_name, language_code, title, content, image_path, image_alt, updated_at

## 🔄 Workflow Example

```
1. Create "index" page content
   ├─ hero section (English)
   ├─ hero section (French)
   ├─ features section (English)
   └─ features section (French)

2. Create "about" page content
   ├─ intro section (English)
   └─ team section (English)

3. Display in website
   use getPageContent('index', 'en');
```

## 📱 Dashboard Features

**Statistics Cards:**
- Total Pages - Count of unique page names
- Content Sections - Total content entries
- Uploaded Images - Images with file paths
- Recent Updates - Shows last 5 modifications

**Quick Actions:**
- Create Content - Fast link to create new
- View All Content - Link to content manager

## 🎨 Customization

### Add New Language
Edit `/cms/admin/content-manager.php` - add to select:
```html
<option value="it">Italian</option>
```

### Change Dashboard Colors
Edit `/cms/includes/header.php` - modify CSS variables

### Add More Statistics
Edit `/cms/admin/dashboard.php` - add new cards

## 📈 Performance Tips

1. **Database**
   - Use indexes for frequent queries
   - Archive old content
   - Regular backups

2. **Images**
   - Compress before upload
   - Use appropriate formats
   - Implement lazy loading

3. **Caching**
   - Enable browser caching
   - Consider Redis for sessions
   - Use CDN for images

## 🔗 Integration with Website

In your website pages:
```php
<?php
require_once __DIR__ . '/cms/config/database.php';
require_once __DIR__ . '/cms/includes/functions.php';

// Get content
$heroContent = getContentBySection('index', 'hero', 'en');

// Display
echo escape($heroContent['title']);
echo escape($heroContent['content']);

if ($heroContent['image_path']) {
    echo '<img src="' . escape($heroContent['image_path']) . '" 
          alt="' . escape($heroContent['image_alt']) . '">';
}
?>
```

## 📞 Support Files

- **README.md** - Complete documentation
- **SETUP_COMPLETE.md** - Setup verification
- **status.php** - System status dashboard

---

**Quick Start:** Login → Dashboard → Content Manager → Create Content

**Need Help?** Check README.md for detailed documentation.
