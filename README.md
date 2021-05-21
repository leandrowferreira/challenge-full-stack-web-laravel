# Full Stack Web Developer - Final Submission

Sample CRUD API and frontend implementation as candidate submission to fullstak web developer position at GrupoA.

## Table of Contents

  - [Challenge](#challenge)
  - [Live sample](#live-sample)
  - [Features](#features)
  - [Modules](#modules)
    - [API](#API)
    - [Frontend](#Frontend)
  - [Installing](#installing)
    - [System requirements](#system-requirements)
    - [Installing API](#installing-api)
    - [.env sample](#.env-sample)
    - [Seeding database](#seeding-database)
    - [Testing](#testing)
    - [Development server](#development-server)
    - [Production environment considerations](#production-environment-considerations)
  - [API documentation](#api-documentation)
    - [Authentication](#authentication)
    - [Entry points](#entry-points)
    - [Further information](#further-information)
  - [Comments](#comments)
  - [Resources](#resources)
  - [Credits](#credits)


## Challenge

A challenge is proposed to measure applicant's technical knowledgement. The detailed requeriments are [described here](/00_project-specification/README.md).


## Live sample

A live sample endpoint is online and can be reached at [https://edtech.tmp.br/api/v1](https://edtech.tmp.br/api/v1).

This sample is hosted using AWS EC2 instance, behind Cloudflare, implementing complete encryption (Client <-> Cloudflare <-> AWS), with Let's encrypt SSL Certificate in the last point.

The Frontend ([stored in another repository](https://github.com/leandrowferreira/challenge-full-stack-web-laravel-frontend)) is hosted as static site using AWS S3, with domain name translated by Cloudflare.


## Features

- Restful API
- `Bearer token` protected
- `/login`, `/logout` and `/me` authentication entry points
- Full *CRUD* implementation to the `User` model


## Modules

As requested, the submission is a combination of two distinct modules, that uses also distinct technologies:

### API

- Backend module, maintained in this repository.
- Written in `PHP` using `Laravel 8.41.0`.
- Restful, well-formed entry points.
- Full resource test covering.

### Frontend

- Frontend to consume *API*, stored in [another repository](https://github.com/leandrowferreira/challenge-full-stack-web-laravel-frontend).
- Written in `Javascript` and `Vuetify`.
- Single Page Application (*SPA*) that consumes all *API* entry points


## Installing

Once the system requeriments is fulfilled, the instalation is a simple step-by-step, as described below.

### System requirements

The *API* is fine-tuned to work in a Linux setup that attends all Laravel requirements, using any web server (e.g. Apache). A comprehensive step-by-step system installation from a fresh *Ubuntu Focal Fossa* installation [is described here](/devops/README.md).

### Installing API

Clone the repository.

```bash
$ git clone https://github.com/leandrowferreira/challenge-full-stack-web-laravel.git
```

Install dependencies.

```bash
$ cd challenge-full-stack-web-laravel
$ composer install && composer update
```

Create `.env` file using any text editor (e.g. `nano`). A sample file is presented [below](.env-sample) in this documentation. Note that the database configurations need to reflect the current system.

```bash
$ nano .env
```

Make required migrations. Migrations are how Laravel creates and manipulates tables as a DDL abstraction layer. As a lot of helpful tools, Laravel uses `artisan` command line interface (*CLI*) to run migrations.

```bash
$ php artisan migrate
```


### .env sample

This sample file enables a MySQL connection as described in project requirements, but any other supported database system can be used (e.g. SQLite). If the system installation [previuously described](/devops/README.md) was correctly followed, this file will work without any changes.

```
APP_NAME="EdTech"
APP_ENV=local
APP_KEY=base64:Tm6EKfGUVoVNFiELB8aUbk8Y8SwG9S3uWz4vGw4RjJ8=
APP_DEBUG=true
APP_URL=http://localhost

API_VERSION=1

LOG_CHANNEL=daily
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projetoA
DB_USERNAME=root
DB_PASSWORD=password
DB_PREFIX="ED_"

CACHE_DRIVER=file
```


### Seeding database

Some crucial data needs to exist into database before consuming the *API*, like roles and an admin account. This is reached seeding database. The *API* ships with database seeding routine that fills up the database with this data.

Also, for development and testing purpouses, fake data will be created into `users` table only on development environment. Once again, use `artisan` *CLI* to do this job.


```bash
$ php artisan db:seed
```


### Testing

The *API* was developed using Test Driven Development (TDD), where the test are written **before** the code and then the code needs to comply to purpoused tests. To do so, Laravel bundles [PHPUnit](https://phpunit.de/) out-of-the-box, that can be invoked using an `artisan` command.

```bash
$ php artisan test
```

The test covers 100% of user *API* methods.


### Development server

In the applitacion root directory, using the `artisan`, the develpment buit-in server can be started.

```bash
$ php artisan serve
```

The *API* now is up and running using port 8000, or another one informed in terminal.


### Production environment considerations

The previously given `.env` file implements a local development environment. So, critical system information could be passed to the user as debug information. To migrate this application to production environment, some changes are required in `.env` file:

```
APP_ENV=production
APP_URL=http://the-real-server-production.com
APP_DEBUG=false
```

Further, the seeding routine previously described have different behaviour when it runs on production or development environment. In real life environment, no fake data is created. If you already ran the seeding and then changed the environment on `.env` file, it's a good idea to *fresh* the database and seeding it again. `artisan` does this job as follows:

```bash
$ php artisan migration:fresh --seed
```


## API documentation

As said before, the *API* has *cruddy* entry points to manage users records (in this special case, students records). Additionally, it enables some few entry points to manage authentication, not directly required by project requests, but implemented to demonstrate access restricions to the *API*.

Regardless the base url, the endpoint needs to be completed with `/api/v1` path before required actions.

HTTP correct verbs are required to consume *API*: `get`, `post`, `put` and `delete`, depending on the request.

All *API* calls needs to accept `application/json` responses.


### Authentication

All entry points are protected, except `/login` one. So, the application needs to request user authentication using this call before consume the services. When authenticated, the *API* returns a `Bearer token`, that needs to be informed in the next calls' headers.

Currently this token is not rotating, and is valid along live period (defined in `config/sanctum.php` file). This is not the most recommended approach to use with *SPA* applications, that would be protected using cookie-based section, widely used to address the HTTP stateless feature. Some issues could be experienced using this last proposal, like *CORS* (Cross-Origin Resource Sharing) limitation. These issues can be easily resolved via sofware implementation.


### Entry points

The following entry points are available:

#### Authentication

Method | URI         | Action
-------|-------------|-------
POST   | /login      | Request access token based on user and password
POST   | /logout     | Revoke all active user's tokens
GET    | /me         | Get information about current logged user

#### User management

Method | URI         | Action
-------|-------------|-------
GET    | /users      | Get user list (can be filtered by role in query string)
POST   | /users      | Create a new record
GET    | /users/{id} | Get information about user, given id
PATCH  | /users/{id} | Update user information
DELETE | /users/{id} | Delete user


## Further information

A detailed and compreehensive *API* documentation [can be read here](https://documenter.getpostman.com/view/15870781/TzRa6P5U).


## Comments

Important notes about architecture, third party software and other considerations [can be read here](./COMMENTS.md).


## Resources

  - [Laravel](https://laravel.com/): PHP framework
  - [Composer](https://getcomposer.org/): Dependency manager used by Laravel
  - [PHPUnit](https://phpunit.de/): Testing framework
  - [MySQL](https://www.mysql.com/): Database required by project specs
  - [Let's encrypt](https://letsencrypt.org/): SSL certificate
  - [Apache web server](https://httpd.apache.org/): HTTP web server used to serve *API*
  - [Amazon Web Services](https://aws.amazon.com/): Cloud services used to host *API* and *SPA*
  - [Cloudflare](https://www.cloudflare.com/): DNS and firewall
  - [Ubuntu](https://ubuntu.com/): Linux based OS

## Credits

This implementation was heavily based on the Laravel framework, but the whole work in the developer layer is original content.