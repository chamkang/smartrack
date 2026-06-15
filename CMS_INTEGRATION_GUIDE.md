# CMS Integration Guide - Smartrack

## Summary

Your main website pages have been successfully integrated with the SQLite-based CMS system. All dynamic content now pulls from the CMS database instead of hardcoded HTML or non-existent database tables.

## Updated Pages

### Pages Successfully Updated to Use CMS:

1. **index.php** - Homepage
   - Hero section pulls from CMS 'index' page, 'hero' section
   - Services section displays from CMS 'index' page, sections starting with 'service_'
   - Gallery section displays from CMS 'index' page, sections starting with 'gallery_'
   - Testimonials section displays from CMS 'index' page, sections starting with 'testimonial_'
   - Contact CTA pulls from CMS 'index' page, 'contact_cta' section

2. **about.php** - About Page
   - Intro section pulls from CMS 'about' page, 'intro' section
   - Statistics cards display from CMS 'about' page, sections starting with 'stat_'

3. **service.php** - Service Details
   - Now uses content IDs (service.php?id=1)
   - Pulls full service content from CMS by ID

4. **contact.php** - Contact Page
   - Intro section pulls from CMS 'contact' page, 'intro' section
   - Contact info pulls from CMS 'contact' page, 'info' section

5. **devices.php** - Devices Page
   - Intro section pulls from CMS 'devices' page, 'intro' section
   - Device items display from CMS 'devices' page, sections starting with 'device_'

## How to Add Content

### Step 1: Login to Admin Panel
- Go to: `http://localhost/smartrack/cms/auth/login.php`
- Email: `admin@smartrack.com`
- Password: `Admin123!`

### Step 2: Add Content via Content Manager
- Go to: `http://localhost/smartrack/cms/admin/content-manager.php`
- Click "Add New Content"
- Fill in the form:
  - **Page Name**: Choose the page (e.g., 'index', 'about', 'contact', 'devices')
  - **Section Name**: Use the naming conventions below
  - **Language**: Select language (en/fr/es/de)
  - **Title**: The heading text
  - **Content**: The body text
  - **Image**: Upload if needed (optional)
  - **Image Alt**: Accessibility text for image

### Step 3: View Content on Website
- Visit the corresponding page on your website
- Content will display automatically from CMS

## CMS Section Naming Conventions

### Index Page Sections:
- `hero` - Main hero banner content
- `services_header` - Services section header
- `service_1`, `service_2`, `service_3` - Individual service items
- `gallery_header` - Gallery section header
- `gallery_1`, `gallery_2`, `gallery_3` - Individual gallery items
- `testimonials_header` - Testimonials section header
- `testimonial_1`, `testimonial_2`, `testimonial_3` - Individual testimonials
- `contact_cta` - Contact call-to-action section

### About Page Sections:
- `intro` - Introduction section with main image
- `stats_header` - Statistics section header
- `stat_1`, `stat_2`, `stat_3` - Individual statistics

### Contact Page Sections:
- `intro` - Contact page introduction
- `info` - Contact information details

### Devices Page Sections:
- `intro` - Device page introduction
- `device_1`, `device_2`, `device_3` - Individual device descriptions

## Features of the Integration

### Automatic Fallbacks
- If no CMS content exists, the website displays default fallback text
- This allows the site to function even before content is added
- Admins see links to add content directly from the website

### Multi-language Support
- Select any language (en/fr/es/de) when adding content
- Website automatically displays content in the selected language
- Falls back to English if translation not available

### Image Management
- Upload images directly from content manager
- Images stored securely in `/cms/uploads/images/`
- Alt text for accessibility
- Automatic cleanup when content is deleted

### Responsive Design
- All integrated content maintains Bootstrap responsive grid
- Works on desktop, tablet, and mobile
- Images scale appropriately

## Database Information

- **Location**: `c:\xampp\htdocs\smartrack\smartrack.db` (SQLite)
- **Tables**:
  - `admins` - Admin user accounts
  - `website_content` - All website content
- **Status**: Check at `http://localhost/smartrack/cms/status.php`

## Admin Panel Features

### Dashboard
- URL: `http://localhost/smartrack/cms/admin/dashboard.php`
- View statistics: Total pages, sections, images
- See recent updates
- Quick access to content manager

### Content Manager
- URL: `http://localhost/smartrack/cms/admin/content-manager.php`
- Create new content
- Edit existing content
- Delete content
- Upload/manage images

### Settings
- Change password (future enhancement)
- Manage additional admins (future enhancement)
- System preferences (future enhancement)

## Next Steps

1. **Add Content**: 
   - Login to admin panel
   - Create content for each page section
   - Upload images for visual appeal

2. **Test Everything**:
   - Visit each page on the website
   - Verify content displays correctly
   - Test multi-language switching

3. **Customize**:
   - Add more pages by creating new PHP files with CMS integration
   - Extend section names as needed
   - Add more admin users

## Troubleshooting

### Content Not Showing
- Check admin panel at `/cms/admin/content-manager.php`
- Verify content is created with correct page and section names
- Check that language matches website language setting

### Database Errors
- Visit `/cms/status.php` to verify database connection
- Ensure write permissions on `/cms/uploads/images/`
- Check SQLite is enabled in PHP (usually enabled by default)

### Images Not Uploading
- Ensure `/cms/uploads/images/` folder exists and is writable
- Maximum file size: 5MB
- Allowed formats: JPG, PNG, WebP

## File Locations

- **CMS Files**: `/cms/`
- **Uploaded Images**: `/cms/uploads/images/`
- **Main Website**: Root directory (index.php, about.php, etc.)
- **Integration File**: `/cms-integration.php`

## Security Notes

- All database queries use prepared statements (SQL injection safe)
- All HTML output is escaped (XSS safe)
- CSRF tokens protect form submissions
- Passwords are bcrypt hashed
- Session cookies are HTTP-only and SameSite protected
- No database credentials stored in code (PDO DSN)

## Support URLs

- Admin Login: `http://localhost/smartrack/cms/auth/login.php`
- Admin Dashboard: `http://localhost/smartrack/cms/admin/dashboard.php`
- Content Manager: `http://localhost/smartrack/cms/admin/content-manager.php`
- System Status: `http://localhost/smartrack/cms/status.php`
- CMS Home: `http://localhost/smartrack/cms/`

---

**Last Updated**: During CMS integration
**Integration Status**: ✅ Complete
**Database Status**: ✅ Initialized
**Admin Panel**: ✅ Functional
