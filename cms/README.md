# Smartrack CMS - Complete Documentation

## Overview

Smartrack CMS is a professional, SQLite-powered Content Management System designed for the Smartrack Vehicle Tracking website. It provides a secure, user-friendly interface for managing website content, images, and multilingual content without requiring any coding knowledge.

## Features

✅ **Secure Authentication**
- Email-based admin login
- Password hashing with bcrypt
- CSRF protection
- Session management
- Automatic logout on inactivity

✅ **Content Management**
- Create, edit, and delete website content
- Organize content by pages and sections
- Support for multiple languages (English, French, Spanish, German)
- Track content updates with timestamps

✅ **Image Management**
- Secure image upload
- File type validation (JPG, PNG, WebP only)
- Maximum file size: 5MB
- Automatic filename generation to prevent conflicts
- Image preview in edit form

✅ **Dashboard**
- Overview statistics
- Recent activity tracking
- Quick access buttons
- Content performance metrics

## Project Structure

```
smartrack/
├── cms/
│   ├── config/
│   │   └── database.php          # SQLite database configuration
│   ├── database/
│   │   └── setup.php             # Database initialization script
│   ├── auth/
│   │   ├── login.php             # Admin login page
│   │   └── logout.php            # Admin logout handler
│   ├── admin/
│   │   ├── dashboard.php         # Admin dashboard
│   │   └── content-manager.php   # Content CRUD interface
│   ├── includes/
│   │   ├── auth.php              # Authentication functions
│   │   ├── functions.php         # CMS helper functions
│   │   ├── header.php            # Admin layout header
│   │   └── footer.php            # Admin layout footer
│   ├── uploads/
│   │   └── images/               # User-uploaded images
│   └── index.php                 # Setup page & entry point
├── smartrack.db                  # SQLite database file (auto-created)
└── [other website files...]
```

## Installation & Setup

### Step 1: Access the Setup Page

Navigate to: `http://localhost/smartrack/cms/`

### Step 2: Initialize Database

Click "Setup Database" button. This will:
- Create SQLite database file (`smartrack.db`)
- Initialize all required tables
- Create default admin account

### Step 3: Login

Navigate to: `http://localhost/smartrack/cms/auth/login.php`

**Default Credentials:**
- Email: `admin@smartrack.com`
- Password: `Admin123!`

**⚠️ IMPORTANT:** Change the default password immediately after first login!

## Database Schema

### admins table

```sql
CREATE TABLE admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)
```

### website_content table

```sql
CREATE TABLE website_content (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_name TEXT NOT NULL,
    section_name TEXT NOT NULL,
    language_code TEXT DEFAULT 'en',
    title TEXT,
    content TEXT,
    image_path TEXT,
    image_alt TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(page_name, section_name, language_code)
)
```

## API Functions

All functions are located in `/cms/includes/functions.php`

### Content Management

#### `createContent()`
Create a new content section.

```php
$result = createContent(
    'index',              // page_name
    'hero',               // section_name
    'en',                 // language_code (optional)
    'Welcome',            // title (optional)
    'Content here...',    // content (optional)
    '/path/to/image.jpg', // image_path (optional)
    'Alt text'            // image_alt (optional)
);

// Returns: ['success' => bool, 'message' => string, 'id' => int]
```

#### `getContent()`
Get a content item by ID.

```php
$content = getContent(1);
// Returns: array with content data or null
```

#### `updateContent()`
Update existing content.

```php
$result = updateContent(
    1,                    // id
    'New Title',          // title (null to keep current)
    'New Content',        // content (null to keep current)
    '/new/image.jpg',     // image_path (null to keep current)
    'New Alt Text'        // image_alt (null to keep current)
);

// Returns: ['success' => bool, 'message' => string]
```

#### `deleteContent()`
Delete content and associated image.

```php
$result = deleteContent(1);
// Returns: ['success' => bool, 'message' => string]
```

#### `getPageContent()`
Get all content for a specific page and language.

```php
$pageContent = getPageContent('index', 'en');
// Returns: array of content items
```

#### `getContentBySection()`
Get content by page, section, and language.

```php
$content = getContentBySection('index', 'hero', 'en');
// Returns: single content item or null
```

### Image Management

#### `uploadImage()`
Upload and validate image file.

```php
$result = uploadImage('image_field_name');

// Returns: [
//     'success' => bool,
//     'message' => string,
//     'filename' => string,
//     'path' => string
// ]
```

#### `deleteImage()`
Delete image file from filesystem.

```php
$deleted = deleteImage('/cms/uploads/images/filename.jpg');
// Returns: bool
```

### Dashboard Statistics

#### `getTotalPages()`
Get count of unique pages.

```php
$count = getTotalPages();
// Returns: int
```

#### `getTotalSections()`
Get total content sections.

```php
$count = getTotalSections();
// Returns: int
```

#### `getTotalImages()`
Get count of uploaded images.

```php
$count = getTotalImages();
// Returns: int
```

#### `getLastUpdatedContent()`
Get recently updated content.

```php
$items = getLastUpdatedContent(5);
// Returns: array of content items
```

## Authentication Functions

Located in `/cms/includes/auth.php`

### Session Management

#### `initSession()`
Initialize PHP session with security headers.

```php
initSession();
```

#### `isLoggedIn()`
Check if admin is logged in.

```php
if (isLoggedIn()) {
    // User is authenticated
}
```

#### `getCurrentAdmin()`
Get current logged-in admin details.

```php
$admin = getCurrentAdmin();
// Returns: ['id' => int, 'email' => string, 'name' => string] or null
```

