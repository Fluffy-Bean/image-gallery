# OnlyLegs!
The only gallery made by a maned wolf.

## How to setup
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
```CREATE TABLE images ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, imagename VARCHAR(50) UNIQUE, alt VARCHAR(255), tags VARCHAR(255), alt VARCHAR(50), last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP );```
#### Users
```CREATE TABLE users ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, usernname VARCHAR(50) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, admin bool, last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP );```
#### Tokens
```CREATE TABLE tokens ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, code VARCHAR(50) NOT NULL, used BOOL, used_at VARCHAR(50) NOT NULL );```
#### Logs
```CREATE TABLE logs ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ipaddress VARCHAR(16) NOT NULL, action VARCHAR(255), time TIMESTAMP DEFAULT CURRENT_TIMESTAMP );```
#### Bans
```CREATE TABLE bans ( id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ipaddress VARCHAR(16) NOT NULL, reason VARCHAR(255), time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, length VARCHAR(255) NOT NULL, permanent BOOL NOT NULL ); ```


### Manifest
In the ```app/settings/manifest.json``` you have a list of infomation about your website. You must change ```user_name``` to your prefered name, ```is_testing``` to false (or anything else) as that is used for development and ```upload_max``` to your prefered file size max in MBs.

### Creating an account
For now, there is no automated way of doing this, so you will have to go into your database on a terminal and type the following command ```INSERT INTO tokens (code, used) VALUES('UserToken', False)```. You have now made a token that you can use to make an account with.

Head over to the Login section off the app and click the Need an account button, from there you can enter your own details. Once you get to the token section enter ```UserToken```. And with that, you have now set up your own image gallery!

## Usage
### Admin
As an admin, you can do things such as modifying other people's posts, reseting users passwords and checking logs for sussy behaviour. With that, use these tools with respect to others and don't abuse them.

If you trust someone enough, you can set them to a moderator through the settings > users > toggle admin. You can tell who is an admin by the green highlight to the left of their name.

### Images
Uploading images is as simple as choosing the image you want to upload, then clicking upload! Keep in mind that not all formats play well as this gallery uses Imagik to generate thumbnails and preview images, so images such as GIFs do not work as of now. Supported file formats include JPG, JPEG, PNG and WEBP.

You should also keep in mind the file size, by default images of 20MBs should be able to get uploaded. But if you run into issues, either raise the file size in the ```manifest.json``` or locate your ```php.ini``` on your webserver and raise the ```upload_max_filesize``` and ```post_max_size``` to a same or greater value.

## License
This project is under the GNU v3 License
