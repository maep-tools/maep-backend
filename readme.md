<p align="center">
    <img width="460" height="300" src="https://maep-tools.github.io/landing-page/assets/img/theme/Vector.svg">
</p>
MAEP is a collection of tools for electrical engineers for analysis and electrical planning model.  
MAEP web is a tool for manage the models of MAEP.

### MAEP Backend System
Our backend system was build using Laravel. Laravel is a web application framework with expressive, elegant syntax. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects.
You need to config the .env file that contains the environment variables of the configurations of the system (Databases, email, queue configuration)

It´s important to know the installation process of a Laravel app.

### Deployment
If you need to deploy this please check this documentation. https://laravel.com/docs/5.5/deployment


### Permissions
Es necesario que el backend cuente con todos los permisos en la carpeta storage y en la carpeta bootstrap para ello ejecutamos:

`chmod -R 777 ./storage ./bootstrap`

### Python Libraries
Gurobi needs to be installed in your server:

`conda install -c gurobi gurobi`

You need to have a gurobi license.
http://www.gurobi.com/documentation/8.0/quickstart_mac/obtaining_a_gurobi_license.html

Exec this command in your command line for install the python packages.

`pip3 install numpy scipy pyomo progressbar`

### Migrations
You need to create a database called laracog in a mysql database.
This command will create the tables in the database.
Execute:
`php artisan migrate`

For more information about migrations:
https://laravel.com/docs/5.5/migrations


### Seeds
The application comes with preconfigured registers. If you need execute this please insert this command:

`php artisan db:seed`

If you need more information:
https://laravel.com/docs/5.5/seeding



### Workers
Change the number of Workers in the supervisord.conf
```
[program:queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/maep_back/artisan --timeout=0 queue:work
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stderr_logfile=/var/www/maep_back/laraqueue.supervisord_out.log
stdout_logfile=/var/www/maep_back/laraqueue.supervisord_out.log
```

### NodeJS realtime features:
If you want realtime features in the software:

In the socket folder:
```
npm install
pm2 start main.js
pm2 save
```

You need also supervisor for supervise the queues.
http://supervisord.org/running.html

If you are in a development environment you can run in the path of the folder:
```
php artisan queue:work
```


### License
MIT License

Copyright (c) 2018 MAEP

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
