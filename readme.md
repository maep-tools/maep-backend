<p align="center">
    <img width="460" height="300" src="https://maep-tools.github.io/landing-page/assets/img/theme/Vector.svg">
</p>
MAEP is a collection of tools for electrical engineers for analysis and electrical planning model.  
MAEP web is a tool for manage the models of MAEP.

### MAEP Backend System
Our backend system was build using Laravel. Laravel is a web application framework with expressive, elegant syntax. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects.

### Deployment
If you need to deploy this please check this documentation. https://laravel.com/docs/5.5/deployment

### Seeds
The application comes with preconfigured registers. If you need execute this please insert this command:

`php artisan db:seed`

If you need more information:
https://laravel.com/docs/5.5/seeding


### Permissions
Es necesario que el backend cuente con todos los permisos en la carpeta storage y en la carpeta bootstrap para ello ejecutamos:

`chmod -R 777 ./storage ./bootstrap`

### Instalación de Librerías python
Es necesario que gurobi se encuentre instalado para ello debemos instalar:

`conda install -c gurobi gurobi`

Muy importante configurar la licencia de gurobi.Para más información:
http://www.gurobi.com/documentation/8.0/quickstart_mac/obtaining_a_gurobi_license.html

Ejecutamos el siguiente comando para configurar el resto de librerías.

`pip3 install numpy scipy pyomo progressbar`

Es conveniente descargar MAEP CORE en el servidor y verificar que esté ejecutando correctamente.






### License
MIT License

Copyright (c) 2018 MAEP

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
