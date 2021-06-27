This is simple backend API.
Configure your database connection and serve index.html on your webserver.

Available routes:
1. POST api/register - User registration, returns simple bearer token
2. POST api/login - login, returns simple bearer token
3. PATCH users/{id} - change user info
4. GET users/{id} - see user info