# Scope

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)


## Definition: Employee Management

## Database Structure:
    - Employee table columns: id, department_id, name, dob, phone, photo, email, salary, status, created, modified
    - Department table columns: id, name, status, created

1. List down all employee in grid with paging feature.
2. Give functionality to add/edit and delete employee.

## Provide below statistics.
1. Department wise highest salary of employees.
2. List down employees who are not belongs to any department.
3. Find the name and age of the youngest employee in each department.

## Preferred technology:
Use any PHP MVC framework, Ajax, MySQL, Jquery.

Please follow below points to test Pratical demo project
 1) clone the project // git clone git@github.com:cooolniky/pet-pooja.git
 2) install composer // composer install
 3) create database and add in env file
 3) migrate with seeder  // php artisan migrate --seed
 4) login with username = 'admin@default.com' and password = 'admin123'
