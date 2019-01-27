# Installation


## 1. Prerequisites

-   PHP 5.4 or greater.
-   MySQL 5.0 or greater.
-   Web server (Apache2, IIS).
-   OS - Windows, Mac, \*nix

Note: for users without web hosting service or existing environment, packages like [XAMMP](http://www.apachefriends.org/en/index.html) or [WampServer](http://www.wampserver.com/en/) can help you get set up quickly.


## 2. Fresh Installation

### 2.1. Application Deployment to Server

-   Unzip the archived distribution to your webserver\'s document root. Or
-   If you don\'t have direct access to your document root or use hosting service, then transfer the extracted directory to your web server\'s document root using FTP or [WinSCP](http://sourceforge.net/projects/winscp/).
-   Copy /config/config.dist.php to /config/config.php and adjust the settings for your environment.
-   [Important!]{.important} The web server must have write access (0755) to /your-booked/tpl\_c and /your-booked/tpl [(want to know why?)](http://www.smarty.net/docs/en/variable.compile.dir.tpl)
    -   If using an FTP client, check read/write/execute for Owner and Group on /tpl, /tpl\_c, and /uploads
-   [Important!]{.important} Booked will not work if PHP [session.autostart](http://www.php.net/manual/en/session.configuration.php#ini.session.auto-start) is enabled. Ensure this setting is disabled.


### 2.2. Application Configuration

You can configure Booked to fit your environments and needs, or use the minimal default settings which should be enough for the application to work. We recommend you to change according to your specifics. Additional information on all configuration settings can be found in the application help page.

-   To configure the application, you can open /config/config.php and alter any settings accordingly. The admin email address can be set in the booked/config/config.php file of setting [\$conf\[\'settings\'\]\[\'admin.email\'\]]{.setting}.\
    When later register an account with admin email address the user will be given admin privilege.
-   In addition, to allow resource image uploads, the web-server must also have read/write access to your configurable uploads directory of [\$conf\[\'settings\'\]\[\'image.upload.directory\'\]]{.setting} in the config.php.
-   By default, Booked uses standard username/password for user authentication. Alternatively, you can use LDAP authentication.\
    See the plugins section of the application help page for more details.

Note: If you try to load the application at this time (eg. http://yourhostname/booked/Web/), you will probably get a white page. This is because there is no backend database configured yet. So continue on \...


### 2.3. Database Setup

You have 2 ways to set up your database for the application to work:


#### 2.3.1. Automatically

You must have the application configured correctly before running the automated install.

-   The automated database setup only supports MySQL at this time. To run the automated database setup, navigate to the /Web/install directory in a web browser and follow the on-screen instructions.
-   Note: Some may see directory permission issues displayed on the page. The web server must have write access to /your-booked/tpl\_c and /your-booked/tpl. If you cannot provide the required permission. Contact your web server administrator or hosting service to resolve or run the manual install.


#### 2.3.2. Manually

The packaged database scripts make assumptions about your desired database configuration and set default values. Please edit them to suit your environment before running. The files are located in `booked/database_schema/`.

Import the following sql files in the listed order (we recommend [phpMyAdmin](http://www.phpmyadmin.net/documentation/) for this):


##### On a remote host with no database creation privileges

If you are installing Booked on a remote host, please follow these steps. These steps assume you are using cPanel and have the ability to create databases via the cPanel tool and phpMyAdmin.

Adding the database and user

-   Select the MySQL Databases tool
-   Add a new user with username and password of your choice. This will be the database user and database password set in your Booked config file. **Please be aware that some hosts will prefix your database user name.**
-   Create a new database with whatever name you choose. This will be the name of the database in your Booked config file. \'bookedscheduler\' is the recommended database name. **Please be aware that some hosts will prefix your database name.**
-   Associate the new user with the new database, giving the user permission to SELECT, CREATE, UPDATE, INSERT and DELETE. Click the \'Add User to Db\' button.

Creating tables

-   Open phpMyAdmin.
-   Click on the database name that you just created in the left panel.
-   Click the SQL tab at the top of the page.
-   Import \"/database\_schema/create-schema.sql\" to bookedscheduler (or whatever database name was used in the creation process)
-   Import \"/database\_schema/create-data.sql\" to bookedscheduler (or whatever database name was used in the creation process)


##### If you have database creation privileges in MySQL

-   Open \"/database\_schema/full-install.sql\" and edit the database name and username/password to match your config.php database values
-   Run or import \"/database\_schema/full-install.sql\"
-   Optionally - run/import \"/database\_schema/testdata-utf8.sql\" to bookedscheduler (sample application data will be created with 2 users: admin/password and user/password). These users are available for testing your installation.

**You are done. Try to load the application at (eg. http://yourhostname/booked/Web/).**


### 2.4. Registering the Administrator Account

After the database has been set up you will need to register the account for your application administrator. Navigate to register.php register an account with email address set in \$conf\[\'settings\'\]\[\'admin.email\'\].


## 3. Upgrading


### 3.1. Upgrading from a previous version of Booked 2.x (or phpScheduleIt 2.x)

The steps for upgrading from a previous version of Booked are very similar to the steps described above in Application Deployment to Server.


#### 3.1.1. Recommended

The recommended approach is to backup your current Booked files, then upload the new files to the that same location. This prevents any old files from interfering with new ones.

After the new files are uploaded, copy your old config/config.php file to the config directory in the new version. Then run /Web/install/configure.php to bring your config file up to date.

If you have any uploaded resource images you will need to copy them from their old location to the new one.


#### 3.1.2. Alternative

An alternative upgrade approach is to overwrite the current Booked files with the new ones. If doing this, you must delete the contents of /tpl\_c. This approach will not allow you to roll back and will not clear out any obsolete files.


#### 3.1.3. Database

After the application files have been upgraded you will need to upgrade the database.


##### Automatically

The automatic database upgrade is exactly the same as the automatic database install. Please follow the instructions in the Automatic Database Setup section above.


##### Manually

The packaged database scripts make assumptions about your desired database configuration and set default values. Please edit them to suit your environment before running. The files are located in `booked/database_schema/upgrades`.

Depending on your current version, import the upgrade.sql file within each subdirectory to get to the current version (we recommend [phpMyAdmin](http://www.phpmyadmin.net/documentation/) for this).

For example, if you are running version 2.0 and the current version is 2.2 then you should run `booked/database_schema/upgrade/2.1/upgrade.sql` then `booked/database_schema/upgrade/2.2/upgrade.sql`.


### 3.2. Migrating from version 1.2

A migration from 1.2 to 2.0 is supported for MySQL only. This can be run after the 2.0 installation. To run the migration open /Web/install/migrate.php directory in a web browser and follow the on-screen instructions.


## 4. Getting Started


### 4.1. The First Login

There are 2 main types of accounts, they are admin and user account.

-   If you imported a sample application data, you now can use admin/password and user/password to login and make changes or addition via the application.
-   If not, **you will need to register an account with your configured admin email address**. The admin email address can be set in the booked/config/config.php file of setting [\$conf\[\'settings\'\]\[\'admin.email\'\]]{.setting}. Other self registration accounts are defaulted to normal users.\
    After registration you will be logged in automatically. [Important!]{style="color:red"} At this time, it is recommended to change your password.
-   For LDAP authentication please login with your LDAP username/password.


### 4.2. Log Files

Booked logs multiple levels of information categorized into either application or database logs. To do this, the log4php library is used. By default, logging is turned OFF.

-   First, rename /config/log4php.config.dist.xml to /config/log4php.config.xml
-   To allow application logging, the PHP account requires write access (0755) to your configured log directory.
-   Logging is configured in /config/log4php.config.xml
    -   For Windows, set the path to something similar to \<param name=\"file\" value=\"c:/temp/log\_%s.log\" /\>
    -   For Unix, set the path to something similar to \<param name=\"file\" value=\"/tmp/bookedscheduler/log\_%s.log\" /\>
-   Logging is controlled by changing the \<level\> of each \<logger\>
-   Levels used by Booked are OFF, DEBUG, ERROR. For normal operation, ERROR is appropriate. If trace logs are needed, DEBUG is appropriate.
-   To turn on application logging, change the \<level value=\"OFF\" /\> to an appropriate level for either the default or sql loggers. For example, \<level value=\"ERROR\" /\>
-   For more information on logging configuration, visit [log4php](http://logging.apache.org/log4php/)


## 5. Enabling Booked API

Booked has the option to expose a RESTful JSON API. This API can be leveraged for third party integration, automation or to develop client applications.


### 5.1. Prerequisites

-   PHP 5.3 or greater
-   To use \'friendly\' URLs, mod\_rewrite or URL rewriting must be enabled
-   Your web server must accept all verbs: GET, POST, PUT, DELETE


### 5.2. Configuration

-   Set \$conf\[\'settings\'\]\[\'api\'\]\[\'enabled\'\] = \'true\'; in your config file.
-   If you want friendly URL paths, mod\_rewrite or URL rewriting must be enabled. Note, this is not required in order to use the API.
-   If using mod\_rewrite and an Apache alias, ensure RewriteBase in /Web/Services/.htaccess is set to that alias root.


### 5.3. API Documentation

Auto-generated documentation for API usage can be found by browsing http://your\_booked\_url/Web/Services. This documentation describes each available service, indicates whether or not the service is available to unauthenticated users/administrators, and provides example requests/responses.


### 5.4. Consuming the API

If URL rewriting is being used, all services will be available from http://your\_booked\_url/Web/Services\
If not using URL rewriting, all services will be available from http://your\_booked\_url/Web/Services/index.php\
\
Certain services are only available to authenticated users or administrators. Secure services will require a session token and userid, which can be obtained from the Authentication service.


## 6. Support

(This section only applies to the original version of Booked Scheduler, not to this fork called "REPAIRS".)

Please post any questions or issues under the Booked 2 section of the forums on <http://php.brickhost.com/forums>. Here you may also find some answers to your questions previously asked by other people.

