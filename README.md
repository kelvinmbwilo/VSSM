# VVS
Vaccine Visibility System

# Steps to Install VVS

UBUNTU (LINUX) INSTALLATION

1. Start a terminal by pressing &quot; **Ctrl+Alt+T**&quot;.
2. Make sure you have the following software installed in your system: Instalation instruction can be found [here](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu)
  1. [GIT](https://git-scm.com/),
  2. [COMPOSER](https://getcomposer.org/)
  3. [PHP](https://php.net/)
  4. [MYSQL](https://www.mysql.com/) or SQLITE or PGSQL or SQLSRV database
  5. [APACHE](https://www.apache.org/)
3. To verify that you have them installed,
  1. Type &quot; **git --version**&quot; then press **ENTER** button
  2. Type &quot; **composer --version**&quot; then press **ENTER** button
  3. Type &quot; **php --version**&quot; then press **ENTER** button
  4. Type &quot; **mysql --version**&quot; then press **ENTER** button, for other type of database, replace **mysql** with the corresponding type of installation.
  5. Type &quot; **apache --version**&quot; then press **ENTER** button
4. Navigate to a directory that will contain the contents of VVS.
  1. Type &quot; **cd /var/www/html**&quot; then press **ENTER** button
5. Clone VVS project from Github by typing the following command:
  1. Type &quot; **git clone** [**https://github.com/kelvinmbwilo/VVS**](https://github.com/kelvinmbwilo/VVS)&quot; then press **ENTER** button
  2. The content of VVS will then be copied from Github to your current directory, wait for it to complete.
6. Navigate to VVS directory
  1. Type &quot; **cd VVS**&quot; then press enter
7. Give the read, write and execute access to a folder of VVS
  1. Type &quot; **sudo chmod -R 777 .**&quot; then press **ENTER** button
8. The next step is to install dependencies of VVS.
  1. Type &quot; **composer install**&quot; then press **ENTER** button.
  2. Type &quot; **composer update**&quot; then press **ENTER** button.
9. Create a database called &quot;VVS&quot;.
10. Change the username and password of a database from a file located at &quot; **VVS/config/database.php**&quot; in order to relate to your database configuration. Any text editor can be used to perform these changes. What is needed to be done is to replace the red texts (null) below with the corresponding values of the installed database configurations. The variables to change are:
  1. &#39;database&#39; =&gt; env(&#39;DB\_DATABASE&#39;, &#39;null&#39;),
  2. &#39;username&#39; =&gt; env(&#39;DB\_USERNAME&#39;, &#39;null&#39;),
  3. &#39;password&#39; =&gt; env(&#39;DB\_PASSWORD&#39;, &#39;null&#39;),
11. Under VVS directory, create the tables that will hold data of your system
  1. Type &quot; **php artisan migrate**&quot; then press **ENTER** button
12. Generate the key:
  1. Type &quot; **php artisan key:generate**&quot; then press **ENTER** button
13. Start using VVS
  1. Start your favorite browser
  2. Use this URL: **SERVER\_NAME** /VVS/public/index.php where **SERVER\_NAME** is the name of your server.

WINDOWS INSTALLATION

1. Install XAMPP or WAMP server
2. Make sure you have the following software installed in your system:
  1. [COMPOSER](https://getcomposer.org/)
  2. [WINRAR](http://www.win-rar.com/)
3. Download the ZIP folder of VVS project from the link: [**https://github.com/kelvinmbwilo/VVS**](https://github.com/kelvinmbwilo/VVS)
4. Unzip the contents of downloaded VVS files in XAMPP installation directory &quot; **xampp\htdocs\**&quot;
  1. For WAMP, unzip the files in &quot; **wamp\www\**&quot; directory
5. The name of the unzipped file will be &quot; **VVS-master**&quot;, rename it to &quot; **VVS**&quot;
6. Start XAMPP server, specifically APACHE module and MYSQL module.
7. Create a database called &quot;VVS&quot;.
8. Change the username and password of a database from a file located at &quot; **VVS/config/database.php**&quot; in order to relate to your database configuration. Any text editor can be used to perform these changes. What is needed to be done is to replace the red texts (null) below with the corresponding values of the installed database configurations. The variables to change are:
  1. &#39;database&#39; =&gt; env(&#39;DB\_DATABASE&#39;, &#39;null&#39;),
  2. &#39;username&#39; =&gt; env(&#39;DB\_USERNAME&#39;, &#39;null&#39;),
  3. &#39;password&#39; =&gt; env(&#39;DB\_PASSWORD&#39;, &#39;null&#39;),
9. Navigate to VVS directory
  1. Search for &quot; **cmd**&quot; or &quot; **Command Prompt**&quot; on a search field of a window then open it.
  2. Navigate to VVS folder. NOTE: a directory is changed by typing &quot;cd&quot; command which stands for _change directory_. Let&#39;s take a look at this example; The directory of VVS can be located at &quot; **C:\Program Files\xampp\htdocs\VVS**&quot;, now assume that you are in &quot; **C:\Users\Mimi**&quot; directory. How to navigate to reach a &quot;VVS&quot; directory from &quot;Mimi&quot; directory?
    1. First, go two steps back by typing &quot; **cd ..**&quot; twice, press **ENTER** button each time. This will take you to &quot; **C:\**&quot; directory
    2. Then navigate to VVS by typing &#39; **cd &quot;Program Files\xampp\htdocs\VVS\&quot;**&#39;, press **ENTER** button once you type the whole directory
10. The next step is to install dependencies of VVS.
  1. Type &quot; **composer install**&quot; then press **ENTER** button.
  2. Type &quot; **composer update**&quot; then press **ENTER** button.
11. Under VVS directory, create the tables that will hold data of your system
  1. Type &quot; **php artisan migrate**&quot; then press **ENTER** button
12. Generate the key:
  1. Type &quot; **php artisan key:generate**&quot; then press **ENTER** button
13. Start using VVS
  1. Start your favorite browser
  2. Use this URL: **SERVER\_NAME** /VVS/public/index.php where **SERVER\_NAME** is the name of your server.

MAC INSTALLATION

1. Install XAMPP or WAMP server you can use the installation instruction from [here](https://jason.pureconcepts.net/2015/10/install-apache-php-mysql-mac-os-x-el-capitan/)
2. Make sure you have the following software installed in your system:
  1. COMPOSER
3. Download the ZIP folder of VVS project from the link: [**https://github.com/kelvinmbwilo/VVS**](https://github.com/kelvinmbwilo/VVS)
4. Unzip the contents of downloaded VVS files in XAMPP installation directory &quot; **xampp\htdocs\**&quot;
  1. For WAMP, unzip the files in &quot; **wamp\www\**&quot; directory

1. The name of the unzipped file will be &quot; **VVS-master**&quot;, rename it to &quot; **VVS**&quot;
2. Start XAMPP server, specifically APACHE module and MYSQL module.
3. Create a database called &quot;VVS&quot;.
4. Change the username and password of a database from a file located at &quot; **VVS/config/database.php**&quot; in order to relate to your database configuration. Any text editor can be used to perform these changes. What is needed to be done is to replace the red texts (null) below with the corresponding values of the installed database configurations. The variables to change are:
  1. &#39;database&#39; =&gt; env(&#39;DB\_DATABASE&#39;, &#39;null&#39;),
  2. &#39;username&#39; =&gt; env(&#39;DB\_USERNAME&#39;, &#39;null&#39;),
  3. &#39;password&#39; =&gt; env(&#39;DB\_PASSWORD&#39;, &#39;null&#39;),
5. Navigate to VVS directory
  1. Search for &quot; **cmd**&quot; or &quot; **Command Prompt**&quot; on a search field of a window then open it.
  2. Navigate to VVS folder. NOTE: a directory is changed by typing &quot;cd&quot; command which stands for _change directory_. Let&#39;s take a look at this example; The directory of VVS can be located at &quot; **C:\Program Files\xampp\htdocs\VVS**&quot;, now assume that you are in &quot; **C:\Users\Mimi**&quot; directory. How to navigate to reach a &quot;VVS&quot; directory from &quot;Mimi&quot; directory?
    1. First, go two steps back by typing &quot; **cd ..**&quot; twice, press **ENTER** button each time. This will take you to &quot; **C:\**&quot; directory
    2. Then navigate to VVS by typing &#39; **cd &quot;Program Files\xampp\htdocs\VVS\&quot;**&#39;, press **ENTER** button once you type the whole directory
6. The next step is to install dependencies of VVS.
  1. Type &quot; **composer install**&quot; then press **ENTER** button.
  2. Type &quot; **composer update**&quot; then press **ENTER** button.
7. Under VVS directory, create the tables that will hold data of your system
  1. Type &quot; **php artisan migrate**&quot; then press **ENTER** button
8. Generate the key:
  1. Type &quot; **php artisan key:generate**&quot; then press **ENTER** button
9. Start using VVS
  1. Start your favorite browser
  2. Use this URL: **SERVER\_NAME** /VVS/public/index.php where **SERVER\_NAME** is the name of your server.
