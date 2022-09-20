# OnlyLegs!
The only gallery made by a maned wolf.

## How to use
### Downloading & installing
#### Path
Download this project and move it into your website(s) folder. Usually under ```/var/www/html/``` on Linux.

#### Imagik
You will need to install the image-magik PHP plugin for thumbnail creation, on Ubuntu its as easy as ```apt install php-imagik```.

#### PHP
This project also requires PHP 8 and was made with Ubuntu 22.04 LTS in mind, so I reccommend running this gallery on such.

### Database setup
If you made it this far, congrats! We're not even close to done. Next you will need to setup your database. If you're running a seperate server for databases, that'll also work.

You first need to head over to ```app/server/conn.php``` and set the correct information, if you're using localhost, this should be the following details: 

- localhost
- (username)
- (password)
- Gallery

I recommend using a database name such as Gallery, but others should work just as well.

I also recommend not using root for this and setting up a user specifically for this, but I will not go through the process of making a such user here.

You will next need to setup the following 5 tables:

#### Images
```CREATE TABLE images ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, imagename VARCHAR(50) UNIQUE, alt VARCHAR(255), tags VARCHAR(255), alt VARCHAR(50), modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP );```
#### Users
```CREATE TABLE users ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, usernname VARCHAR(50) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, admin bool, modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP );```
#### Tokens
```CREATE TABLE tokens ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, code VARCHAR(50) NOT NULL, used BOOL, used_at VARCHAR(50) NOT NULL );```
#### Logs
```CREATE TABLE logs ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ipaddress VARCHAR(16) NOT NULL, action VARCHAR(255), time TIMESTAMP DEFAULT CURRENT_TIMESTAMP );```
#### Bans
```CREATE TABLE bans ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ipaddress VARCHAR(16) NOT NULL, reason VARCHAR(255), time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, length VARCHAR(255) NOT NULL, permanent BOOL NOT NULL ); ```

### Manifest
In the ```app/settings/manifest.json``` you have a list of infomation about your website. You must change ```user_name``` to your prefered name, ```is_testing``` to false as that is used for development and ```upload_max``` to your prefered file size max in MBs.

## License
This project is under the GNU v3 License
