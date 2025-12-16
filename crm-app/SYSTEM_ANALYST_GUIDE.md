# System Analyst Guide - CRM Application PT. Smart ISP

## 📌 Document Purpose

Dokumen ini dibuat khusus untuk **System Analyst** agar dapat memahami:
- Business flow aplikasi CRM
- Use cases dan scenarios
- Business logic dan rules
- Integration points
- Data flow diagrams
- Requirements traceability

---

## 🏢 Business Context

### Company Profile
**PT. Smart** adalah perusahaan Internet Service Provider (ISP) yang melayani customer di Indonesia. Saat ini operasional masih semi-konvensional dengan banyak pekerjaan manual yang belum paperless.

### Problem Statement
Divisi sales melakukan rekap data secara manual untuk:
- Calon customer (leads)
- Customer yang sudah berlangganan
- Produk layanan internet
- Penjualan dan project

### Solution
Aplikasi CRM berbasis web untuk mengotomasi dan mendigitalisasi proses sales, dari lead management hingga customer berlangganan.

---

## 🎯 Business Objectives

### Primary Goals:
1. **Digitalisasi Lead Management** - Semua calon customer tercatat dalam sistem
2. **Streamline Sales Process** - Otomasi workflow dari lead → project → customer
3. **Manager Approval Workflow** - Kontrol kualitas dengan approval manager
4. **Customer Service Tracking** - Monitor layanan yang aktif per customer
5. **Paperless Operations** - Eliminasi pencatatan manual

### Success Metrics:
- Reduce lead data entry time by 70%
- 100% project approval tracked in system
- Real-time visibility of active subscriptions
- Zero manual spreadsheet for sales data

---

## 👥 User Roles & Personas

### 1. Sales Person
**Tanggung Jawab:**
- Input lead baru dari berbagai source
- Follow up lead dan update status
- Buat project dari qualified leads
- Buat customer setelah project approved
- Assign services ke customer

**Needs:**
- Quick lead entry form
- Easy status update
- Clear project creation flow
- Multiple service assignment

### 2. Sales Manager
**Tanggung Jawab:**
- Review project yang diajukan sales
- Approve atau reject project
- Monitor team performance
- Assign leads ke sales person

**Needs:**
- Dashboard for pending approvals
- Quick approve/reject buttons
- Ability to add notes
- Team activity visibility

### 3. Admin (System Administrator)
**Tanggung Jawab:**
- Manage master data products
- User management
- System configuration
- Data maintenance

**Needs:**
- Product CRUD operations
- User management interface
- Bulk operations
- Data export/import

---

## 📊 Use Cases

### UC-01: Manage Leads
**Actor:** Sales Person, Sales Manager

**Preconditions:**
- User is logged in
- User has sales role

**Main Flow:**
1. User access Leads menu
2. System displays list of leads
3. User clicks "Create Lead"
4. User fills lead form (name, phone, email, address, source)
5. User submits form
6. System validates and saves lead
7. System displays success message
8. System redirects to leads list with new lead visible

**Alternative Flow 3a: Edit Lead**
3a1. User clicks "Edit" on existing lead
3a2. System displays edit form with current data
3a3. User updates data
3a4. System validates and updates lead
3a5. Back to main flow step 7

**Alternative Flow 6a: Validation Failed**
6a1. System displays error messages
6a2. User corrects errors
6a3. Back to main flow step 5

**Postconditions:**
- New lead saved in database
- Lead visible in leads list
- Lead can be assigned to sales person
- Lead can be converted to project

**Business Rules:**
- BR-01: Name is mandatory
- BR-02: At least phone or email must be provided
- BR-03: Status defaults to "new"
- BR-04: Source can be: website, referral, ads, cold call, other

---

### UC-02: Create Project from Lead
**Actor:** Sales Person

**Preconditions:**
- User is logged in
- At least one lead exists in system
- At least one product exists in system

**Main Flow:**
1. User access Projects menu
2. System displays list of projects
3. User clicks "Create Project"
4. System displays project form with:
   - Dropdown of available leads
   - Dropdown of available products
   - Input for estimated fee
5. User selects lead and product
6. User enters estimated fee
7. User submits form
8. System validates data
9. System creates project with status "pending"
10. System displays success message
11. System redirects to projects list

**Alternative Flow 8a: Lead already has active project**
8a1. System shows error "Lead already has active project"
8a2. User selects different lead
8a3. Back to main flow step 7

**Postconditions:**
- New project created with status "pending"
- Project visible in projects list
- Project waiting for manager approval
- Notification sent to manager (future enhancement)

