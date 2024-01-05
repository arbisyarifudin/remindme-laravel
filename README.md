# ReminderMe Laravel

This is a project created with **Laravel 10** to complete the "Take home challenge for [Senior Laravel Developer at Nabitu](https://ghazlabs.com/nabitu/senior-backend-engineer-laravel.html)."

## 1. Development Requirements
- PHP version 8.1 https://www.php.net/downloads.php along with its necessary packages, can be checked at https://laravel.com/docs/10.x
- PostgreSQL version 14.6 https://www.postgresql.org/
- Git https://git-scm.com
- Composer https://getcomposer.org

**Note:** If you're using Docker [check Docker Docs](https://docs.docker.com/get-docker/), you don't need to manually install the four tools above (Refer to step no. 2).

## 2. How to Run the Application
### a. Without Docker
- Ensure the computer has the necessary development tools installed (Step no. 1)
- Clone this Repo
- Use the terminal or command line
- Copy **.env.example** to **.env** with
  
        cp .env.example .env

- Set up the database connection
   
        DB_HOST=127.0.0.1
        DB_PORT=5432
        DB_DATABASE=database_name
        DB_USERNAME=database_user
        DB_PASSWORD=database_password

- Execute `composer install`
- Execute `php artisan key:generate`
- Execute `php artisan migrate`
- Execute `php artisan db:seed`

### b. With Docker
- Clone this Repo
- Use the terminal or command line
- Run `docker compose up --build`, which will install all development requirements, including database migration and seeding
- If you want to interact with Laravel, access it by using `docker exec -it remindme-php-container /bin/bash`

## 3. How to Run the Project
**Note:** Specific to non-Docker

    php artisan serve

## Coding Style
- Use `camelCase` for variable and function names
- Use plural for naming variables and functions that represent arrays / multiple data, and singular for singular data.
Example: `$users = User::all();` for plural / multiple and `$user = User::find($id);` for singular
- Use single quotes `'...'` for strings and double quotes `"..."` only as an alternative
- Boolean values are `true` and `false` in lowercase
- Route names use `lowercase`, and for more than one word, connect them with a hyphen `-`
- For function names in controllers and additional structures, clear, English names using `camelCase` are mandatory
- Specifically for list-shaped data, use **Laravel Query Builder** for data retrieval optimization
- For data other than lists, use **Laravel Eloquent ORM**

## How to push update
For development purposes, each developer's working branch will be separate according to their name.
To use this repository, follow these steps:

1. Clone this repository
 
        git clone repo-url
2. Checkout to your respective branch

        git checkout -b your-branch
3. Before continuing coding, please update the source code from the `main` branch

        git checkout your-branch
        git pull origin main
4. Before updating the repository, perform the following steps

        git add .
        git pull origin your-branch
        git commit -m "commit comment"
        git push origin your-branch
5. For merging changes to the main branch, use the **Merge Request** feature in GitLab to check for conflicts. There will also be automated testing processes from Github Actions.
6. Ensure that once all changes are pushed, update from the `main` branch

        git checkout your-branch
        git pull origin main

## Developer Team
1. [Arbi Syarifudin](mailto:arbisyarifudin@gmail.com)
