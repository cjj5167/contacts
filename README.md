# Contacts Demo

This is an iterative demo application prepared for php[world] 2018. It's divided into multiple _modules_, each of which is intended to be a standalone lesson in PHP security.

Each module subdirectory contains:

- `index.php`  A basic http router to power a built-in PHP server
- `util.php`   Various utility functions provided to make life easier during the lesson at hand
- `lesson.php` The actual code for that given lesson - most with placeholders

## Installation

Composer dependencies are bundled in the repository to make it easier to clone and get started with this project. If for any reason you want to _update_ your dependencies, merely do so with `composer update`.

The first step is to install Composer dependencies in the project root:

The modules themselves are self-contained and share their dependencies.

## Running

Each module is a self-contained application and is meant to be worked on one at a time. Merely `cd` into the target directory, then run the following to start the PHP webserver:

```sh
php -S localhost:8888 -t module-1
```

The application itself can now be [viewed in a web browser](http://localhost:8888).

Alternatively, with Docker:

```sh
./dockphp -S 0.0.0.0:8888 -t module-1
```

**Note:** You can only run one application at a time as they share the same port.

## Understanding the Lessons

Each lesson is built to cover a specific topic regarding PHP security. As such, there are several placeholder `@TODO`s throughout the code that are meant for you to complete. Each is documented explaining what's expected from you to complete the task.

The lessons are structured into the following modules:

**1. Authentication**

`module-1`

- Password management
- Password storage
- Password hashing
- Account reset tokens

**2. Credential Management**

`module-2`

**3. Encryption**

- File encryption (`/module-3`)
- Database encryption (`/module-3-2`)

**4. Session Management**

`/module-4`

- Server-side data management

**5. Data validation**

`/module-5`

- Input sanitization

**6. Long-term Trust**

`module-6`

- Document signing