**Business Rules:**
- BR-05: One lead can only have one active project at a time
- BR-06: Estimated fee must be positive number
- BR-07: Status defaults to "pending"
- BR-08: Manager approval defaults to false

---

### UC-03: Approve/Reject Project
**Actor:** Sales Manager

**Preconditions:**
- User is logged in with manager role
- Project exists with status "pending"

**Main Flow (Approve):**
1. Manager access Projects menu
2. System displays list of projects, highlighting pending ones
3. Manager clicks "Approve" button on a project
4. System displays confirmation modal with approval notes field
5. Manager enters notes (optional)
6. Manager confirms approval
7. System updates project:
   - Set manager_approval = true
   - Set status = "approved"
   - Set manager_id = current user
   - Save approval_notes
8. System displays success message
9. System refreshes projects list
10. Approved project can now be converted to customer

**Alternative Flow (Reject):**
3a. Manager clicks "Reject" button
4a. System displays confirmation modal with rejection reason field
5a. Manager enters reason (mandatory for rejection)
6a. Manager confirms rejection
7a. System updates project:
   - Set manager_approval = false
   - Set status = "rejected"
   - Set manager_id = current user
   - Save approval_notes with reason
8a. System displays success message
9a. Sales person can see rejection reason and fix issues

**Postconditions (Approved):**
- Project status changed to "approved"
- Manager approval recorded
- Sales person can create customer
- Lead can be marked as "qualified"

**Postconditions (Rejected):**
- Project status changed to "rejected"
- Rejection reason recorded
- Sales person notified (future)
- Project cannot proceed until issues resolved

**Business Rules:**
- BR-09: Only managers can approve/reject projects
- BR-10: Approval cannot be undone (audit trail)
- BR-11: Rejection must have notes explaining reason
- BR-12: Approved project enables customer creation

---

### UC-04: Create Customer with Services
**Actor:** Sales Person

**Preconditions:**
- User is logged in
- Approved project exists (recommended)
- At least one product exists

**Main Flow:**
1. User access Customers menu
2. User clicks "Add Customer"
3. System displays customer form with:
   - Basic info fields (name, phone, email, address)
   - Optional lead selection dropdown
   - Multiple service selection checkboxes
   - Date fields for each selected service
   - Monthly fee field for each service
4. User fills basic customer info
5. User selects lead (optional, to link from project)
6. User checks multiple services (e.g., Home 10Mbps + Home 20Mbps)
7. For each service, user fills:
   - Start date
   - End date
   - Monthly fee (defaults to product price, can be customized)
8. User submits form
9. System validates all data
10. System creates customer record
11. System creates customer_services records for each selected service
12. If linked to project, system updates project status to "completed"
13. System displays success message
14. System redirects to customers list

**Alternative Flow 9a: Validation Failed**
9a1. System shows validation errors
9a2. User corrects errors
9a3. Back to main flow step 8

**Alternative Flow 11a: Service Creation Failed**
11a1. System rolls back customer creation
11a2. System displays error message
11a3. User tries again

**Postconditions:**
- Customer record created
- Multiple service subscriptions created
- Customer visible in customers list
- Services tracked with start/end dates
- Monthly fees recorded
- Project marked as completed if linked

**Business Rules:**
- BR-13: Customer name is mandatory
- BR-14: At least one service must be selected
- BR-15: Start date must be before end date
- BR-16: Monthly fee can differ from product price (discounts, promos)
- BR-17: One customer can have multiple active services
- BR-18: Each service tracked independently

---

### UC-05: View Customer Services
**Actor:** Sales Person, Sales Manager

**Preconditions:**
- User is logged in
- Customer exists with services

**Main Flow:**
1. User access Customers menu
2. System displays list of customers with basic info
3. User clicks on a customer or "View Details"
4. System displays customer detail page showing:
   - Customer basic information
   - List of all services with:
     - Product name
     - Start date
     - End date
     - Monthly fee
     - Status (active/suspended/terminated)
   - Total monthly revenue from this customer
5. User can view all information

**Alternative Flow 3a: Edit Customer**
3a1. User clicks "Edit Customer"
3a2. System displays edit form
3a3. User updates information
3a4. System validates and updates
3a5. Back to main flow step 4

**Alternative Flow 5a: Manage Service**
5a1. User clicks "Edit Service" on a specific service
5a2. System displays service edit form
5a3. User updates start/end date or monthly fee
5a4. System validates and updates service
5a5. Back to main flow step 4

**Postconditions:**
- User has complete view of customer subscriptions
- User can track service lifecycle
- User can calculate customer lifetime value

