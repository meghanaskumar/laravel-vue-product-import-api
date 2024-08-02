git clone https://github.com/meghanaskumar/cp360-admin-forms.git

navigate to  project folder

copy .env.example and create .env

uncomment the following from .env

# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

and set

DB_CONNECTION=mysql

composer install

php artisan key:generate

php artisan migrate // do the migrations

npm install && npm run build //building the frontend

php artisan serve // will point to a url where the application loads

This will point to http://127.0.0.1:8000 Will have Login and Register button

http://127.0.0.1:8000/register

http://127.0.0.1:8000/login

After registering a new user will point to User Home page where we can upload sample csv data and , imported products can be visible on the home page itself

sampledata.csv is attached within the repo
