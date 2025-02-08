
# SecureChat

SecureChat is a secure messaging platform built with Laravel, designed to provide end-to-end encryption for messages and files. The platform allows users to communicate securely using a secret code for message access and AES-256-CBC encryption for data protection.

## Features

- **Secret Code Authentication**: Access to messages is secured by a secret code, ensuring that only authorized users can read the messages.
- **AES-256-CBC Encryption**: All messages and files are encrypted using AES-256-CBC, ensuring that the content remains secure from end to end.
- **Role-based Access Control**: Different users can have different access levels, such as victims, journalists, and police officers.

## Requirements

- PHP >= 8.0
- Composer
- Laravel >= 9.0
- MySQL or any other supported database
- Node.js & NPM (for frontend asset compilation)

## Installation

### Step 1: Clone the repository

```bash
git clone https://github.com/your-username/SecureChat.git
cd SecureChat
```

### Step 2: Install dependencies

Run the following command to install all necessary PHP dependencies:

```bash
composer install
```

Then, install the frontend dependencies:

```bash
npm install
```

### Step 3: Set up the environment

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### Step 4: Configure the database

Edit your `.env` file and set the correct database credentials:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=securechat
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Run migrations and seed the database

Run the migrations to set up the database structure:

```bash
php artisan migrate
```

### Step 6: Compile frontend assets

Compile the frontend assets using the following command:

```bash
npm run dev
```

### Step 7: Serve the application

You can now serve the application using Laravel's built-in server:

```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## Database Design

![Database Design](https://i.ibb.co.com/LXd56NNR/image.png)

## Admin Account
Navigate to /test endpoint to create admin account

- Username: Admin
- Email: admin@example.com
- Password: password


## License

SecureChat is open-source and available under the [MIT License](LICENSE).