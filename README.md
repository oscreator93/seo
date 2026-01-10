# Parcel Horse Vendor Portal

A vendor portal for Parcel Horse that allows vendors to:
- Send references
- View submitted references
- Edit profile (change password only)
- Logout

## Setup Instructions

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Environment Configuration**
   - Copy the `.env` file from `parcelhorse-admin` directory to this directory, OR
   - Create a `.env` file with the following content:
   ```
   SERVER=http://localhost:3000
   ```
   Update the `SERVER` URL to match your backend API server.

3. **Directory Structure**
   The vendor portal references assets from the admin portal:
   - CSS/JS libraries from `../parcelhorse-admin/bower_components/`
   - AdminLTE theme from `../parcelhorse-admin/dist/`
   - Images from `../parcelhorse-admin/images/`

   Make sure the `parcelhorse-admin` directory exists at the same level as this directory.

## File Structure

```
parcelhorse-vendor/
├── ApiClient.php          # API client for vendor endpoints
├── bridge.php             # API routing bridge
├── index.php              # Login page
├── dashboard.php          # Main dashboard with tabs
├── header.php            # Common header
├── footer.php            # Common footer
├── logout.php            # Logout handler
├── loadenv.php           # Environment loader
├── composer.json         # PHP dependencies
├── dist/
│   └── js/
│       └── vendor-dashboard.js  # Dashboard JavaScript
└── README.md             # This file
```

## API Endpoints Required

The backend API should implement the following endpoints:

1. **POST /api/vendor/login**
   - Body: `{ username, password, timezone }`
   - Returns: `{ token, vendor_id, name, username }`

2. **GET /api/vendor/is_logged_in**
   - Headers: `Authorization: Bearer {token}`
   - Returns: `{ name, username }`

3. **POST /api/vendor/reference**
   - Headers: `Authorization: Bearer {token}`
   - Body: `{ ref_name, ref_email, ref_phone, city }`
   - Returns: Success/error response

4. **GET /api/vendor/references**
   - Headers: `Authorization: Bearer {token}`
   - Returns: `{ data: [{ ref_name, ref_email, ref_phone, city, submitted_at, lead_recv, lead_conv }] }`

5. **PUT /api/vendor/profile**
   - Headers: `Authorization: Bearer {token}`
   - Body: `{ old_password, new_password }`
   - Returns: Success/error response

## Features

### Dashboard Tabs

1. **Send Reference**
   - Form to submit new references
   - Fields: Name, Email, Phone, City
   - Validation and error handling

2. **View References**
   - Table showing all submitted references
   - Displays: Name, Email, Phone, City, Submitted Date, Lead Received, Lead Converted
   - Refresh button to reload data

3. **Edit Profile**
   - Change password functionality
   - Username and name are read-only
   - Password validation (minimum 6 characters)

## Notes

- The vendor portal uses the same AdminLTE theme as the admin portal
- Session-based authentication
- All API calls go through `bridge.php` for routing
- The portal is designed to be simple and focused on vendor-specific tasks

# seo
