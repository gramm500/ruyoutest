This is simple backend API.
Configure your database connection and serve index.html on your webserver.

Available routes:
POST api/register - User registration, returns simple bearer token
POST api/login - login, returns simple bearer token
PATCH users/{id} - change user info
GET users/{id} - see user info