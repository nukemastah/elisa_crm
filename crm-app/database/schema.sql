-- PostgreSQL schema for PT.Smart CRM (minimal)
-- Run with: psql -h <host> -U crm_user -d crm_db -f schema.sql

-- Enable uuid-ossp for UUID generation if desired
-- CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Users (auth) - minimal
CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'sales', -- sales, manager, admin
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Products (master layanan)
CREATE TABLE IF NOT EXISTS products (
  id SERIAL PRIMARY KEY,
  code VARCHAR(50) UNIQUE NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  monthly_price NUMERIC(12,2) DEFAULT 0,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Leads (calon customer)
CREATE TABLE IF NOT EXISTS leads (
  id SERIAL PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  phone VARCHAR(50),
  email VARCHAR(150),
  address TEXT,
  source VARCHAR(100),
  status VARCHAR(50) DEFAULT 'new', -- new, contacted, qualified, lost
  assigned_to INTEGER REFERENCES users(id) ON DELETE SET NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Projects: processes leads into an installation/project
CREATE TABLE IF NOT EXISTS projects (
  id SERIAL PRIMARY KEY,
  lead_id INTEGER REFERENCES leads(id) ON DELETE CASCADE,
  product_id INTEGER REFERENCES products(id) ON DELETE SET NULL,
  estimated_fee NUMERIC(12,2) DEFAULT 0,
  status VARCHAR(50) DEFAULT 'pending', -- pending, approved, rejected, in_progress, done
  manager_approval BOOLEAN DEFAULT FALSE,
  manager_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
  approval_notes TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Approvals history to keep track of manager decisions
CREATE TABLE IF NOT EXISTS approvals (
  id SERIAL PRIMARY KEY,
  project_id INTEGER REFERENCES projects(id) ON DELETE CASCADE,
  approved_by INTEGER REFERENCES users(id) ON DELETE SET NULL,
  approved BOOLEAN,
  notes TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Customers (after successful project)
CREATE TABLE IF NOT EXISTS customers (
  id SERIAL PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  phone VARCHAR(50),
  email VARCHAR(150),
  address TEXT,
  joined_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  lead_id INTEGER REFERENCES leads(id) ON DELETE SET NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Customer services (list layanan per customer)
CREATE TABLE IF NOT EXISTS customer_services (
  id SERIAL PRIMARY KEY,
  customer_id INTEGER REFERENCES customers(id) ON DELETE CASCADE,
  product_id INTEGER REFERENCES products(id) ON DELETE SET NULL,
  start_date DATE,
  end_date DATE,
  monthly_fee NUMERIC(12,2) DEFAULT 0,
  status VARCHAR(50) DEFAULT 'active', -- active, suspended, cancelled
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Sample data (seeds)
INSERT INTO users (name, email, password, role) VALUES
('Admin User','admin@example.com','$2y$10$examplehashedpassword', 'admin'),
('Manager One','manager@example.com','$2y$10$examplehashedpassword', 'manager'),
('Sales One','sales@example.com','$2y$10$examplehashedpassword', 'sales')
ON CONFLICT DO NOTHING;

INSERT INTO products (code, name, description, monthly_price) VALUES
('FTTH-50','FTTH 50 Mbps','Fiber to the home 50 Mbps', 300000.00),
('FTTH-100','FTTH 100 Mbps','Fiber to the home 100 Mbps', 500000.00)
ON CONFLICT DO NOTHING;

INSERT INTO leads (name, phone, email, address, source, status, assigned_to) VALUES
('Budi Customer','08123456789','budi@mail.com','Jl. Kebon Jeruk, Jakarta','Website','new',3)
ON CONFLICT DO NOTHING;

-- Create a project from the lead
INSERT INTO projects (lead_id, product_id, estimated_fee, status, manager_approval, manager_id) VALUES
((SELECT id FROM leads WHERE name='Budi Customer' LIMIT 1),(SELECT id FROM products WHERE code='FTTH-50' LIMIT 1),1000000.00,'pending',FALSE,2)
ON CONFLICT DO NOTHING;