#### `loginAdmin()`
Authenticate admin with email and password.

```php
$result = loginAdmin('admin@smartrack.com', 'password');
// Returns: ['success' => bool, 'message' => string]
```

#### `logoutAdmin()`
Terminate admin session.

```php
logoutAdmin();
```

#### `requireLogin()`
Redirect to login if not authenticated (use at top of admin pages).

```php
requireLogin();
```

### CSRF Protection

#### `getCsrfToken()`
Generate or retrieve CSRF token for forms.

```php
<input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">
```

#### `verifyCsrfToken()`
Verify CSRF token in form submission.

```php
if (verifyCsrfToken($_POST['csrf_token'])) {
    // Token is valid
}
```

## Utility Functions

### `escape()`
Escape output for HTML (prevent XSS).

```php
echo escape($userInput);
```

### `redirect()`
Redirect to URL.

```php
redirect('/smartrack/cms/admin/dashboard.php');
```

### `getBasePath()`
Get application base path for redirects.

```php
$path = getBasePath();
// Returns: '/smartrack'
```

## Security Best Practices

1. **Always escape output** to prevent XSS attacks:
   ```php
   echo escape($variable);
   ```

2. **Use prepared statements** for all database queries (all included functions do this automatically)

3. **Verify CSRF tokens** in all POST/PUT/DELETE requests (handled in templates)

4. **Hash passwords** with password_hash() (done automatically in loginAdmin())

5. **Validate file uploads** (uploadImage() does this automatically)

6. **Use HTTPS** in production

7. **Change default credentials** immediately after setup

8. **Implement session timeout** (can be configured in database.php)

## Image Upload Requirements

**Allowed Formats:**
- JPEG/JPG
- PNG
- WebP

**Maximum Size:** 5MB

**Upload Directory:** `/cms/uploads/images/`

**Filename Format:** `{timestamp}_{random_hash}.{extension}`

Files are validated by:
- MIME type checking
- File extension verification
- Size validation
- Directory traversal prevention

## Multilingual Content

The CMS supports content in multiple languages:
- English (en)
- French (fr)
- Spanish (es)
- German (de)

Each content section can have translations for different languages. The `language_code` field determines which version is retrieved.

### Example:

```php
// English version
$enContent = getContentBySection('index', 'hero', 'en');

// French version
$frContent = getContentBySection('index', 'hero', 'fr');
```

## Common Tasks

### How to Edit a Page Title?

1. Login to admin panel
2. Go to Content Manager
3. Find the content in the list
4. Click Edit button
5. Modify the Title field
6. Click Update button

### How to Add a New Image?

1. In Content Manager, edit content section
2. Under "Image" section, select file
3. Add descriptive Alt Text (for accessibility)
4. Click Create/Update button

### How to Create Content in Another Language?

1. In Content Manager form
2. Select desired Language from dropdown
3. Fill in Page Name and Section Name
4. Enter content in the selected language
5. Click Create button

### How to Delete Content?

1. In Content Manager content list
2. Find the content
3. Click Delete (trash icon)
4. Confirm deletion
5. Content and associated image will be removed

## Troubleshooting

### Database connection error

**Problem:** "Database Connection Error"

**Solution:**
1. Check if SQLite is enabled in PHP (`php -m | grep sqlite`)
2. Verify write permissions on `/smartrack/` directory
3. Run setup script again at `/cms/database/setup.php`

### Cannot upload images

**Problem:** "Failed to move uploaded file"

**Solution:**
1. Check `/cms/uploads/images/` directory permissions
2. Ensure PHP has write access: `chmod 755 /cms/uploads/images/`
3. Verify upload_max_filesize in php.ini is at least 5MB

### Login not working

**Problem:** "Invalid email or password"

**Solution:**
1. Verify default credentials are correct
2. Check if database was set up properly
3. Clear browser cookies and try again
4. Check `/cms/database/setup.php` to reset admin account

### Session expired

**Problem:** Redirected to login page unexpectedly

**Solution:**
1. This is normal after browser restart
2. Login again with your credentials
3. Check PHP session.gc_maxlifetime setting

## Extending the CMS

### Add a New Language

Edit `/cms/admin/content-manager.php` line with language options:

```php
<select class="form-control" name="language_code">
    <option value="en">English</option>
    <option value="fr">French</option>
    <option value="es">Spanish</option>
    <option value="de">German</option>
    <option value="it">Italian</option> <!-- Add new language -->
</select>
```

### Add More Content Fields

1. Add new column to `website_content` table in `setup.php`
2. Update form in `content-manager.php`
3. Update functions in `functions.php` to handle new field

### Customize Dashboard Cards

Edit `/cms/admin/dashboard.php` to add/modify dashboard widgets.

## Performance Tips

1. **Database optimization:** SQLite handles most websites well, consider upgrading to MySQL for high traffic
2. **Image optimization:** Resize images before uploading
3. **Caching:** Implement browser caching for static assets
4. **CDN:** Use CDN for image delivery in production

## Backup & Recovery

### Backup Database

```bash
cp /path/to/smartrack.db /path/to/backup/smartrack.db
```

### Backup Uploads

```bash
cp -r /path/to/smartrack/cms/uploads /path/to/backup/
```

## License & Support

Smartrack CMS is built for the Smartrack Vehicle Tracking website.

## Version History

- **v1.0.0** (2024) - Initial release
  - SQLite database
  - Authentication & CSRF protection
  - Content management
  - Image uploads
  - Multilingual support
  - Admin dashboard

---

**Last Updated:** June 2024

For issues or questions, refer to the code comments for detailed explanations of each function.
