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

    - images
    - users
    - tokens
    - logs
    - bans

## License
This project is under the GNU v3 License
