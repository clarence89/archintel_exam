#### Archintel Exam

##### How to Install
1. Docker
    1. cd ./project_root
    2. docker compose up

2. Manual
    1. MYSQL
        1. Download MYSQL
        2. Import "./project_root/db/seed/seed.sql" into database
    2. PHP Server (PHP must be installed)
        1. cd ./project_root/src/backend
        2. run "php -S 127.0.0.1:8000"
    3. Nuxt
        1. cd ./project_root/src/frontend
        2. run "npm install"
        3. run "npm run dev"


##### Credentials
* Administrator account
    - Username
        - administrator
    - Password
        - adminpassword

* Editor account
    - Username
        - editor
    - Password
        - iamaeditor

* Writer account
    - Username
        - writer
    - Password
        - iamawriter