**Business Rules:**
- BR-19: Service status changes based on dates
- BR-20: Terminated services kept for history
- BR-21: Customer can have mix of active and terminated services

---

## 🔄 Business Process Flows

### Flow 1: Lead to Customer (Happy Path)

```
[Start] 
   ↓
[Sales Person Input New Lead]
   ↓
[Lead Status: NEW]
   ↓
[Sales Follow Up Lead]
   ↓
[Update Status: CONTACTED]
   ↓
[Lead Qualified?] ─NO→ [Update Status: LOST] → [End]
   ↓ YES
[Update Status: QUALIFIED]
   ↓
[Sales Create Project]
   ↓
[Select Lead + Product]
   ↓
[Enter Estimated Fee]
   ↓
[Submit Project]
   ↓
[Project Status: PENDING]
   ↓
[Manager Review Project]
   ↓
[Manager Approve?] ─NO→ [Status: REJECTED] → [Sales Fix Issues] → [Resubmit]
   ↓ YES
[Manager Approve + Notes]
   ↓
[Project Status: APPROVED]
   ↓
[Sales Create Customer]
   ↓
[Link to Lead (Optional)]
   ↓
[Select Multiple Services]
   ↓
[Set Start/End Dates + Fees]
   ↓
[Submit Customer]
   ↓
[Customer Created]
   ↓
[Services Activated]
   ↓
[Project Status: COMPLETED]
   ↓
[End]
```

### Flow 2: Multiple Service Assignment

```
[Customer Created]
   ↓
[Select Service 1: Home 10Mbps]
   ├─ Start: 2025-01-15
   ├─ End: 2026-01-15
   └─ Fee: 250,000
   ↓
[Select Service 2: Home 20Mbps]
   ├─ Start: 2025-01-15
   ├─ End: 2026-01-15
   └─ Fee: 350,000
   ↓
[Select Service 3: Business 50Mbps]
   ├─ Start: 2025-02-01
   ├─ End: 2026-02-01
   └─ Fee: 1,500,000
   ↓
[Total Monthly Revenue: 2,100,000]
   ↓
[All Services Tracked Independently]
```

---

## 📋 Business Rules Catalog

### Lead Management Rules
- **BR-01**: Lead name is mandatory
- **BR-02**: Lead must have at least phone or email
- **BR-03**: Lead status defaults to "new" on creation
- **BR-04**: Valid sources: website, referral, ads, cold call, other

### Project Management Rules
- **BR-05**: One lead can only have one active/pending project
- **BR-06**: Estimated fee must be positive number
- **BR-07**: Project status defaults to "pending" on creation
- **BR-08**: Manager approval defaults to false
- **BR-09**: Only managers can approve/reject projects
- **BR-10**: Approval decisions cannot be undone (audit trail)
- **BR-11**: Rejection must include notes explaining reason
- **BR-12**: Only approved projects can be converted to customers

### Customer Management Rules
- **BR-13**: Customer name is mandatory
- **BR-14**: Customer must have at least one service
- **BR-15**: Service start date must be before end date
- **BR-16**: Monthly fee can differ from product price (for discounts/promos)
- **BR-17**: One customer can subscribe to multiple services
- **BR-18**: Each service has independent lifecycle
- **BR-19**: Service status automatically changes based on dates
- **BR-20**: Terminated services kept for historical reporting
- **BR-21**: Customer can have mix of active/terminated services

### Product Management Rules
- **BR-22**: Product code must be unique
- **BR-23**: Product name is mandatory
- **BR-24**: Monthly price cannot be negative
- **BR-25**: Deleting product sets NULL in projects/services (soft delete)

---

## 🔗 Integration Points

### Current Integrations
**None** - This is a standalone application

### Recommended Future Integrations

#### 1. Email Service (High Priority)
- **Purpose**: Automated notifications
- **Use Cases**:
  - Send email when project needs approval
  - Notify sales when project approved/rejected
  - Send welcome email to new customers
  - Service renewal reminders
- **Suggested Provider**: SendGrid, AWS SES, Mailgun

#### 2. Billing System (High Priority)
- **Purpose**: Generate invoices for customer services
- **Integration Points**:
  - customer_services table → Invoice generation
  - Monthly recurring billing
  - Payment status tracking
- **Data Flow**: CRM → Billing System (monthly_fee, customer info)

#### 3. Network Monitoring (Medium Priority)
- **Purpose**: Monitor customer connection status
- **Integration Points**:
  - customer_services → Network device status
  - Real-time connection monitoring
  - Bandwidth usage tracking
