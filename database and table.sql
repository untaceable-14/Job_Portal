/*First create a database in any local server on a pc name the database as what is given in the file name connect.php. The name of the databse should be same in both connect.php file and n the server, then only the website works properly. After doing this step copy and run the sql file in the sql section in the database has been created. Thats all everything is done.*/



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `abc` (
  `id` int(100) NOT NULL,
   `name` varchar(255) NOT NULL ,
    `payment_status` varchar(200) NOT NULL 
 ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `adminname` varchar(200) NOT NULL,
  `adminpassword` varchar(50) NOT NULL
 )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `companies` (
  `id` int(100) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `adminname` varchar(200) NOT NULL,
  `adminpassword` varchar(50) NOT NULL
 )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `admins` (`id`, `adminname`, `adminpassword`) VALUES
(1, 'Niteesh', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

CREATE TABLE `mcq` (
  `id` INT(255) NOT NULL AUTO_INCREMENT ,
   `com_id` INT(255) NOT NULL ,
   `company_name` VARCHAR(255) NOT NULL ,
   `questions` VARCHAR(5000) NOT NULL ,
   `answers` VARCHAR(5000) NOT NULL 
   ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `job_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `job_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `company_name` varchar(500) NOT NULL,
  `job_name` varchar(1000) NOT NULL,
  `details` varchar(1000) NOT NULL,
  `salary` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `expected_salary` VARCHAR(20),
  `start_date` DATE,
  `qualification` VARCHAR(50) NOT NULL,
  `specific` VARCHAR(100),
  `university` VARCHAR(100),
  `graduation_year` INT,
  `experience` INT,
  `previous_company` VARCHAR(100),
  `skills` TEXT NOT NULL,
  `certifications` TEXT,
  `portfolio` VARCHAR(255),
  `reason` TEXT NOT NULL,
  `worked_before` ENUM('Yes', 'No') NOT NULL,
  `eligible` ENUM('Yes', 'No') NOT NULL,
  `application_status` varchar(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp()
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE orders 
ADD COLUMN job_id VARCHAR(15) NOT NULL AFTER user_id,
ADD COLUMN phone VARCHAR(15) NOT NULL AFTER location,
ADD COLUMN expected_salary VARCHAR(20) AFTER phone,
ADD COLUMN start_date DATE AFTER expected_salary,
ADD COLUMN qualification VARCHAR(50) NOT NULL AFTER start_date,
ADD COLUMN specific VARCHAR(100) AFTER qualification,
ADD COLUMN university VARCHAR(100) AFTER specific,
ADD COLUMN graduation_year INT AFTER university,
ADD COLUMN experience INT AFTER graduation_year,
ADD COLUMN previous_company VARCHAR(100) AFTER experience,
ADD COLUMN job_title VARCHAR(100) AFTER previous_company,
ADD COLUMN skills TEXT NOT NULL AFTER job_title,
ADD COLUMN certifications TEXT AFTER skills,
ADD COLUMN portfolio VARCHAR(255) AFTER certifications,
ADD COLUMN reason TEXT NOT NULL AFTER portfolio,
ADD COLUMN worked_before ENUM('Yes', 'No') NOT NULL AFTER reason,
ADD COLUMN eligible ENUM('Yes', 'No') NOT NULL AFTER worked_before;

CREATE TABLE orders (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
name VARCHAR(50) NOT NULL,
number VARCHAR(15) NOT NULL,
email VARCHAR(100) NOT NULL,
company_name VARCHAR(100) NOT NULL,
job_name VARCHAR(100) NOT NULL,
details TEXT NOT NULL,
salary VARCHAR(20),
location VARCHAR(100) NOT NULL,


application_status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `job_name` varchar(100) NOT NULL,
  `details` varchar(1000) NOT NULL,
  `salary` int(100) NOT NULL,
  `required_skills` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `test_results` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) NOT NULL, 
    `test_id` INT(11) NOT NULL, 
    `company_name` varchar(255) NOT NULL, 
    `answers` TEXT NOT NULL, 
    `score` FLOAT NOT NULL,
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users_details` (
  `id` INT NOT NULL AUTO_INCREMENT ,
   `user_id` INT(255) NOT NULL ,
    `name` VARCHAR(255) NOT NULL ,
     `email` VARCHAR(255) NOT NULL ,
      `dob` VARCHAR(255) NOT NULL ,
       `education` VARCHAR(255) NOT NULL ,
        `passout` VARCHAR(255) NOT NULL ,
         `location` VARCHAR(255) NOT NULL ,
          `number` VARCHAR(255) NOT NULL ,
           `profile_img` VARCHAR(255) NOT NULL ,
            `resume` VARCHAR(255) NOT NULL ,
            PRIMARY KEY (`id`)
            ) ENGINE = InnoDB;


ALTER TABLE `abc`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);




ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `abc`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;


ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;


ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
COMMIT;
