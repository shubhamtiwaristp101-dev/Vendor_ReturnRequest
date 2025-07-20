# Vendor_ReturnRequest

A Magento 2 module that enables customers to submit product return requests for completed orders, with full admin management, approval workflow, email notifications, and API support.

---

## 🖥️ System Requirements

| Component   | Version     |
|-------------|-------------|
| Magento     | 2.4.7       |
| Apache      | 2.4.58      |
| PHP         | 8.3         |
| MySQL       | 8.0.35      |

---

## 🚀 Features

- Customers can create return requests from **My Orders**
- Auto-filled **Order ID**, Reason (dropdown), Description, optional image upload
- Show list of return requests under **My Account > My Returns**
- Admin Grid under **Sales > Return Requests** with:
    - Filtering, sorting
    - Inline status update
    - Mass actions: Approve / Reject
- Email notification to admin on new request
- Status history tracking (approval flow)
- Custom DB tables
- ACL, Repository, DI, UI Components
- REST and GraphQL API Support

---

## 🎯 Functionality

### Customer Side

- Return request form accessible from completed orders
- Fields in form:
    - Order ID (auto-filled)
    - Reason (select dropdown)
    - Description (textarea)
    - Image upload (optional)
    - Hidden date of request, customer ID
- List of return requests: **My Account > My Returns**

### Admin Panel

- Admin Grid at **Sales > Return Requests**
- Columns:
    - Return ID
    - Order ID (clickable → admin order view)
    - Customer Email
    - Reason
    - Status (New / Approved / Rejected)
    - Created At
- Admin Features:
    - Inline status editing
    - Mass actions (Approve / Reject)
    - Filtering, sorting
    - Return status history tracking

---

## 🧩 Technical Implementation

- Data stored in custom table: `vendor_return_request`
- Return status history stored in: `vendor_return_request_status_history`
- Full Magento 2 standards:
    - Dependency Injection (DI)
    - Repositories
    - Setup scripts for DB
    - ACL & admin menu
    - ViewModel for frontend
- Event Dispatch: `vendor_returnrequest_after_save`
- Unit tested controller (Customer Save)

---

## 🔔 Email Notifications

- Email sent to admin when a new return request is submitted.
- Includes request details and uploaded image (if any)
- Email template customizable from Admin Panel
- Template identifier: `return_request_email_template_new`

---

## 🔁 Approval Flow

- Admin updates status to **Approved** or **Rejected**
- Status change is logged with:
    - Previous status
    - New status
    - Changed at datetime
    - Admin user (if available)
- History table: `vendor_return_request_status_history`
- Easily extendable to show history log in a separate grid or tab

---

## 🔗 API Support

### ✅ REST API

- Submit a return request
- Fetch customer return requests
- Admin: Get all return requests, update status

### ✅ GraphQL API

- Mutation to submit return request
- Query to list return requests for customer

---

## 🧪 Unit Testing

Unit test for `Vendor\ReturnRequest\Controller\Customer\Save`:

```bash
vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/Vendor/ReturnRequest/Test/Unit/Controller/Customer/SaveTest.php