- **Data Flow**: Bidirectional (CRM ↔ Network Monitoring)

#### 4. Ticketing System (Medium Priority)
- **Purpose**: Customer support and issue tracking
- **Integration Points**:
  - customers → Support tickets
  - Service quality issues
- **Data Flow**: CRM → Ticketing System (customer info)

#### 5. Reporting/BI Tool (Low Priority)
- **Purpose**: Advanced analytics and dashboards
- **Integration Points**:
  - All tables → Data warehouse
  - Sales pipeline analytics
  - Customer lifetime value
  - Churn prediction
- **Suggested Tools**: Metabase, Tableau, Power BI

---

## 📊 Data Flow Diagrams

### Level 0: Context Diagram
```
External Entities:
- Sales Person
- Sales Manager
- Admin

System: CRM Application PT. Smart

Data Flows:
- Sales Person → [Lead Data] → CRM
- Sales Person → [Project Data] → CRM
- Sales Manager → [Approval Decision] → CRM
- CRM → [Reports] → Sales Manager
- Admin → [Product Data] → CRM
```

### Level 1: Main Processes
```
Process 1.0: Lead Management
- Input: Lead information from sales
- Output: Qualified leads list
- Data Store: leads table

Process 2.0: Project Processing
- Input: Lead + Product selection
- Output: Approved projects
- Data Store: projects table

Process 3.0: Approval Workflow
- Input: Pending projects
- Output: Approval decisions
- Data Store: projects table (manager_approval)

Process 4.0: Customer Management
- Input: Approved projects
- Output: Active customers with services
- Data Store: customers, customer_services tables

Process 5.0: Product Management
- Input: Product information from admin
- Output: Available products catalog
- Data Store: products table
```

---

## 🧪 Test Scenarios

### Scenario 1: Complete Sales Cycle
**Objective**: Test full flow from lead to customer

**Steps:**
1. Login as sales person
2. Create new lead "PT. ABC Indonesia"
3. Verify lead appears in list with status "new"
4. Edit lead to change status to "contacted"
5. Edit lead again to change status to "qualified"
6. Create project from this lead
7. Select product "Home 20 Mbps"
8. Enter estimated fee 350,000
9. Submit project
10. Verify project status is "pending"
11. Logout
12. Login as manager
13. View pending projects
14. Click approve on PT. ABC project
15. Enter notes "Good prospect, approved"
16. Confirm approval
17. Verify project status changed to "approved"
18. Logout
19. Login as sales person
20. Create new customer "PT. ABC Indonesia"
21. Link to the lead
22. Select multiple services:
    - Home 20 Mbps (350k/month)
    - Business 50 Mbps (1.5M/month)
23. Set start dates and end dates
24. Submit customer
25. Verify customer created with 2 services
26. Verify project status changed to "completed"

**Expected Result**: Full cycle completed without errors

---

### Scenario 2: Project Rejection Flow
**Objective**: Test rejection and resubmission

**Steps:**
1. Sales create project with unrealistic estimated fee (too low)
2. Manager rejects with notes "Estimated fee too low, check pricing"
3. Sales views rejection notes
4. Sales creates new project with correct fee
5. Manager approves
6. Customer created successfully

**Expected Result**: Rejection properly recorded, new project approved

---

### Scenario 3: Multiple Services Management
**Objective**: Test customer with multiple services

**Steps:**
1. Create customer "PT. XYZ Corp"
2. Assign 3 different services with different dates
3. Verify all services created correctly
4. Later, edit one service to change end date
5. Verify only that service updated
6. Verify other services unchanged

**Expected Result**: Independent service management works correctly

---

## 📈 Reporting Requirements

### Sales Reports (Priority: High)
1. **Lead Pipeline Report**
   - Count by status (new, contacted, qualified, lost)
   - Conversion rate: qualified/total
   - Average time in each status

2. **Project Status Report**
   - Pending approvals count
   - Approval rate (approved/total)
   - Average approval time
   - Rejection reasons analysis

3. **Customer Growth Report**
   - New customers per month
   - Active services count
   - Total monthly recurring revenue (MRR)
   - Customer acquisition by source

### Manager Dashboard (Priority: High)
1. **Pending Approvals Widget**
   - Count of pending projects
   - Quick action buttons
   - Aging analysis (> 3 days old)

2. **Team Performance Widget**
   - Leads by sales person
   - Conversion rates per person
   - Projects submitted vs approved

### Product Reports (Priority: Medium)
1. **Product Performance**
   - Most subscribed products
   - Revenue by product
   - Active subscriptions per product

