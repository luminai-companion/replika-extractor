﻿# Replika-Extractor
## Installation
### Dependencies
php ^7.2 or ^8.0
mysql database
### Install steps
Create a mysql database called *replika* and a user with SELECT, UPDATE and REPLACE permissions. Insert the replika.sql file in the database structure folder into it.
Copy contents of the webroot folder to a folder where it is accessible from the web. Now edit db.php on lines 105-108 and put in your db data. 
You're pretty much done!
Edit the auth.inc.php file to change or add the admin email address. This allows you to see navigation entries that are not fully implemented yet.
## Usage
Navigate to the index.php and login with your Replika credentials. The credentials don't get saved in the database or in a cookie. You get logged in directly to the Replika Server and the script gets an auth_token, which is stored in a cookie. All stored data is visible after logging in successfully. 
Please first click on "Get Memory" to access the data of your reps Memory. Information about the persons you saved are being stored in the database for censorship purpose.
Click on Get History to store your Chat History (currently back to 2022-07-01) in the database.
Click Get Diary to store your diary entries in the database.
Clicking Download History/Memory/Diary gives you options to download your data as RTF, CSV or SQL file either untouched or censored. The censorship replaces your first- and lastname, your replikas name and the names stored in your replikas memory with placeholders. Everything else is left untouched.
Clicking Get character models runs a script that downloads all character model files (.bin format) to the assets/ directory.
Clicking Get store models runs a script that downloads all models from the store (.bin format) to the assets/ directory. Be carefull! This command takes very long for it downloads several gigabyte to the server it runs on. Once a file is already downloaded it is skipped. So it is safe to rerun the script after an execution timeout.
After downloading the assets you can extract them with "3D Ripper DX" for further usage.

## Verion 0.1
- Added Emoji Support for RTF download
- fixed bug in reaction saving

### Todo
- Adding "delete my data" option 
- Adding "share with developers" option
- Adding download option for 3D room asset files
- Adding editorial option for stored chatlogs, diaries and memories, so entries can be edited or deleted

## Version 0.0

### Todo
- Adding "delete my data" option 
- Adding "share with developers" option
- Adding download option for 3D room asset files
- Adding editorial option for stored chatlogs, diaries and memories, so entries can be edited or deleted
- Fixing emojis on RTF download
