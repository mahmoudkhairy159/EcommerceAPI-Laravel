# EcommerceAPI-Laravel  

Welcome to the **EcommerceAPI-Laravel** repository! This API is built with Laravel to power e-commerce systems, offering a robust, scalable, and secure foundation for online stores, mobile apps, or integrating e-commerce capabilities into existing platforms.

---

## 🚀 Features  

### Core Functionalities  
- **User Authentication**: Secure and efficient user login and registration.  
- **Admin Dashboard**: Manage products, users, and orders from a centralized interface.  
- **Product Management**:  
  - Add, edit, and organize products.  
  - Associate services and accessories with products.  
- **Product Reviews**: Users can rate and review products.  
- **Order Processing**: Manage customer orders with streamlined workflows.  
- **User Wishlists**: Allow users to save their favorite items.  
- **Cart Management**: Handle shopping cart functionalities dynamically.  

### System Content Management  
- Manage application content and settings easily.  

### General Features  
- **App Settings**: Customize global app preferences.  
- **Contact Us Messages**: Handle user inquiries.  

---
## Installation

To get started, clone this repository.

```
git clone https://github.com/mahmoudkhairy159/EcommerceAPI-Laravel.git
```

Next, copy your `.env.example` file as `.env` and configure your Database connection.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR-DATABASE-NAME
DB_USERNAME=YOUR-DATABASE-USERNAME
DB_PASSWORD=YOUR-DATABASE-PASSWROD
```

## Run Packages and helpers

You have to all use packages and load helpers as below.

```
composer install
npm install
npm run build
```

## Generate new application key

You'll need to create a new application key as below.

```
php artisan key:generate
```

## Run Migrations and Seeders

You have to run all the migration files included with the project and also run seeders as below.

```
php artisan migrate
php artisan db:seed
```

## Accessing Admin Panel

You can access the admin login page using these credentials.

```
Email: mahmoudkhairy159@gmail.com
Password: 12345678
```