---

## 🚨 Error Handling & Edge Cases

### Edge Case 1: Lead Without Contact Info
**Scenario**: Sales tries to create lead without phone and email
**Handling**: Validation error - at least one required
**Message**: "Please provide at least phone or email"

### Edge Case 2: Double Project Submission
**Scenario**: Sales tries to create second project for same lead
**Handling**: Check if lead has active/pending project
**Message**: "This lead already has an active project"

### Edge Case 3: Service Date Overlap
**Scenario**: Customer subscribes to same product twice with overlapping dates
**Handling**: Allow (business decision - might be upgrade/downgrade scenario)
**Note**: Consider validation in future if needed

### Edge Case 4: Project Approval After Lead Deleted
**Scenario**: Lead deleted while project pending
**Handling**: CASCADE delete removes project too
**Prevention**: Soft delete leads instead of hard delete

### Edge Case 5: Product Deleted With Active Subscriptions
**Scenario**: Admin deletes product that customers are using
**Handling**: Set NULL in projects and customer_services
**Impact**: Historical data preserved, no orphaned records

---

## 🔐 Security Considerations

### Authentication
- Session-based authentication
- Password hashing with bcrypt
- CSRF protection on all forms

### Authorization
- Middleware checks for logged-in user
- Role-based actions (manager approval)
- User can only edit own data (future enhancement)

### Data Protection
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade escaping
- Input validation on all forms

---

## 📱 Future Enhancements

### Phase 2 (Next 3 Months)
- [ ] Role-based access control (RBAC)
- [ ] Dashboard with charts and statistics
- [ ] Email notifications for approvals
- [ ] Export to Excel/PDF
- [ ] Advanced search and filtering

### Phase 3 (6 Months)
- [ ] Mobile responsive improvements
- [ ] API endpoints for mobile app
- [ ] Integration with billing system
- [ ] Automated reporting
- [ ] Activity log for audit trail

### Phase 4 (12 Months)
- [ ] AI-based lead scoring
- [ ] Predictive analytics for churn
- [ ] Integration with network monitoring
- [ ] Customer portal for self-service
- [ ] WhatsApp integration for notifications

---

## 📞 Support & Maintenance

### System Maintenance Windows
- Weekly: Sunday 02:00 - 04:00 WIB
- Database backup: Daily at 23:00 WIB

### Support Contacts
- Technical Support: [To be defined]
- Business Analyst: [To be defined]
- System Admin: [To be defined]

### Issue Escalation
1. Level 1: User reports to sales manager
2. Level 2: Sales manager contacts IT support
3. Level 3: IT support escalates to development team

---

## 📚 References

### Technical Documentation
- Laravel 11 Documentation: https://laravel.com/docs/11.x
- PostgreSQL 14 Documentation: https://www.postgresql.org/docs/14/
- Simple.css Framework: https://simplecss.org/

### Related Documents
- **DATA_DICTIONARY.md** - Database structure details
- **README.md** - Installation and setup guide
- **database/schema.sql** - Database schema SQL
- **drawio/er_diagram.drawio** - Entity Relationship Diagram

---

**Document Version:** 1.0  
**Last Updated:** 16 Desember 2025  
**Prepared By:** Development Team  
**Approved By:** [Pending]  

**Document Status:** ✅ Complete and Ready for Review

---

## Appendix A: Glossary

- **Lead**: Calon customer yang potensial untuk menjadi customer
- **Project**: Proses konversi lead menjadi customer, memerlukan approval
- **Customer**: Pelanggan yang sudah berlangganan layanan
- **Service**: Layanan internet yang dilanggani customer
- **MRR**: Monthly Recurring Revenue - pendapatan bulanan berulang
- **Approval Workflow**: Proses review dan persetujuan oleh manager
- **Cascade Delete**: Automatic deletion of related records
- **Soft Delete**: Mark as deleted without actually removing from database

## Appendix B: Status Value Reference

### Lead Status
- `new` - Lead baru masuk sistem
- `contacted` - Sudah dihubungi sales
- `qualified` - Memenuhi kriteria untuk jadi customer
- `lost` - Tidak jadi customer

### Project Status
- `pending` - Menunggu approval manager
- `approved` - Disetujui manager
- `rejected` - Ditolak manager
- `completed` - Selesai, customer sudah dibuat

### Customer Service Status
- `active` - Subscription aktif dan berjalan
- `suspended` - Ditangguhkan (masalah pembayaran, dll)
- `terminated` - Berhenti berlangganan

---

**End of Document**
