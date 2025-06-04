-- Create and use the database
CREATE DATABASE IF NOT EXISTS `assignment` DEFAULT CHRACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE assignment2;

-- Create Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(255) NOT NULL,
    license_number VARCHAR(255) NOT NULL
);

-- Create Cars table
CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_type VARCHAR(50) NOT NULL,
    brand VARCHAR(50) NOT NULL, 
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    mileage VARCHAR(50) NOT NULL,
    fuel_type VARCHAR(50) NOT NULL,
    availability INT NOT NULL, 
    price_per_day DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    vin VARCHAR(17) UNIQUE
);

-- Create Renting History table
CREATE TABLE renting_history (
    rent_id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    rent_date DATE NOT NULL,
    rent_days INT NOT NULL,
    FOREIGN KEY (car_id) REFERENCES cars(id),
    FOREIGN KEY (user_email) REFERENCES users(email)
);