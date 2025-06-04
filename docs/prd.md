# Order App - Product Requirements Document

## Overview
A restaurant ordering system that enables customers to browse menus, place orders, and manage their dining experience.
The system includes restaurant menu management, order processing, and client management functionality.

## Core Features

### 1. Restaurant Menu Management
- **Menu Categories**: Organize items by categories (appetizers, mains, desserts, beverages)
- **Menu Items**: 
  - Name, description, price
  - Availability status (available/unavailable)
  - Dietary information (vegetarian, vegan, gluten-free, allergens)
  - Image support
- **Menu Administration**: Restaurant staff can add, edit, and remove menu items
- **Dynamic Pricing**: Support for different pricing based on time/day

### 2. Order Management
- **Order Creation**: Customers can browse menu and add items to cart
- **Order Customization**: 
  - Item modifications (no onions, extra cheese, etc.)
  - Special instructions
  - Quantity selection
- **Order Status Tracking**:
  - Pending (order placed)
  - Confirmed (restaurant accepted)
  - Preparing (kitchen started)
  - Ready (ready for pickup/delivery)
  - Completed (order fulfilled)
  - Cancelled
- **Order History**: Customers can view past orders
- **Order Analytics**: Restaurant can view order trends and popular items

### 3. Client Management
- **Customer Profiles**:
  - Personal information (name, email, phone)
  - Delivery addresses
  - Payment methods
  - Dietary preferences
- **Authentication**: Secure login/registration system
- **Order History**: Track customer order patterns
- **Loyalty Program**: Points/rewards system for repeat customers

## Technical Architecture

### Database Models
1. **User** (existing) - Customer and restaurant staff accounts
2. **Restaurant** - Restaurant information and settings
3. **Category** - Menu organization
4. **MenuItem** - Individual menu items
5. **Order** - Customer orders
6. **OrderItem** - Items within an order
7. **Customer** - Customer profiles and preferences

### User Roles
- **Customer**: Browse menu, place orders, manage profile
- **Restaurant Staff**: Manage menu, process orders, view analytics
- **Admin**: System administration, multi-restaurant management

## Phase 1 Implementation Plan

### MVP Features
1. Basic menu display with categories and items
2. Simple order placement (no customization)
3. Order status updates
4. Customer registration and login
5. Basic restaurant admin panel using Filament

### Database Schema
```sql
-- Categories
CREATE TABLE categories (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    sort_order INT,
    is_active BOOLEAN DEFAULT TRUE
);

-- Menu Items
CREATE TABLE menu_items (
    id BIGINT PRIMARY KEY,
    category_id BIGINT,
    name VARCHAR(255),
    description TEXT,
    price DECIMAL(8,2),
    is_available BOOLEAN DEFAULT TRUE,
    image_url VARCHAR(255),
    dietary_info JSON
);

-- Orders
CREATE TABLE orders (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'),
    total_amount DECIMAL(8,2),
    special_instructions TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Order Items
CREATE TABLE order_items (
    id BIGINT PRIMARY KEY,
    order_id BIGINT,
    menu_item_id BIGINT,
    quantity INT,
    unit_price DECIMAL(8,2),
    modifications TEXT
);
```

## Future Enhancements
- Real-time order notifications
- Payment processing integration
- Delivery tracking
- Multi-restaurant support
- Mobile app
- QR code menu access
- Table management for dine-in orders
