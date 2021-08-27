
# license4net
This README was made by Nani-Games Developers to understand the structure of the framework easily.
This mvc framework was adapted by Nani-Games.

## Installation
1. Clone Repo
2. Install LAMP webserver and start it
3. Make sure .htaccess files are enabled ([tutorial](https://www.linode.com/docs/guides/how-to-set-up-htaccess-on-apache/))
4. Install SQL Structure
5. Remove Password Authentification from `.htaccess` Files if you want to deploy it live. You could alternatively change the path to `.htpasswd` to your own file in case you want to use own authentification for development
6. Update `/public/.htaccess`:
	- Change `RewriteBase /playground/nani-*/public` to your public folder path (e.g `/public`)
	- Save and in some cases restart apache service: `systemctl restart apache2`
7. Update `/app/config/config.php`:
	- Replace SQL data for `DB_USER` and `DB_PASS`. `DB_HOST` and `DB_NAME` should stay the same.
	- Update SMTP data if email has changed
	- Update `URL_ROOT` to the root directory of the framework (e.g `http://localhost/nani-framework` or if placed in root directory `http://localhost`)
8. Execute `composer install` in `/app`
9. You are done. Open `http://your-server.tld/your-path` and everything should run fine

## Configuration File
The config file includes some global variables which control certain functions and timings of the nani-framework. In some cases you may change them. Here is a short explanation of what the variables do:

### Database

> DB_HOST

Your database hostname (in most cases: `localhost`).

> DB_USER

Your SQL username (you can use `root` in production but we recommend to change it later for live deployment).

> DB_PASS

Your SQL secret to authenticate with SQL database. When password authentification is disabled (only recommended for production!!!) leave it emty.

> DB_NAME

The database name of nani-framework. In case you used the database structure linked above you can leave it like this.

### PHPMailer

> SMTP_SERVER

Your SMTP server address. Attention: Not every hosting service on this earth has this option enabled. In case authentification with SMTP server should fail contact hoster. SMTP server addresses are used to be like `smtp.your-domain.tld`.

> SMTP_USERNAME

Your SMTP username (usually your email address you want to use for sending emails).

> SMTP_REPLY_TO

The email address you want to be shown as `Reply To: ...`. If not desired: leave empty.

> SMTP_SECRET

The SMTP secret to authentificate at your SMTP service with the given SMTP username.

### App

> APP_AUTHOR

Your abbreviation as global variable for easy and fast use. Usually you can leave it like this.

> APP_NAME

App name should not get changed like `APP_AUTHOR`. It just gives the app a name.. obviously. Can be used for `<title></title>` tags or breadcrumbs.

> APP_ROOT

Absolute path to app folder. Will get set automatically.

### Tasks

> TASK_EXPIRATION_TIME

Time in days until a task will expire. Expired tasks can not be executed anymore and will throw a little error message. You can cancel tasks at any time, not matter if expired or not.

 ### Session

> INACTIVITY_TIME

Time in minutes until the user will get logged out automatically when idling.

### Url

> URL_ROOT

Root path of your url. Change it in case you change the deployment server. Or just for production.

### Standard User Settings

> STANDARD_PROFILE_PICTURE

Profile picture id of image that will be set as default

> STANDARD_PROFILE_WALLPAPER

Wallpaper id of image that will be set as default

> STANDARD_TWO_AUTH

Regulates whether two factor authentication should be enabled as default or not (use `active` for yes and `inactive` for no)

### Head Title

> TITLE_PREFIX

Defines a global prefix for `<title></title>` tags

### Two Factor Authentication

> TWO_AUTH_ACTIVE

Defines the string value for active 2auth settings

> TWO_AUTH_INACTIVE

Self explanatory

> TWO_AUTH_SECRET

Cryptographical secret code for google authenticator

> TWO_AUTH_APP_CONNECTED

Defines the string value for connected google auth setting

> TWO_AUTH_APP_DISCONNECTED

Defines the string value for disconnected google auth setting

## Useful tutorials

[How to use templates](https://gist.github.com/david-prv/433da5ef7f6c18a67c3d8bb013575017) - Work with `sendEmail()` function made with PHPMailer and use templates

## Credits

### Helpers
[Code with Dary](https://www.youtube.com/channel/UCkzGZ6ECGCBh0WK9bVUprtw) - Basic MVC Structure tutorial (<3)

### Third Party Services  
[EasyCaptchas](https://www.EasyCaptchas.com) - Simple Spam Prevention  
[PHPMailer](https://github.com/PHPMailer/PHPMailer) - Sending Confirmation Emails  
[GoogleAuthenticator](https://github.com/sonata-project/GoogleAuthenticator) - Two Factor Authentication
