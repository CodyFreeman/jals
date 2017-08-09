# Just Another Login System

JALS is a RELAXED* authentication API written in PHP aiming to provide easy separation between backend API and frontend consumer of the authentication service.

A demo, with a rather hacky frontend can be found at [jals.space](https://jals.space) and the API can be called at [api.jals.space](https://api.jals.space)

**NB! This software is currently in an alpha state. Use at own risk.**

_\* RELAXED: Trying real hard to be REST, but falling short._

## INSTALLATION
You can easily install JALS with [git](https://git-scm.com/) and [composer](https://getcomposer.org/)

Use the git command `git clone https://github.com/CodyFreeman/jals <FOLDERNAME>` to download JALS into your chosen folder.

To install the dependencies of JALS, run `composer install` in the folder containing `composer.json`

Log into your MySQL user and run `CREATE DATABASE jals;` to create the database needed.

Import the default table with `mysql -u <USERNAME> -p jals < <PATH>/app/config/jals.sql;`

Modify your `php.ini` file to secure session cookies by ensuring these settings are set:
```
session.use_strict_mode = 1
session.use_only_cookies = 1
session.cookie_lifetime = 0
session.cookie_secure = 1
session.cookie_httponly = 1
```

## CONFIGURATION
### Changing password rules
By default JALS comes with strict password requirements. The requirements can be changed in `app/config/passwordRules.json` to suit your own needs.

Password requirements for symbols, numbers, uppercase and lowercase characters can be set to 0 to not require the corresponding type.

The accepted characters for each type of requirement can be changed in the passwordComponents part of `app/config/passwordRules.json`

### Database configuration
To set up your database connection, change the values for user and password found in `app/config/databaseConfig.json` to correspond with your database server credentials.

By default JALS uses PDO to connect to MySQL databases: consult PHP.net for proper formatting of dsn string. If you have imported the supplied sql file, you will most likely not need to change this option.

### CORS header
To allow another domain to use JALS' API, you must set the appropriate header to comply with CORS.

Open `app/dependencyInjection/definitions` and locate the following factory function
```PHP
Response::class => function () {

        $response = new Response();
        $response = $response->withHeader('Access-Control-Allow-Origin', 'https://jals.space');
        $response = $response->withHeader('X-Frame-Options', 'DENY');

        return $response->withHeader('Access-Control-Allow-Credentials', 'true');
    }
```
Change `'https://jals.space'` to your own frontend's URL('s). You can also change the X-Frame-Options value, or add your own default headers as needed.

## USAGE
### Expected response format
JALS returns JSON by default. A normal response contains a `status` object with 2 keys; `hasError` contains a boolean denoting whether or not there was an error with the request and `errors` contains an array of errors encountered.

The response may also contain a data object, this is where any data requested is stored.

Example response from a call to `/users/isloggedin` to check if a user is logged in:
```JSON
{
  "status":{
    "hasError":false,
    "errors":[]
  },
  "data":{
    "loggedIn":false
  }
}
```

### Default endpoints
By default JALS comes with several functional endpoints. They can be found in `/app/router/routes.php`

#### Registration - /users/register
To register a new user; send a POST request `/users/register` containing a token, email and password in the body of the request.

#### Change email - /users/changeemail
To change a user's email; send a PATCH request to `/users/changemail` containing a token, email, password and newPassword in the body of the request.

#### Change password - /users/changepassword
To change a user's password; send a PATCH request to `/users/changepassword` containing a token, email, newEmail and password in the body of the request.

#### Log in - /users/login
To log a user in; send a POST request to `/users/login` containing a token, email and password in the request body.

#### Check if logged in - /users/isloggedin
To check if a user is logged in; send a GET request to `/users/isloggedin`. The response contains data object containing `loggedIn` key with a boolean value.

[See it live](https://api.jals.space/users/isloggedin)

#### Log out - /users/logout
To log a user out; send a POST request to `/users/logout` Requires no body.

#### Get CSRF token - /users/gettoken
To get a CSRF token to put into forms; send a request to `/gettoken` The response contains a data object containing `token` key with a string value.

[See it live](https://api.jals.space/gettoken)

#### Get password rules - /users/passwordrules
To get the password rules; send a request to `/users/passwordrules` Requires no body. The response contains a data object containing passwordRules.

[See it live](https://api.jals.space/passwordRules)

## MODIFICATION
JALS uses interfaces extensively to make it easy exchanging part of the application with your own classes.

## LICENSE
Use it, modify it, play with it, break it!