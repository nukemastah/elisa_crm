# Data dictionary — PT.Smart CRM (summary)

Tables:

- `users`
  - `id` (PK): integer
  - `name`: full name
  - `email`: unique login email
  - `password`: hashed password
  - `role`: user role (sales, manager, admin)

- `products`
  - `id` (PK)
  - `code`: product code (unique)
  - `name`: product name
  - `description`
  - `monthly_price`: numeric monthly fee

- `leads`
  - `id` (PK)
  - `name`, `phone`, `email`, `address`
  - `source`: how the lead was acquired
  - `status`: new, contacted, qualified, lost
  - `assigned_to`: FK -> `users.id` (sales person)

- `projects`
  - Represents processing of a lead towards becoming a customer
  - `lead_id`: FK -> leads
  - `product_id`: FK -> products
  - `estimated_fee`: overall cost/fee for installation/project
  - `status`: pending, approved, rejected, in_progress, done
  - `manager_approval`: boolean quick flag
  - `manager_id`: FK -> users (manager who approved)

- `approvals`
  - History of approvals per project
  - `project_id`, `approved_by` (user id), `approved` (boolean), `notes`

- `customers`
  - `lead_id`: optional reference to original lead
  - `joined_at`: when customer relationship started

- `customer_services`
  - `customer_id`, `product_id`
  - `start_date`, `end_date`, `monthly_fee`, `status`

Notes:
- Recommended to implement database constraints and indexes in migrations.
- Convert these DDL statements into Laravel migrations for maintainability.
