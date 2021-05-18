# MVCBlog

[![SonarCloud](https://sonarcloud.io/images/project_badges/sonarcloud-white.svg)](https://sonarcloud.io/dashboard?id=Tavrin_oc-mvc-php-blog)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Tavrin_oc-mvc-php-blog&metric=alert_status)](https://sonarcloud.io/dashboard?id=Tavrin_oc-mvc-php-blog)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=Tavrin_oc-mvc-php-blog&metric=code_smells)](https://sonarcloud.io/dashboard?id=Tavrin_oc-mvc-php-blog)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=Tavrin_oc-mvc-php-blog&metric=duplicated_lines_density)](https://sonarcloud.io/dashboard?id=Tavrin_oc-mvc-php-blog)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=Tavrin_oc-mvc-php-blog&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=Tavrin_oc-mvc-php-blog)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=Tavrin_oc-mvc-php-blog&metric=bugs)](https://sonarcloud.io/dashboard?id=Tavrin_oc-mvc-php-blog)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=Tavrin_oc-mvc-php-blog&metric=security_rating)](https://sonarcloud.io/dashboard?id=Tavrin_oc-mvc-php-blog)

MVCBlog is a is a blog CMS made in PHP with a Model View Controller architecture. It's also a modular Symfony and Doctrine inspired web framework.


## Key Features

**CMS**

- A complete blog CMS with CRUD functionalities and an admin panel
- Posts, Users, Comments, Media and Category management
- A message repository for the contact form
- A powerful and customizable rich text editor
- Users profiles and personal spaces
- Email system (for password resets and new accounts confirmation)
- Responsive design

**Application**

- A robust Symfony inspired framework kernel with an MVC architecture
- A Doctrine inspired ORM layer on top of PDO
- Twig templating
- Modular
- Customizable and extensible
- Object Oriented
- Robust and secure
- Contains a command system which can be extended

## Requirements

- PHP 7.4.3 or higher
- Apache 2.4 or higher to be able to host the website, mod_rewrite should be enabled
- Composer
- MySQL
- PDO extension enabled
- PHPMyAdmin highly recommanded

## Installation

Begin by cloning the repository
```sh
git clone git@github.com:Tavrin/oc-mvc-php-blog.git
```
In the project's root folder use this Composer command :
```sh
composer install
```
then use this :
   ```sh
    composer dump-autoload -o
```

1. In the **/db** you will find a database file (db.sql)
2. Go to your PHPMyAdmin interface
3. Go to the Import tab
4. Choose the sql file and import it, make sure the character set is utf-8

You will need to create an .env.local file in the project's root folder, you will have to add the database connection information as well as the email credentials if needed. You can use the provided .env file which contains fake information as a model.

the database URI is set in this manner :
```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```
- mysql : the database type, mysql is the only type working with this framework
- db_user : the username of the user which is used to connect to the database. This user should have admin rights over this database (but not on the other ones for added security)
- db_password : the user's password
- 127.0.0.1:3306 : the database's address and port
- db_name : the database's given name.
## Usage

Inside the project's root folder run `php -S localhost:8000 -t public/` to launch the application with PHP's built-in web server.

---
## CMS Documentation
### First use
When using the CMS for the first time and importing the database you will have a default admin account with the credentials :
- email : admin@admin.com
- password : admin

use those to connect to the account on the login page (top right of the page).
You should then go to the backoffice (little lock icon in the footer), and change your credentials as soon as possible :
- Go to **Utilisateurs**
- On the admin user row, on the right click on the **action** button and select **Modifier**, here modify your user credentials and information

### Managing the categories
First of all you will need categories to publish your blog posts to. They are very easy to manage, modify, create and delete.
- In the backoffice, go to **structure**
- Then go to **Catégories**, here you will be able to manage the categories
- To create your first category, click on **nouvelle catégorie** on the upper right corner
- Here you need to add your category information. For now the media gallery is empty. We will see the different ways of adding a media in the next part, for now you it can be left empty.
- When done, click on **accepter**, your new category is created and active, you can find it in your header menu
- To modify a category you also need to go to **Catégories**, click on the **action** button and choose **Modifier**
- To delete a category, the **action** button should also be used, but you will need to click on **delete** instead

### Managing the medias
Medias can be used for blog posts, as default categories images (in the posts listing, if an article doesn't have a main image), and for user profile images.

- The default media type is the Image. To add or modify an image go to  **/admin/structure/medias/image**
- Here you can add a new image by clicking on **nouveau média**
- In the form you need to add information about the image, and upload it. If successful you will see it in the medias/image gallery from before
- Here you will see every image added to that media type, the **action** button lets you modify or delete an image

Images can also be added from the image gallery modal used to choose an image in different forms, by clicking on **ajouter une image** on the top left of the modal, and completing and uploading the image like before. Once successful, the modal media gallery will immediately be updated with the new image.

### Managing blog posts
Blog posts can be found in the **/admin/posts** section.
#### Your first blog post
- Go to the previously given link to the posts management page
- Click on **nouvel article**
- add the post information, if you don't have any categories yet create one first (see the **managing the categories** section), same for the main media, you can choose one from the gallery modal by clicking on **galerie média**, if you don't have any you can add one (see the **managing the medias** section).
- Use the rich text editors to create the header and the main article content sections of your post
- Once done, click on **publier** and you are **done**, it's as easy as that. You can now go see your blog post on the blog, if you used headings other that H1 in your main article content you will see that a summary with links to the titles was automatically created

To modify or delete a blog post, go to the post management page and use the **action** button

### Managing users
- Go to **admin/users/** to manage users
- To create a new user click on **Nouvel utilisateur** on the top right, then fill in the information and confirm
- To modify or delete a user, on the user management page, click on **action** and choose your action

note : as this is a simple one author blog CMS, there are no possibilities to add new admins, all the users can only have the ROLE_USER role, this means that roles can't be modified in the user settings.

### Managing comments
- Go to **admin/comments/** to manage comments
- To confirm a new comment so that it can show on the blog : click on the **action** button and choose **activer**
- To moderate an already confirmed comment, click on the **action** button and activate or deactivate the comment

### Managing Messages
Messages are what users wrote to you on the contact form. Those are sanitized and sent to the admin email configured inside the config file as well as stored here.

- Go to **admin/configuration/messages** to manage messages
- Here you can see the message information, you can delete messages with the **action** button


---
## Framework Documentation

### Setting up index.php
To be used, the framework needs to be integrated into the index.php file, the environment variables need to be loaded and the ROOT_DIR constant needs to be defined, here is a simple example of its implementation :
   ```php
    <?php

use Core\Kernel;
use Core\http\Request;

define('ROOT_DIR', dirname(__DIR__));

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_DIR, '.env.local');
$dotenv->load();
$request = Request::create();
$kernel = new Kernel();
$response = $kernel->handleRequest($request);
$response->send();
exit();
```

### Creating routes
Routes need to be set up in the config folder as a JSON file named **routes.json**, in this json file the routes need to be set up as objects inside an array, with the following elements :
- **route** : the route name
- **path** : the route path
- **controller** : the controller's namespace and method, example : controller": "App\\Controller\\IndexController::indexAction

Here is an example with several routes :
  ```json
 [
    {
        "route": "index",
        "path": "/",
        "controller": "App\\Controller\\IndexController::indexAction"
    },
    {
        "route": "blog_index",
        "path": "/blog",
        "controller": "App\\Controller\\BlogController::indexAction"
    }
]
```

Routes can have wildcard parameters in their path, which need to be set in between brackets. Those wildcars can then be used as arguments for the associated controller method :
```json

    {
        "route": "blog_show",
        "path": "/blog/{category}/{slug}",
        "controller": "App\\Controller\\BlogController::showAction"
    }
```
*example of a path with several wildcard parameters (category and slug)*

### Controllers
Controllers are the endpoints of the routes, they can have the **Request** as well as the **Wildcard parameters** as arguments.

They are where the request is processed and where a **Response** needs to be returned. Generally this is either a **rendered html** page (from a Twig file) with a parameters array, a **JON response**, or a **redirect**.

#### Response types
**Rendering in HTML :**
To render in HTML, the **Controller** method render() needs to be called, there are two parameters to pass, a link from the templates directory to the correct Twig template file, as well as an optional parameters array, which will pass data which can be reused in the associated template.
```php
return $this->render('/path/to/template.html.twig', ['parameterToPass' => $variable])
```
**Sending a JSON response :**
To send a JSON response, the method sendJson (which contains two parameters, a data array and an optional status array with a 200 default value).
 ```php
return $this->sendJson(['response' => $content], 403);
```
**Sending a JSON response :**
Redirectionc can also be made from the controller, the redirect method needs to be called, an url needs to be passed, an optional flash message can also be passed in an array, with the 'type' parameter, which can be anything (generally success or danger/error) and the 'message' parameter, which is the flash message.
```php
$this->redirect("/", ['type' => 'success', 'message' => "Bienvenue"]);
```

#### Most important methods
The abstract controller has several usable methods which are important to know about :
- getManager() : gets the instantiated EntityManager, which is used for everything about Entities and database connections (seen later)
- getUser() : gets the current User if connected and in session.
- createForm(object $entity, array $options(optional)) : instantiates a new Form object
- flashMessage(string $type, string $message) : sets a flash message up for the next request
- render(string $template, array $parameters) : sets the render
- sendJson(array $data, int  $status) : sets a JSON response
- redirect(string $path, array $flash = ['type' => null, 'message' => null]) : sets a redirect with an optional flash message


### Database and Entities
For the database, first of all a database URI needs to be provided, this URI is then parsed and called inside the framework with PDO, an abstraction layer is then added on top of it, with a Doctrine inspired Entity system. The three major parts of an entity is the Entity class, the associated repository, and the associated JSON config file which contains all the needed information about the entity. The EntityManager class (which can be called in controllers with $this->getManager()) is the "brain" of the entity system and coordinates everything together. Let's see all of this in details.

#### Setting up the database URI
The database needs to be set up in a database.json file in the config directory. The recommanded way is setting the URI (which is a sensitive information) in the .env.local file and calling it in the database.json file as en environment variable, like this:

```sh
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```

```json
{
  "database" :
  {
    "url" : "$_ENV['DATABASE_URL']"
  }
}
```

#### Entities
An Entity is a PHP Class with properties and setter and getter methods (as well as custom methods if needed) that abstracts MySql data as PHP objects and makes it easy to manipulate. If you are familiar with Doctrine Entities you will easily recognize the patterns here, but there are some subtle differences. For example, the associated information used for the database is not set as annotations but as a JSON file with all the needed information (more below in the **Entities Config** section).
Here is an example of a part of an entity :
```PHP
namespace App\Entity;

class Post
{
/* Properties */
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string|null
     */
    private ?string $title = null;
    
    /* Methods */

     public function __construct()
    {
    /* Properties can be set here */
        $this->title = 'Default title';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }
}
```

#### Repository
The repository is where the entity or entities are retrieved and hydrated, it consists of a class extending an abstract Repository class. The most important part here is having to pass the entity name as a string.

Example :

```PHP
<?php

namespace App\Repository;

use Core\database\EntityManager;
use Core\database\Repository;

class PostRepository extends Repository
{
    public function __construct(?EntityManager $entityManager = null)
    {
        parent::__construct($entityManager, "post");
    }
}
```

#### Entities config
The entities need JSON configuration files that need to be placed in the **/config/entities/** folder with the same name as the entity. Those files need to contain :
- The repository namespace
- the entity namespace
- the entity name
- the entity database table name
- the fields of the entity, which are the properties, those fields need to have as object title the name of the entity property, they also need to contain :
    - the fieldName which is the table field name
    - the type (json, string, text, datetime, bool, association). If the type is **association**, it is also needed to precise some information about the associated entity :
        - associatedEntity: the name of the associated entity
        - repository: the namespace of the associated entity's repository
- if there are any, the childrenEntities, which are associated entities that need to also be removed if the parent entity is deleted.
    - the title of the json should be the associated entity
    - like for association type entities, it is needed to provide the entity name with the associatedEntity field.

Example :
```json
{
    "repository" : "App\\Repository\\PostRepository",
    "entity" : "App\\Entity\\Post",
    "name" : "post",
    "table" : "post",
    "id" :
    {
      "type" : "integer"
    },
    "fields" :
    {
        "title" :
        {
            "fieldName" : "title",
            "type": "string"
        },
        "category" :
        {
            "fieldName" : "category_id",
            "type": "association",
            "associatedEntity": "category",
            "repository": "App\\Repository\\CategoryRepository"
        }
    },
    "childrenEntities" :
    {
        "Comment":
        {
          "associatedEntity" : "comment"
        }
    }
}
```
#### Entity Manager
The Entity Manager serves as the coordinator, it gets, parses and passes the entities data, connects to the database through PDO, passes the queries, is used to add, modify or remove entities.

##### most important methods
EntityManager has several important methods to know :
- getEntityData(string $entityName) : gets and parses the data about an entity
- ::getAllEntityData() returns the data about all the entities, it's a static method that can be used anywhere.
- save(object $entity) prepares a statement to save a new entity in the database
- update(object $entity) prepares a statement to update an existing entity
- remove(object $entity) prepares a statement to delete an existing entity
- flush() execute the prepared statements one after the other
- getStatements() get the actually prepared statements (they don't stay between requests)

### Security
Security is an important part of any web application and framework, and this framework comes with several features to enhance this.

#### Firewall
The firewall is set up in **/config/security.json**, the important part inside this file is the **firewalls** part, which has three parameters :
- a pattern for the affected paths
- which roles have access to this path
- an optional redirect parameter, to which the user needs to be redirected if the access is denied

Example :
```json
{
    "firewalls":
    {
        "admin":
        {
            "pattern": "/admin",
            "roles":
            [
                "ROLE_ADMIN"
            ],
            "redirect": "/login"
        }
    }
}
```

#### Other security considerations
- There is a built in feature which verifies a logged user's session details and compares them to the database information. If there is a difference the user is disconnected. If you don't want your users to be disconnected after a profile or account change for example, it is recommanded to update the session so that the user's session information stays up to date with the new entity data.
- PDO's prepare and execute methodology is used so that no query is directly used as is for database interactions. Also entity datas are abstracted as much as possible, more than ease of use and conformity, it also makes database interactions with user inputted data more secure.
- The request links are sanitized as early as possible during the request processing.
- The integrated Forms functionality (seen below) incorporates several sanitizing and verification processes by default. To name a few : CSRF tokens, input sanitization, entity compatibility testing if needed

### Forms
The Form class is an in-built system that makes it easy to rapidly do secure forms that can be associated or not with an entity (and automatically adds the processed input data into it). In this framework, forms are very customizable with lots of option that can be entered in an array. Here are some examples of forms :

Contact form :
```PHP
        $this->addTextInput('fullName', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Prénom / Nom'])
            ->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email'])
            ->addTextareaInput('content',['class' => 'form-control', 'placeholder' => "Ecrire un message", 'value' => '', 'label' => 'Message', 'rows' => 5, 'cols' => 35])
            ->setSubmitValue('Envoyer', ['class' => 'button-bb-wc-2 as-c br-5 mt-1'])
            ->addCheckbox('consent', ['class' => 'text-muted', 'label' => 'I consent to share my personal data', 'entity' => false,])
            ->addCss('d-f fd-c');
```

Register form :
```PHP
        $this->addTextInput('username', ['class' => 'form-control', 'placeholder' => "Nom d'utilisateur"]);
        $this->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email']);
        $this->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe', 'hash' => true]);
        $this->setSubmitValue('accepter', ['class' => 'button-bb-wc']);
```

Blog post creator/editor form :
```PHP
        $this->addCss('w-75')
            ->addTextInput('title', ['class' => 'form-control js-binder', 'placeholder' => "Titre", 'dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]], 'value' => 'edit' === $options['type']? $entity->getTitle() : null])
            ->addTextInput('metaTitle', ['class' => 'form-control', 'placeholder' => "Méta titre",  'value' => 'edit' === $options['type']? $entity->getMetaTitle() : null])
            ->addTextInput('metaDescription', ['class' => 'form-control', 'placeholder' => "Méta description",  'value' => 'edit' === $options['type']? $entity->getMetaDescription() : null])
            ->addTextInput('slug', ['class' => 'form-control', 'placeholder' => "Slug", 'value' => 'edit' === $options['type']? $entity->getSlug() : null])
            ->addSelectInput('category', $options['selection'], ['class' => 'form-control w-75', 'placeholder' => 'choisissez une catégorie', 'label' => 'catégorie :', 'targetField' => 'id', 'selected' => $category])
            ->addDateTimeInput('createdAt', ['class' => 'form-control', 'placeholder' => "Date de publication", 'value' => 'edit' === $options['type']? $entity->getCreatedAt()->format("Y-m-d\TH:i:s") : null])
            ->addDateTimeInput('updatedAt', ['class' => 'form-control', 'placeholder' => "Date de modification", 'value' => 'edit' === $options['type']? $entityUpdated : null])
            ->addSelectInput('status', $Selection, ['class' => 'form-control w-75', 'placeholder' => 'Publié', 'label' => 'Statut :', 'targetField' => 'status', 'selected' => $selected])
            ->addSelectInput('featured', $Selection, ['class' => 'form-control w-75', 'placeholder' => 'En vedette', 'label' => 'En vedette :', 'targetField' => 'featured', 'selected' => $selectedFeatured])
            ->addHiddenInput('mediaHiddenInput', ['entity' => false, 'class' => 'js-binder', 'required' => false,'dataAttributes' => ['type' => 'image', 'from' => 'modal', 'target' => 'previewImage']])
            ->addButton('mediaLibrary', ['class' => 'js-modal button-bb-wc m-1', 'value' => 'Galerie média', 'type' => 'button', 'dataAttributes' => ['target-modal' => 'mediaModal']])
            ->addDiv('mediaShow', ['class' => 'hrem-15 js-filler pt-1', 'dataAttributes' => ['type' => 'image', 'id' => 'previewImage', 'class' => 'mh-80 d-b mw-100', 'src' => 'edit' === $options['type']? $media : ''], 'wrapperClass' => 'mt-1', 'label' => 'Prévisualisation du média principal'])
            ->addHiddenInput('header', ['sanitize' => false])
            ->addHiddenInput('content', ['sanitize' => false]);
```

As we can see this system is very customizable and robust. We can add input types and pass some data, a name and options in an array, for each input field.

To instantiate a form there are several ways :
- the controller methode createForm(object $entity, array $options = [])
- directly insantiating the Form class (Request $request, object $entity, Session $session, array $options = [])
- extending the Form class, adding your form inputs here and instantiating this new form type. This is recommended to declutter the controller.

Here is an example using the third method :
```php
$editorForm = new EditorForm($request,$post, $this->session, ['name' => 'newPost','submit' => false, 'selection' => $selection, 'type' => 'new', 'wrapperClass' => 'mb-1']);
```

#### Form class options
The form class can have several options passed to it in the $options array :
- method : the form method (POST by default)
- name : the form name, optional
- submit : if set and false, disables the submit button, true otherwise
- wrapperClass : sets classes for the wrapper divs of every input if defined, has to be a string
- errorClass : sets the CSS for error events for every input if defined.
- action : sets the action path of the form if defined, else it will set the current request's path as the action path.
- sanitize: if defined and set to false, the inputs wont be sanitized

#### Inputs / Form creation methods
- addTextInput(string $name, array $options)
- addDateTimeInput((string $name, array $options)
- addHiddenInput((string $name, array $options)
- addButton((string $name, array $options)
- addDiv((string $name, array $options)
- addTextareaInput((string $name, array $options)
- addPasswordInput((string $name, array $options)
- addCheckbox((string $name, array $options)
- addSelectInput((string $name,array $selection, array $options)
- addFileInput((string $name, array $options)
- addCss(string $classes)
- setSubmitValue((string $name, array $options)

#### Input options
inputs need to be named. If they are to be associated with an entity property, this name needs to be the same as the associated property.

Each input has their specific available options, like placeholders, default values etc, here are some of them.
- sanitize : if false the input is not sanitized, else it is by default
- hash : if true and it is a password type then it is hashed, else it's false by default
- entity: if set and false then the input is not mapped with an entity property (it can be retrieved with Form's getData() method)
- value : an input's default value if compatible
- modifyIfEmpty : if set and false, doesn't modify a field if the input is left empty, else it does by default (if the input is mapped)
- class : add classes to the input
- targetField : for association types entity properties, gives the ability to select a custom index to search an entity in the database. Defaults to ID.
- placeholder : Input placeholder
- label : Input label
- wrapperClass : Input's wrapper div class
- dataAttributes : can set data attributes

#### Rendering a form
To render a form, it needs to be passed with the method renderForm(), this can be passed to twig or even as an AJAX call
```php
 return $this->render('/form_page.html.twig',[
    'form' => $form->renderForm()
]);
```
this can then be rendered easily :
```twig
{{ form.render|raw }}
```

The form can be used as AJAX data, by using the form.data (which gives raw data about the form and the inputs) instead of form.render (which renders the form, with the html already generated)

### Email
#### Configurating the emailing
If used, the email settings need to be configurated inside **/config/email.json**

**Defaults**
- email : sender email address
- name : sender name
- timezone : actual timezone

**phpmailer**
- username : authentication for the smtp service
- password : authentication for the smtp service
- host : SMTP hostname
- port : SMTP host port
- smtpAuthentication : if authentication is needed or not for the SMTP
- SMTPSecure : Security type

Example :

```json
{
    "default" :
    {
        "email": "$_ENV['EMAIL_USERNAME']",
        "name": "domain.com",
        "timezone": "Europe/Paris"
    },
    "phpmailer" :
    {
        "username": "$_ENV['EMAIL_USERNAME']",
        "password": "$_ENV['EMAIL_PASSWORD']",
        "host": "smtp.gmail.com",
        "port": "587",
        "smtpAuthentication": true,
        "SMTPSecure": "TLS"
    }
}
```

#### Usable methods
- addReceiver(string $reveicer) : set receiver address of the email
- subject(string $subject) : set the subject of the email
- sender(string $email, string $name) : change the sender email and name
- addRepleyTo(string $email) : adds a reply-to address
- setContent(string $content) : set the html content of the email
- setRender(string $template, array $parameters = []) sets a twig template render with optional parameters like for controller renders but for an email
- send() : send the email

### Commands
The framework includes an extensible command system, with some base commands that can be used to easily generate new entities or controllers for example. To use a command you need to call the main managing script and  precise the command as well as the necessary options or arguments like this :

```sh
php core/CommandManager.php CommandName --optionName argumentName=argumentValue
```

#### Base commands
Here are some commands that are provided with the framework :
- CreateEntity : Creates an entity
    - alias : create:e
    - options :
        - newtable : creates a migration file of the entity, ready to be inserted into the database
- CreateController : Creates a controller with a CRUD boilerplate
    - alias : create:c
    - arguments :
        - subfolder : Specifies a subfolder inside Controller in which to insert the new controller.
    - options :
        - setroute : Specifies that a new route has to be created in the routes config file in conjunction with the controller
- ExecuteMigration : migrates and adds a new table from a new entity to the database
    - alias : e:m
    - options :
        - latest : Indicates that the latest migration file should be exexuted

#### Configure and create commands
the command configuration is done inside **/config/commands.json**, active commands need to be added in this file with a name and the namespace of the command, like this :

```json
{
    "HelloWorld":
    {
        "name": "HelloWorld",
        "class": "Core\\commands\\HelloWorldCommand"
    }
}
```
Commands need to be extended from the Command class.
each command needs to be configured inside the **configure()** method, this is where the name of the command, the alias, the options, the arguments and the description are added.
The actual command execution code has to be put inside an **execute()** method, here is an example :
```php
<?php

namespace Core\commands;

class HelloWorldCommand extends Command
{
    public function configure()
    {
        $this->setName('HelloWorld')
            ->setAlias('hlw')
            ->setDescription('writes Hello World')
            ->addArgument('argumentName', 'argument description')
            ->addOption('optionName', 'option description');
    }

    public function execute()
    {
        echo "Hello World" . PHP_EOL;
    }
}
```

### Helper classes and methods
Several classes and methods can be used to help with different tasks, they can mainly be found in the Core\utils namespace :
#### Paginator
There are two methods :
- paginateArray(array $content, int $currentPage, int $limit): ?array
    - It is used to paginate an already queried array of items. This method doesn't touch the database. It returns the items that should be kept for the current page.
- paginate(Repository $repository, int $currentPage, int $limit, string $column = null, string $order = null, string $row = null, string $criteria = null): ?array
    - It queries the database based on the current page and the number of items in the tabl. Then it returns the number of pages, the current page and the kept items for the current page

#### StringUtils
They are static funtions that can help with strings :
- normalizeForComparison(...$strings): array
    - it returns all the passed strings as lowercase strings inside an array
- changeTypeFromValue(string $string)
    - it is used to change the type of a string that could be another type, for example a string with "true" or "false" or an int.
- slugify(string $str)
    - slugifies a string

#### JsonParser
- parseFile(string $path)
    - It is used to parse json files into PHP arrays. It is used extensively to parse the config files.

#### ClassUtils
- getClassNameFromObject($object)
    - gets the class name from an instantiated object

## License

MIT
