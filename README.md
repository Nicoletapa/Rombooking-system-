# Hotel Booking System

Welcome to the Hotel Booking System! This system allows users to search for available rooms, make reservations, and manage their bookings. It also includes an admin panel for managing room availability and user accounts.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)
- [Database Schema](#database-schema)
- [Contributing](#contributing)
- [License](#license)

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
   cd hotel-booking-system
   ```
3. Set up the database:
   - Import the SQL schema from `Roombooking_system_schema.sql` into your MySQL database.
4. Configure the database connection:
   - Update the database configuration in `Includes/config.php` with your database credentials.

## Usage

1. Start your local server (XAMPP)
2. Open your browser and navigate to the project directory (e.g., `http://localhost/Rombooking-system-`).
3. Register a new user or log in with an existing account.
4. Use the navigation bar to access different sections of the system:
   - **Profile**: View and update your profile information.
   - **Reservations**: View and manage your reservations.
   - **Search**: Search for available rooms and make new reservations.

## Database Schema

The database schema is defined in the `Roombooking_system_schema.sql` file. It includes tables for users, rooms, reservations, and roles.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
