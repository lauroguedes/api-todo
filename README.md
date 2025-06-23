# Task Management API

A RESTful API for managing hierarchical task lists, built with Laravel as a technical back-end test implementation.

## Project Overview

This project implements a task management system with support for hierarchical (parent-child) task relationships. It allows users to create, read, update, and delete tasks through a RESTful API interface. Each task can have multiple subtasks, creating a tree-like structure for organizing work.

## Technologies Used

- **Framework**: Laravel 12
- **Database**: SQLite (supports both MySql and Postgres)
- **Authentication**: Laravel Sanctum for API cookie authentication

### Key Packages
- Laravel Sanctum for API authentication
- [Pest](https://pestphp.com/)
- [Laradumps](https://laradumps.dev/)

## Architecture Overview

The application follows Laravel's MVC architecture with:

- **Models**: Represent database entities (User, Task, Labels)
- **Controllers**: Handle API requests and responses
- **Resources**: Transform model data for API responses
- **Requests**: Validate incoming data

## Key Features

### RESTful API for CRUD Operations

The API provides endpoints for:
- Listing all tasks (with their hierarchical structure)
- Creating new tasks
- Viewing specific tasks
- Updating existing tasks
- Deleting tasks

### Recursive Task Hierarchy

Tasks can have parent-child relationships, creating a hierarchical structure:
- Implemented using self-referencing foreign keys in the database
- Queried efficiently using Common Table Expressions (CTEs) for recursive SQL
- Presented as a nested tree structure in API responses

### Task Prioritization

Tasks can be assigned priority levels, implemented using TaskPriority enum:
- HIGH
- MEDIUM
- LOW

### User Authentication

- Users can register and authenticate to manage their own task lists
- API endpoints are protected with csfr token authentication
- Each user can only access their own tasks

## Getting Started

1. Clone the repository
2. Install dependencies: `composer install`
3. Configure your database in `.env`
4. Run migrations: `php artisan migrate --seed`
5. Serve the application: `php artisan serve`

## API Endpoints

- `GET /api/tasks` - List all tasks
- `POST /api/tasks` - Create a new task
- `GET /api/tasks/{id}` - View a specific task
- `PUT/PATCH /api/tasks/{id}` - Update a task
- `DELETE /api/tasks/{id}` - Delete a task

## Users to test
user: test@user.com, pw: secret
user: test2@user.com, pw: secret

