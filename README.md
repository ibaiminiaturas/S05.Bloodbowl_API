# 🧠 Blood Bowl API – Laravel REST API

## 📌 Description

This project is a **RESTful API** built with **Laravel** as part of a full-stack web development course. It provides secure endpoints to manage resources related to the game Blood Bowl (e.g., teams, players, matches, etc.).

It is deployed in production on **Railway** and fully documented using **Swagger**.

---

## 🌐 Live Demo

- 🔗 Production: [https://s05bloodbowlapi-production.up.railway.app](https://s05bloodbowlapi-production.up.railway.app)  
- 🔗 Swagger Documentation: [https://s05bloodbowlapi-production.up.railway.app/api/documentation](https://s05bloodbowlapi-production.up.railway.app/api/documentation)

---

## 🚀 Technologies & Packages Used

- **Laravel** – Main PHP framework
- **Laravel Passport** – OAuth2 authentication
- **Spatie Laravel Permission** – Role and permission management
- **Swagger (L5-Swagger)** – Interactive API documentation
- **MySQL / SQLite** – Relational database
- **Railway** – Deployment platform

---

## ⚙️ Local Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/ibaiminiaturas/S05.Bloodbowl_API.git
    cd your-repo
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Copy and set up your `.env` file:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Configure your database connection in `.env`.

5. Run database migrations and optionally seed data:

    ```bash
    php artisan migrate --seed
    ```

6. Install Laravel Passport:

    ```bash
    php artisan passport:install
    ```

7. (Optional) Publish Swagger documentation assets:

    ```bash
    php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
    ```

8. Serve the application:

    ```bash
    php artisan serve
    ```

---

## 🔐 Authentication with Laravel Passport

This API uses **Laravel Passport** to secure endpoints with OAuth2.

### How to authenticate:

1. Register to the api on the /register endpoint, then login with the registration data in the /login endpoint. This will provide an authoritation token.
2. Use the access token in the `Authorization` header:

    ```
    Authorization: Bearer {access_token}
    ```

---

## 🛡️ Role & Permission Control (Spatie)

- Roles like `admin`, `user`, etc. are defined using **Spatie Laravel Permission**.
- Fine-grained access control per resource and action.

---

## 📚 Swagger API Documentation

Interactive API documentation is generated using **L5 Swagger**.

🧭 Access it at:  
`/api/documentation`  
or directly:  
[https://s05bloodbowlapi-production.up.railway.app/api/documentation](https://s05bloodbowlapi-production.up.railway.app/api/documentation)

---

## ✅ Features

- [x] User registration and login
- [x] Token-based authentication with Passport
- [x] Protected routes via auth middleware
- [x] Role & permission control with Spatie
- [x] Swagger UI for API documentation
- [x] Coach management
- [x] Team management
- [x] Player management
- [x] Match simulation between teams

---

## 📦 Deployment

This API is deployed using **Railway**.

Live API:  
🔗 [https://s05bloodbowlapi-production.up.railway.app](https://s05bloodbowlapi-production.up.railway.app)

---

## 🧪 Testing (if applicable)

```bash
php artisan test
```bash

📁 Project Structure
├── app/
│   ├── Http/
│   │   └── Controllers/Api/   ← API controllers
│   └── Models/                ← Eloquent models
├── routes/
│   └── api.php                ← API routes
├── database/
│   └── seeders/               ← Seed data
├── config/
│   └── l5-swagger.php         ← Swagger config
👨‍🏫 Author
Project created by [Ibai Ramirez Pereda] https://github.com/ibaiminiaturas
