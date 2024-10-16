# Weather Application

**Website Link:** [http://18.138.248.60:8000/](http://18.138.248.60:8000/)

## Demo



[Video_Demo] [(link_demo)](https://youtu.be/udvlwIgco5g) <!-- Add your demo image link here -->

## Instructions to Run

### Step 1: Configure Environment

Change the following settings in your `.env` file to match your local machine:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
WEATHER_API_KEY=
```
### Step 2: Run the Application
Execute the following commands in order:
```
npm install
composer install
npm run build
php artisan migrate
```
Run simultaneously.
```
npm run dev
php artisan serve
```
Notes
 - Database: [Weather Database Repository](https://github.com/nntcuong/Weather_db)
 - To send daily weather notification emails to registered users, use the command:
```
php artisan users:sendmail

```


