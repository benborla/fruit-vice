Tasks:

API:
[ ] - Setup database
        Actions:
            - Created database via: php bin/console doctrine:database:create
            - Created `fruit` table via: php bin/console make:entity
            - Ran: php bin/console make:migration
            - Ran: php bin/console d:m:m 
            - Setup createdAt and updatedAt using Doctrine lifecycle callbacks
[ ] - Setup commands
    - fruits:fetch
    - fruits:purge
[ ] - Setup service
[ ] - Setup endpoints

Database

fruits:
    - id (primary)
    - name (string)
    - genus (string)
    - family (string)
    - order (string)
    - carbohydrates (float) default: 0f
    - fat (float) default: 0f
    - protein (float) default: 0f
    - sugar (float) default: 0f
    - calories (float) default 0f
    - source (string) options: FRUITY_VICE_API | FRUITY_VICE_APP

users:
    


------
Fruits Test Task

Write a console script for getting all fruits from https://fruityvice.com/ and saving them into local DB (MySQL). We will run this command from terminal.
For example:
php bin/console fruits:fetch

When all fruits a saved into the DB send an email about it to a dummy admin email (e.g. test@gmail.com or your gmail address).

Create a page with all fruits (paginated). Add a form to filter fruits by name and family. Each fruit can be added to favorites (up to 10 fruits).

Create a page with favorite fruits. Display the sum of nutritions facts of all fruits.

Add a README file with installation and startup instructions.

Treat the task as a full-fledged project. In php follow PSR-12, in JS follow JavaScript Standard Style. Unit tests are welcome. You should use the Symfony PHP framework for backend, and VueJS & Twitter Bootstrap for frontend.

Once the project code is ready please upload it to a github repo and share with us the githup repo URL.

I will be waiting for the link tomorrow. End of day Kindly just send it here. Thank you and Good luck!
    

