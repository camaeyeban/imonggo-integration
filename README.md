# Imonggo-3dCart Integrator or I3 Integrator
**Description**: I3 Integrator is a software that aims to integrate Imonggo (a free online web-based Point of Sale system) with 3dCart (an eCommerce site).
It aims to provide easy and efficient synchronization of the user’s products, customers, invoices, and inventory levels between the user’s stores (Imonggo store and 3dCart store) over the internet.


### I3 Integrator Localhost Setup Guide
1. Download XAMPP (version: 5.6.8-0)
2. Install XAMPP (include: Apache and MySQL service packages)
3. Create a folder in xampp_directory\htdocs
......- In windows, the default xampp_directory is: C:\xampp
......- Name the folder whatever you want
4. Pull this git repository
5. Copy the contents of the pulled git repository and paste these inside the folder you created in step number 3
6. Create an [Imonggo account](http://www.imonggo.com/)
......- You may use its trial version
7. Sign up for a [3dCart developer account](http://devportal.3dcart.com)
......- Follow the Registration instructions provided at [3dCart REST API documentation](https://apirest.3dcart.com/Help#Registration) to create a developer account, create an application, and subscribe to the created application
8. Locate then edit authentication.php inside the folder you created in step 3
......- instructions for editing is inside authentication.php
......- provide the needed data in authentication.php by the data you will get from accomplishing step number 7
9. Create a database in XAMPP
......- you can create a database in the given url below:
............localhost/phpmyadmin
......- name the database imonggo_integration_db
10. Import the database named imonggo_integration_db.sql(imonggo_integration_db.sql) from the pulled git repository to the database you created in step number 9


Viola! You're all set!


Note: You can view the Imonggo Integration homepage by typing any of the url's below, in your browser
......localhost/folder_name_created_in_step_3/index.php
......localhost/folder_name_created_in_step_3