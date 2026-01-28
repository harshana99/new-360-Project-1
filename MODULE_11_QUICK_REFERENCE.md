# MODULE 11 - QUICK REFERENCE GUIDE

## 🚀 Getting Started

### For Users:

**Access Your Profile:**
```
/user/profile
```

**Edit Profile:**
```
/user/profile/edit
```

**Change Password:**
```
/user/profile/change-password
```

**View KYC Status:**
```
/user/kyc-status
```

**View Activity Log:**
```
/user/account-activity
```

---

### For Admins:

**Manage Users:**
```
/admin/user-management
```

**View User Details:**
```
/admin/user-management/{id}
```

**Edit User:**
```
/admin/user-management/{id}/edit
```

**Review KYC:**
```
/admin/user-management/{id}/kyc
```

**Export Users:**
```
/admin/user-management/export/csv
```

---

## 📋 Common Tasks

### User Tasks:

**1. Update Profile Information:**
1. Go to `/user/profile`
2. Click "Edit Profile"
3. Update fields
4. Click "Update Profile"

**2. Change Password:**
1. Go to `/user/profile/change-password`
2. Enter current password
3. Enter new password (must meet requirements)
4. Confirm new password
5. Click "Change Password"

**3. Resubmit KYC:**
1. Go to `/user/kyc-status`
2. Click "Resubmit KYC" (if rejected)
3. Fill form with corrected information
4. Upload new documents
5. Click "Resubmit KYC"

**4. View Activity:**
1. Go to `/user/account-activity`
2. Use filters to narrow results
3. Click "View Details" for metadata

---

### Admin Tasks:

**1. Find a User:**
1. Go to `/admin/user-management`
2. Use search box (name, email, phone)
3. Or use filters (membership, status)
4. Click "Filter"

**2. Edit User Information:**
1. Find user in list
2. Click edit icon (pencil)
3. Update fields
4. Click "Update User"

**3. Suspend User:**
1. Go to user details page
2. Click "Suspend" button
3. Enter reason
4. Click "Suspend Account"

**4. Review KYC:**
1. Go to user details page
2. Click "View KYC Documents"
3. Review documents
4. Click "Approve" or "Reject"
5. Add notes/reason
6. Submit

**5. Export Users:**
1. Go to `/admin/user-management`
2. Apply filters (optional)
3. Click "Export to CSV"
4. File downloads automatically

---

## 🔑 Key Features

### Password Requirements:
- ✅ Minimum 8 characters
- ✅ At least 1 uppercase letter
- ✅ At least 1 lowercase letter
- ✅ At least 1 number
- ✅ At least 1 special character

### KYC Document Requirements:
- ✅ ID Image: JPG, PNG (max 5MB)
- ✅ Address Proof: JPG, PNG, PDF (max 5MB)
- ✅ Clear, readable images
- ✅ All details visible

### User Statuses:
- **Registered** - Just signed up
- **Membership Selected** - Chose membership type
- **KYC Submitted** - Submitted KYC documents
- **Under Review** - Admin reviewing KYC
- **Approved** - KYC approved, full access
- **Rejected** - KYC rejected, can resubmit
- **Suspended** - Account suspended by admin

---

## 🎨 UI Elements

### Status Badges:
- 🟢 **Green** - Approved
- 🔴 **Red** - Rejected/Suspended
- 🟡 **Yellow** - Pending/Under Review
- 🔵 **Blue** - Registered/Info

### Icons Used:
- 👤 **Person** - Profile/User
- 🔒 **Lock** - Security/Password
- 📄 **Document** - KYC/Files
- 🕐 **Clock** - Activity/History
- ✏️ **Pencil** - Edit
- 👁️ **Eye** - View
- ✅ **Check** - Approve/Success
- ❌ **X** - Reject/Error
- ⚠️ **Warning** - Suspend/Alert

---

## 🔒 Security Notes

### For Users:
- ⚠️ Never share your password
- ⚠️ Use a strong, unique password
- ⚠️ Review activity log regularly
- ⚠️ Report suspicious activity
- ⚠️ Keep contact info updated

### For Admins:
- ⚠️ All actions are logged
- ⚠️ Suspensions require reason
- ⚠️ Deletions require email confirmation
- ⚠️ KYC reviews are permanent
- ⚠️ Export data responsibly

---

## 📊 Activity Types

### User Activities:
- **Registration** - Account created
- **Login** - User logged in
- **Logout** - User logged out
- **Profile Update** - Profile information changed
- **Password Changed** - Password updated
- **KYC Submitted** - KYC documents submitted
- **KYC Resubmitted** - KYC documents resubmitted

### Admin Activities:
- **User Updated by Admin** - Admin changed user info
- **Account Suspended** - Admin suspended account
- **Account Activated** - Admin activated account
- **KYC Approved** - Admin approved KYC
- **KYC Rejected** - Admin rejected KYC

---

## 🐛 Troubleshooting

### Common Issues:

**"Cannot edit profile"**
- Check if account is suspended
- Contact admin if issue persists

**"Password too weak"**
- Must meet all 5 requirements
- Use password strength meter

**"KYC upload failed"**
- Check file size (max 5MB)
- Check file format (JPG, PNG, PDF)
- Ensure clear, readable image

**"Cannot find user"**
- Check spelling in search
- Try different filters
- User might be deleted

---

## 📞 Support

### For Users:
- Email: support@360winestate.com
- View activity log for account history
- Contact admin through platform

### For Admins:
- Check activity logs for user history
- Review KYC submission history
- Export data for analysis

---

## ✅ Checklist

### User Onboarding:
- [ ] Register account
- [ ] Select membership type
- [ ] Complete profile
- [ ] Submit KYC documents
- [ ] Wait for approval
- [ ] Access full features

### Admin User Review:
- [ ] View user details
- [ ] Check KYC documents
- [ ] Verify information
- [ ] Approve or reject
- [ ] Add notes
- [ ] Monitor activity

---

**Last Updated:** January 28, 2026  
**Module:** 11 - User Management  
**Version:** 1.0.0
