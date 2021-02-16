# MVCBlog

MVCBlog is a is a blog CMS made in PHP with a Model View Controller architecture. It's framework influenced foundation permits some degree of modularity and reusability of the core code for other projects.


# Key Features

**CMS**
 - A complete blog CMS with CRUD functionalities and an admin panel
 - Posts management
 - User management
 - Comments management
 - Users have a personal space
 - Email system (for password resets and new accounts confirmation)
 - Responsive design
 
 **Application**
 
 - MVC standards
 - Twig based templating
 - Modular
 - Customizable
 - Object Oriented
 - Framework based core

## Requirements

 - PHP 7.4.3 or higher
 - Composer
 - MySQL
 - PDO extension enabled

## Installation

In your root folder use this Composer command :

    composer install
   then use this : 
   

    composer dump-autoload -o

## Usage

Inside the project's root folder run `php -S localhost:8000 -t public/` to launch the application with PHP's built-in web server.

