<div align="center">
    <img src="onlylegs.jpg" width="621px" align="center">
    <p></p>
</div>

# Only legs!
The only gallery made by a maned wolf

## Special thanks
- Carty: Kickstarting development and SQL/PHP development
- Jeetix: Helping patch holes in some features
- mrHDash, Verg, Fennec, Carty, Jeetix and everyone else for helping with early bug testing
- <a class='link' href="https://phosphoricons.com/">Phosphor</a> for providing nice SVG icons

## How to setup
### Downloading & installing
#### Path
Download this project and move it into your website(s) folder. Usually under ```/var/www/html/``` on Linux.

#### Imagik
You will need to install the image-magik PHP plugin for thumbnail creation, on Ubuntu its as easy as:

    apt install php-imagick


#### PHP and Nginx
This project also requires PHP 8.1 and was made with Ubuntu 22.04 LTS and Nginx in mind, so I reccommend running this gallery on such.

With Nginx, you may need to configure the ```/etc/nginx/sites-available/default.conf``` for the new version on PHP. You must find the allowed index list and add index.php as such:

    # Add index.php to the list if you are using PHP
    index index.php index.html index.htm index.nginx-debian.html;

Then you have to find the ```fastcgi-php``` configuration and uncomment the lines and update the php version to 8.1 and now the config should look like the following: 

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }

With that, you may need to increase the ```client_max_body_size``` in the ```nginx.conf```, which should be located under ```/etc/nginx/nginx.conf```. There make sure your ```http``` looks like this:

    http {
        client_body_buffer_size 16K;
        client_header_buffer_size 1k;
        client_max_body_size 32M;

Most important of them being ```client_max_body_size```.

### Database setup
If you made it this far, congrats! We're not even close to done. Next you will need to setup your database. If you're running a seperate server for databases, that'll also work.

#### Note:
If you run into errors with connecting to the database, you may need to install php-mysqli, on Ubuntu that command will be:

    apt install php-mysqli

You first need to head over to ```app/server/conn.php``` and set the correct information, if you're using localhost, this should be the following details: 

- localhost
- (username)
- (password)
- Gallery

I recommend using a database name such as Gallery, but others should work just as well.

I also recommend not using root for this and setting up a user specifically for this, but I will not go through the process of making a such user here.

You will next need to setup the following 5 tables:

#### Images
    CREATE TABLE images (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        imagename VARCHAR(50) UNIQUE, alt VARCHAR(255),
        tags VARCHAR(255),
        author VARCHAR(50),
        last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
#### Users
    CREATE TABLE users (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL, admin bool,
        last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
#### Tokens
    CREATE TABLE tokens (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        code VARCHAR(50) NOT NULL,
        used BOOL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
#### Logs
    CREATE TABLE logs (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        ipaddress VARCHAR(16) NOT NULL,
        action VARCHAR(255),
        time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
#### Bans
    CREATE TABLE bans (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        ipaddress VARCHAR(16) NOT NULL,
        reason VARCHAR(255),
        time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        length VARCHAR(255) NOT NULL,
        permanent BOOL NOT NULL
    );

### Manifest
In the ```app/settings/manifest.json``` you have a list of infomation about your website. You must change ```user_name``` to your prefered name, ```is_testing``` to false (or anything else) as that is used for development and ```upload_max``` to your prefered file size max in MBs.

### Images Folder
Since there is currently no automated install script for this gallery, you'll have to make your own folders to hold images in. To do that, go into the root directory of the gallery and type the following commands:

    mkdir images
    mkdir images/thumbnails
    mkdir images/previews

This'll make 3 new folders. That is where all the uploaded images will be held in. But before you go anywhere, you will need to ```chown``` the folders so Nginx can access the images within them, so do the following:

    chown www-data:www-data -R images

### Creating an account
For now, there is no automated way of doing this, so you will have to go into your database on a terminal and type the following command:

    INSERT INTO tokens (code, used) VALUES('UserToken', false) 

Head over to the Login section off the app and click the __Need an account__ button, from there you can enter your own details. Once you get to the token section enter __UserToken__. And with that, you have now set up your own image gallery!


## Usage
### Admin
As an admin, you can do things such as modifying other people's posts, reseting users passwords and checking logs for sussy behaviour. With that, use these tools with respect to others and don't abuse them.

If you trust someone enough, you can set them to a moderator through the settings > users > toggle admin. You can tell who is an admin by the green highlight to the left of their name.

### Images
Uploading images is as simple as choosing the image you want to upload, then clicking upload! Keep in mind that not all formats play well as this gallery uses Imagik to generate thumbnails and preview images, so images such as GIFs do not work as of now. Supported file formats include JPG, JPEG, PNG and WEBP.

You should also keep in mind the file size, by default images of 20MBs should be able to get uploaded. But if you run into issues, either raise the file size in the ```manifest.json``` or locate your ```php.ini``` on your webserver, usually under ```/etc/php/8.1/fpm/php.ini```, and modify ```upload_max_filesize```, then ```post_max_size``` to a same or greater value.

## License
This project is under the GNU v3 License
