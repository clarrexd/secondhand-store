# secondhand-store

It's a REST API for a web service where routing is built using URLs that outputs JSON. The web service is intended for creating sellers and items that the seller leaves in the store for sale in the database.

## Getting started

First, clone the project from GitHub to the correct folder. For example, if you are using XAMPP, then clone the project to the htdocs folder in your XAMPP folder on your computer. If you are using XAMPP, make sure both the Apache and MySQL modules are running.

After that, create a databse in MySQL and import the db.sql file that is in the project. You should then have your database needed to use this API.

To test all the endpoints, you could use Postman.

## Endpoints

URL http://localhost/secondhand_store/[endpoint]

If you want to view a specific object, the URL would look like this: http://localhost/secondhand_store/[endpoint]/[id]

GET and POST is available for both sellers and items and PUT only for "Sold" key in items.
Note: You need the endpoint to be for a specific ID to change the "Sold" key.
