# Comments about this project

Here you can read notes about architecture, third part software and other important considerations about this project.

## Table of Contents

  - [Architecture](architecture)
  - [Third party software](#third-party-software)
  - [What to do with more time](what-to-do-with-more-time)
  - [What required items was not delivered](what-required-items-was-not-delivered)


## Architecture

Laravel provides an ideal framework to develop APIs, since it delivers a complete toolbox to manage requests, routing and authentication. Besides, using it always forces de developer to follow best dev paradigms, like restful *APIs* and *SOLID* concepts. The MVC architecture was applied, where the "degraded" *View layer* is performed by `json` responses to the client.

At the backend, the default Laravel configuration was used to manage resource routes and so stripping out unnecessary code from the project. Various frameworks resources was used

MySQL database is a project request but Laravel supports out-of-the-box several database managers, such as SQLite, PostgreSQL and MSSQL, additionalli, other *DBMS* could be implemented, using third party soluctions, such as Oracle.

## Third party software

To develop the backend *API* solution, no third party software or library was used. All solutions were provided by Laravel framework.

Although, it's valid to cite some software and libraries that played important role in development:

  - [Laravel Sanctum](https://laravel.com/docs/master/sanctum) provides a featherweight authentication system for *SPAs*, mobile applications, and simple, token based *APIs*. It's an optional package added to project using the Composer dependency manager.
  - [Faker](https://github.com/fzaninotto/Faker) is a PHP library that generates fake data. It's present in Laravel skeleton application, so, there's no need to install it.
  - [PHPUnit](https://phpunit.de/) is a programmer-oriented testing framework for PHP. This is bundled with Laravel and is integrated using the `php artisan test`command.

## What to do with more time

Programming is the art of always refactoring. There's an extensive list of features that could be implemented or enhanced, but just to cite the most important ones:

  - Log all data changes through *API*
  - Add localization in front and backend
  - Create Postman library to make easy to implement solutions that consumes *API*
  - Enable Sawgger to the *API*
  - Implement Websockets to guarantee frontend sync
  - Enable cookie-based session authentication to SPA frontend access
  - Token rotation for every *API* call
  - Implement complete Auth interface to better user management

## What required items was not delivered

This implementation tries to meet all project specs, including all features required and even this file name.

The deploy instructions give as examples [Netlify](https://www.netlify.com/) and [Heroku](https://www.heroku.com/) platforms, that wasn't used in this project, but the term "ou semelhante" (or equivalent) gives margin to another approaches to be chosen. In present case, AWS services was chosen for deployment.