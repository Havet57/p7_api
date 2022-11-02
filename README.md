# P7_api

This project is an API REST made with Nelmio . Its goals were to learn basics of API REST.


## Installation


Install the project by cloning it onto your system using git

```
  git clone https://github.com/Havet57/P7_api P7_api
  cd P7_api
  composer install
```

## Database

Please create a mysql database named `P7_api` with utf8_general_ci. 
Then run this command line `mysql -uroot -p P7_api < database.sql` to create all the tables.

## Environment Variables

To run this project, you must update the `config/database.json` with your database values (host, user, password, dbname).
 
