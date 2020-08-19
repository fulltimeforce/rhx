# How to run 

1. Clone the project:

```
git clone https://github.com/fulltimeforce/rhx.git 
```

or 

```
git@github.com:fulltimeforce/rhx.git 
```

2. Install dependencies

```
composer install
```

3. Rename the **.env.example** file to **.env** , add services keys (Google, AWS, etc) and set database info.

4. Generate the **App key** 

```
php artisan key:generate
```
5. Run migrations

```
php artisan migrate
```

6. Seed the database

```
php artisan db:seed
```

7. Serve the project

```
php artisan serve
```