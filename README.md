# ToDo API – Laravel Back-End Assessment

This project is the back-end portion of a technical assessment for a Software Engineer position. It demonstrates proficiency in building a robust, scalable, and maintainable RESTful API for managing hierarchical task lists using Laravel.

## Project Purpose

This repository implements a RESTful API for a Todo application as part of a technical assessment. The focus is on backend architecture, data management, and efficient handling of hierarchical task relationships.

## What Was Implemented

### Main Features & Components:

#### RESTful API Endpoints:
- CRUD operations for tasks (create, read, update, delete).
- Listing all tasks, including their hierarchical structure.

#### Recursive Task Hierarchy:
- Support for parent-child task relationships using self-referencing foreign keys.
- Efficient querying with Common Table Expressions (CTEs) for recursive SQL.
- API responses present tasks as a nested tree structure.

#### Authentication:
- User registration and authentication using Laravel Sanctum.
- API endpoints protected with CSRF token authentication.
- Each user can only access and manage their own tasks.

#### Task Prioritization:
- Tasks can be assigned priority levels (High, Medium, Low) using a TaskPriority enum.

#### Data Management:
- Robust database implementation (SQLite for development, supports MySQL/PostgreSQL).
- Validations for incoming data.
- Exception handling.

#### Architecture & Technologies:
- Built with Laravel 12.
- Follows Laravel's MVC architecture (Models, Controllers, Resources, Requests).
- Key packages: Laravel Sanctum, Pest (for testing), Laradumps.

## Areas for Improvement

Given more time, the following enhancements could be made:

- **API Documentation:** Generate comprehensive API documentation (e.g., using Swagger/OpenAPI).
- **Label Manager:** Implement full CRUD operations and management for task labels.
- **User Manager:** Develop more extensive user profile management features.
- **Performance Optimization:** Optimize database queries for very large datasets and consider caching strategies.
- **Error Handling:** Refine error responses for specific scenarios.
- **Security:** Conduct a full security audit and implement additional measures if necessary.
- **Code Quality:** Further refactor code for modularity and add more comprehensive type hinting.

**Note:** The API uses `api.todo.test` as the main domain. If you need to change this, you should also update it in the frontend project to avoid CORS errors.

**Frontend Repository:** https://github.com/lauroguedes/todo

## Setup

1. Clone the repository.
2. Install dependencies:
   ```bash
   composer install
   ```
3. Configure env:
   ```bash
   cp .env.example .env
   ```
4. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```
5. Serve the application:
   ```bash
   php artisan serve
   ```

## API Endpoints

- `GET /api/users` - Get authenticated user details
- `GET /api/tasks` - List all tasks (with hierarchical structure)
- `POST /api/tasks` - Create a new task
- `GET /api/tasks/{id}` - View a specific task
- `PUT/PATCH /api/tasks/{id}` - Update an existing task
- `DELETE /api/tasks/{id}` - Delete a task

## Users for Test

You can use the following credentials for testing:

**User 1:**
- Email: `test@user.com`
- Password: `secret`

**User 2:**
- Email: `test2@user.com`
- Password: `secret`
