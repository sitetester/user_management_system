# user_management_system
### Task Description
Stories
* As an admin I can add users. A user has a name
* As an admin I can delete users
* As an admin I can assign users to a group they aren’t already part of
* As an admin I can remove users from a group
* As an admin I can create groups
* As an admin I can delete groups when they no longer have members

#### Required setup/environment:
* PHP 7.2
* Symfony 4.1

#### Project dependencies
[Composer](https://getcomposer.org/) is used for managing dependencies.

After cloning the repository, open a terminal window & run ```composer install``` at root of the project.
It will install all required dependencies of the project which are specified in ```composer.json``` file.
It will also create ```vendor``` directory on file system.


#### DB Connection
sqlite is used as DB engine in ```.env``` file. You don't need to change anything except using other DB engines (e.g mysql, postgres) 

#### Web server:
Open a terminal window & run ```php bin/console server:run``` at root of the project to start the server. Then open a browser with address & port where web
server started in terminal window.

You should see a ```Welcome``` message.


#### DB Schema
run ```php bin/console doctrine:migrations:migrate``` to load DB schema.

#### Db Fixtures (test data)
run ```php bin/console doctrine:fixtures:load``` at command prompt to load test data into database.

#### Super admin login
* admin@example.com
* demo

(This is from /src/DataFixtures/UserFixture.php)

#### After login
* Admin can change password using ```Change Password``` link
* Can create groups, roles & users
* Role(s) must be assigned to a group
* group should be assigned to a user
