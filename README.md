# Motel Booking

Welcome to the Motel Booking System! This system allows users to search for available rooms, make reservations, and manage their bookings. It also includes an admin panel for managing room availability and user accounts.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)
- [Database Schema](#database-schema)

## Features

- User registration and authentication
- Search for available rooms based on check-in and check-out dates
- Make and manage reservations
- User profile management
- Admin panel for managing rooms and users

## Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/Nicoletapa/Rombooking-system-.git
   ```
2. Navigate to the project directory:
   ```sh
   cd Rombooking-system-
   ```
3. Set up the database:
   - Import the SQL schema from `SQLDump.sql` into your MySQL database.
4. Configure the database connection:
   - Update the database configuration in `Includes/config.php` with your database credentials.

## Usage

1. Start your local server (XAMPP)
2. Open your browser and navigate to the project directory (e.g., `http://localhost/Rombooking-system-`).
3. Register a new user or log in with an existing account.
   Admin account: Thevithach (username), Admin123* (Password)
   Customer: Customer (username), Customer123* (Password)
4. Use the navigation bar to access different sections of the system:
   - **Profile**: View and update your profile information. (Click on name in navbar)
   - **Reservations**: View and manage your reservations. (In profile section sidebar)
   - **Search**: Search for available rooms and make new reservations.(Home page)

## Database Schema

The database schema is defined in the `SQLDump.sql` file. It includes tables for users, rooms, reservations, and roles etc.
