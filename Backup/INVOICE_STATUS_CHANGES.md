# Invoice Status System Changes

## Overview
The invoice management system has been updated with a new status workflow: **Pending → Complete → Deleted**

## Database Changes
✅ **Migration:** `2026_01_22_083712_update_invoices_status_to_pending_complete_deleted.php`
- Changed `status` ENUM from `(active, inactive)` to `(pending, complete, deleted)`
- Existing records converted: `active` → `pending`, `inactive` → `complete`
- Migration successfully executed

## Status Workflow

### 1. Pending Status
- **When:** All new invoices start with `pending` status
- **Meaning:** Invoice is active and awaiting payment
- **Actions Allowed:**
  - ✅ View invoice
  - ✅ Edit invoice
  - ✅ Delete invoice (only if `paid_amount = 0`)
  - ✅ Apply payments

### 2. Complete Status
- **When:** Automatically set when `remaining_amount = 0` (fully paid)
- **Meaning:** Invoice is fully paid and closed
- **Actions Allowed:**
  - ✅ View invoice
  - ❌ Cannot edit
  - ❌ Cannot delete
  - ❌ Cannot apply additional payments

### 3. Deleted Status
- **When:** Admin deletes a pending invoice with no payments
- **Meaning:** Soft delete - invoice is hidden from normal view
- **Conditions:** Only allowed if `paid_amount = 0`
- **Visibility:** Only admin can view deleted invoices
- **Actions Allowed:**
  - ✅ View invoice
  - ❌ Cannot edit
  - ❌ Cannot restore (currently)

## Code Changes

### InvoiceController.php Updates

#### 1. index() Method
```php
- Default filter changed from 'all' to 'pending'
- Auto-complete logic: if remaining = 0, status changes to 'complete'
- Added status counts: $pendingCount, $completeCount, $deletedCount
- Passes counts to view for filter buttons
```

#### 2. create() Method
```php
- New invoices now created with 'status' => 'pending'
```

#### 3. edit() Method
```php
- Prevents editing if status = 'complete'
- Prevents editing if status = 'deleted'
```

#### 4. update() Method
```php
- Auto-complete logic: if remaining = 0 and status = 'pending', changes to 'complete'
```

#### 5. destroy() Method (UPDATED)
```php
- Soft delete: Changes status to 'deleted' instead of hard delete
- Validation: Only allows delete if paid_amount = 0
- Error message if trying to delete invoice with payments
```

#### 6. toggleStatus() Method (UPDATED)
```php
- Updated for new status values
- Prevents toggling deleted invoices
- Prevents toggling complete invoices
- Prevents toggling invoices with payments applied
```

#### 7. setoffManagement() Method
```php
- Filters for 'pending' status only
- Complete invoices don't appear in payment setoff screen
```

### View Changes: invoices/index.blade.php

#### Status Filter Navigation
```html
- Removed: All Invoices, Active, Inactive buttons
- Added: Pending, Complete, Deleted buttons
- Each button shows count from controller
- Color coding: Primary (Pending), Success (Complete), Danger (Deleted)
```

#### Status Column
```html
- Badge display with icons:
  * Pending: Yellow badge with clock icon
  * Complete: Green badge with check icon
  * Deleted: Red badge with trash icon
```

#### Actions Column
```html
View Button: Always available
Edit Button: 
  - Enabled for pending invoices
  - Disabled (grayed out) for complete invoices
  - Disabled (grayed out) for deleted invoices
Delete Button:
  - Only visible for pending invoices
  - Only visible if paid_amount = 0
  - Shows trash icon
  - Requires confirmation before delete
```

## Business Rules

### Rule 1: New Invoice Creation
- All new invoices start as **pending**
- Default view shows only pending invoices

### Rule 2: Auto-Complete
- When invoice is fully paid (remaining = 0)
- Status automatically changes from pending to **complete**
- Happens on invoice update or when viewing invoice list

### Rule 3: Delete Protection
- Can only delete if `paid_amount = 0` (no payments applied)
- Delete is a **soft delete** (status = 'deleted')
- Deleted invoices hidden from normal view
- Only admin can view deleted invoices

### Rule 4: Edit Protection
- Cannot edit **complete** invoices
- Cannot edit **deleted** invoices
- Can only edit **pending** invoices

### Rule 5: Payment Setoff
- Only **pending** invoices appear in setoff management
- Complete invoices don't need payments (already paid)
- Deleted invoices shouldn't receive payments

## User Interface

### Filter Buttons
1. **Pending (Count)** - Yellow/Primary button
2. **Complete (Count)** - Green button
3. **Deleted (Count)** - Red button (admin only)

### Default View
- Shows only **pending** invoices by default
- User must click "Complete" or "Deleted" to see those invoices

### Status Badges
- **Pending:** 🟡 Yellow badge with clock icon
- **Complete:** 🟢 Green badge with check mark
- **Deleted:** 🔴 Red badge with trash icon

### Action Buttons
- **View (Eye Icon):** Always enabled
- **Edit (Pencil Icon):** Only for pending invoices
- **Delete (Trash Icon):** Only for pending with no payments

## Testing Checklist

### Create Invoice
- [ ] New invoice starts with pending status
- [ ] Pending invoice appears in default view
- [ ] Status badge shows yellow "Pending"

### Apply Payment
- [ ] Can apply payment to pending invoice
- [ ] When fully paid, status changes to complete
- [ ] Complete invoice appears only in "Complete" filter
- [ ] Complete status badge shows green "Complete"

### Delete Invoice
- [ ] Can delete pending invoice with no payments
- [ ] Cannot delete pending invoice with payments (error message)
- [ ] Cannot delete complete invoice (button not shown)
- [ ] Deleted invoice appears only in "Deleted" filter
- [ ] Deleted status badge shows red "Deleted"

### Edit Invoice
- [ ] Can edit pending invoice
- [ ] Cannot edit complete invoice (button disabled)
- [ ] Cannot edit deleted invoice (button disabled)
- [ ] Error message shown if trying to edit via URL

### Filter Navigation
- [ ] Pending button shows correct count
- [ ] Complete button shows correct count
- [ ] Deleted button shows correct count
- [ ] Clicking each button filters correctly
- [ ] Default view shows only pending invoices

## Migration Instructions

### Already Completed
✅ Migration file created
✅ Migration successfully run
✅ Database schema updated
✅ Existing records converted

### No Manual Steps Required
All database changes have been applied automatically.

## Rollback Plan (If Needed)

If you need to rollback this migration:

```bash
php artisan migrate:rollback
```

This will restore the old `(active, inactive)` ENUM values and convert data back:
- `pending` → `active`
- `complete` → `inactive`
- `deleted` → `inactive`

## Summary

This update provides a professional invoice workflow:
1. **Pending** - Active invoices awaiting payment
2. **Complete** - Fully paid invoices (locked)
3. **Deleted** - Soft-deleted invoices (admin only)

The system now properly handles invoice lifecycle with appropriate protections and clear status indicators.
