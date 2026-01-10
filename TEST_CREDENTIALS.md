# Test Credentials for Vendor Portal

## For UI/UX Testing (Test Mode)

The vendor portal is currently in **TEST MODE** which allows you to test the UI without needing the backend API.

### Login Credentials:
- **Username:** `testvendor`
- **Password:** `test123`

### What Works in Test Mode:
✅ Login with test credentials  
✅ View dashboard  
✅ Send Reference (mock submission)  
✅ View References (shows 3 sample references)  
✅ Edit Profile / Change Password (mock update)  

### How to Disable Test Mode:
When you're ready to connect to the real backend API:

1. Open `bridge.php`
2. Find `$TEST_MODE = true;`
3. Change it to `$TEST_MODE = false;`

4. Open `header.php`
5. Find `$TEST_MODE = true;`
6. Change it to `$TEST_MODE = false;`

### Test Data:
The test mode includes 3 sample references:
1. John Doe - New York (Lead Received: Yes, Converted: No)
2. Jane Smith - Los Angeles (Lead Received: Yes, Converted: Yes)
3. Bob Johnson - Chicago (Lead Received: No, Converted: No)

---

**Note:** All operations in test mode are simulated and don't actually save data to a database.

