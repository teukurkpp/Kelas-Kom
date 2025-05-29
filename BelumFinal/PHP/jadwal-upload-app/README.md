# Jadwal Upload App

## Overview
Jadwal Upload App is a PHP application designed to facilitate the uploading of course schedule data using CSV or Excel file formats. The application provides a user-friendly interface for uploading files and processes the data to store it in a database.

## Project Structure
```
jadwal-upload-app
├── public
│   └── index.php
├── src
│   └── UploadHandler.php
├── vendor
├── composer.json
└── README.md
```

## Requirements
- PHP 7.2 or higher
- Composer
- A MySQL database

## Installation
1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd jadwal-upload-app
   ```
3. Install the dependencies using Composer:
   ```
   composer install
   ```

## Configuration
1. Set up your database and update the database connection settings in `src/UploadHandler.php`.
2. Ensure that the necessary database tables are created to store the uploaded schedule data.

## Usage
1. Open your web browser and navigate to `http://localhost/jadwal-upload-app/public/index.php`.
2. Use the file upload form to select and upload your CSV or Excel file containing the course schedule data.
3. The application will process the uploaded file and insert the data into the database.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.