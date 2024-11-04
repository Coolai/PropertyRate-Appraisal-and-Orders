## About Appraisal and Orders Project

This is my solution to PropertyRate's case study challenge.

## Running Locally

This project uses Laravel Sail that uses Docker to build the application. To run this project  locally, follow the steps below:

- After Git cloning this project, change to its directory.

    `cd ./nameOfThisProjectLocally/`

- Start Sail.

    `./vendor/bin/sail up`

- Go into the Docker container of the Laravel app.

    `docker ps`
    
    `docker exec -it [containerIdOfApp] bash`

- Run the database migration.

    `php artisan migrate`

- Go to http://localhost/admin/login to see the application.