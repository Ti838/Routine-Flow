# How to Run Routine Flow

Since this project uses **PHP** and **MySQL**, you need a local server environment like **XAMPP** to run it. You cannot just double-click the HTML files anymore for the full experience (specifically for Registration).

## Prerequisites

- Download and Install **XAMPP** (includes Apache & MySQL).

## Step 1: Move Project to XAMPP

1. Navigate to your XAMPP installation folder (usually `C:\xampp`).
2. Open the `htdocs` folder (`C:\xampp\htdocs`).
3. Copy your entire **"Routine Flow"** project folder into `htdocs`.
    - Path should look like: `C:\xampp\htdocs\Routine Flow`

## Step 2: Start the Server

1. Open the **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

## Step 3: Setup the Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **New** on the left sidebar.
3. Create a database named: `routine_flow_db`
4. Click on the new database to select it.
5. Go to the **Import** tab (top menu).
6. Click **Choose File** and select `database/database.sql` from your project folder.
7. Click **Import** (bottom right).

## Step 4: Run the App

1. Open your browser.
2. Go to: `http://localhost/Routine Flow/`
3. You should see the Landing Page.
4. Click **Get Started** -> **Sign Up** to test the PHP Registration.

## Troubleshooting

- **Database Connection Error**: If `register.php` fails, ensure your MySQL username is `root` and password is empty (default XAMPP settings). Check `register.php` to match your credentials.
- **404 Not Found**: Make sure the folder name in `htdocs` matches the URL (e.g., if folder is `Routine-Flow`, URL is `localhost/Routine-Flow`).
