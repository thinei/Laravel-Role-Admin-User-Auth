# Laravel-Role-Admin-User-Auth

One Paragraph of project description goes here

## Getting Started

Authorization can be tricky. Verifying who someone is and managing user permissions can be a whole can of worms.
Fortunately, Laravel has systems in place that make a tiered login system very easy to implement.

### Creating new project
_ { laravelAuth } is the application name that you need to define

```
composer create-project --prefer-dist laravel/laravel laravelAuth "5.5.*"
```

### re-creating a new key and completely invalidating the previous cache
```
php artisan key:generate
```

### Updating database connection in the application

## .env

_you need to update the following data,_

- DB_DATABASE  -> scheme name you want to use in the application
- DB_USERNAME  -> mysql username
- DB_PASSWORD  -> mysql password

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=admin_auth
DB_USERNAME=root
DB_PASSWORD=root

```

## database.php

- 'database' => env('DB_DATABASE', 'admin_auth'),
- 'username' => env('DB_USERNAME', 'root'),
- 'password' => env('DB_PASSWORD', 'root'),        
          
```
'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'admin_auth'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'root'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
      - --- --- --- --
      - --- --- --- --
      - --- --- --- --
    ],
```



### Updating table
_Before running the project, you need to do the following command for the reason of knowing the updated table data_

```
php artisan migrate 
```

### Running

```
php artisan serve
```



### Commands you must know
_The following commands are the commands that you needed to know_

```
Create Controller
  php artisan make:controller LoginController
 
Create new table 
	php artisan make:migration create_tasks_table 
** tasks ** is the name of table

Migrating the database
  php artisan migrate

Refresh after updating database fields or something
	php artisan migrate:fresh

Seeding the database data
  php artisan db:seed 
  
Create new middleware
  php artisan  make:middleware Authenticate
  
Clear cache, config, route, view
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear

Check the route list
  php artisan route:list
  
```

### Usage of Middleware
After creating new middleware,

```
-----------------------------------------------------
app/Http/Kernel.php
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Auth::class,

...

    ]
-----------------------------------------------------
Usage -->   $this->middleware('auth')

```



## Step By Step
__The following is the step by step implementation of the whole project.__


### ＊＊ Adding "auth"

To add authentication to a Laravel 5 app, all you need is one command:
```
$ php artisan make:auth
```

### ＊＊ Add a type column on the users table and check if a user has that type via custom middleware

1. __Add the types you want to the User model and a method to check if a user is an admin__
```
/* app/User.php */
const ADMIN_TYPE = 'admin';
const DEFAULT_TYPE = 'default';
public function isAdmin()    {        
    return $this->type === self::ADMIN_TYPE;    
}
```

2. __Add the type column to the migration that created your users table__

```
/* database/migrations/2019_10_30_000000_create_users_table.php */
$table->string('type')->default('default');
```

3. __Add the type column to the migration that created your users table__

```
/* app/Http/Controllers/Auth/RegisterController.php */
protected function create(array $data)    {        
    return User::create([            
        'name' => $data['name'],
        'email' => $data['email'],            
        'password' => bcrypt($data['password']),            
        'type' => User::DEFAULT_TYPE,        
    ]);    
}
```

4. __Create a custom middleware file to check if a user is an admin. Generate this file using php artisan make:middleware IsAdmin__

```
<?php
namespace App\Http\Middleware;
use Closure;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->isAdmin()) {
            return $next($request);
        }
        return redirect('home');
    }
}
```

5. __Register the middleware you just created__

```
/* app/Http/Kernel.php */
'is_admin' => \App\Http\Middleware\IsAdmin::class,
```

6. __Add some routes that invoke the middleware__

```
/* routes/web.php */
Route::view('/', 'welcome');
Auth::routes();
Route::get('/home', 'HomeController@index')    
    ->name('home');
Route::get('/admin', 'AdminController@admin')    
    ->middleware('is_admin')    
    ->name('admin');
```

7. __Create an admin controller with php artisan make:controller AdminController. This controller returns the dashboard for whatever view you want your admin to see__

```
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function admin()
    {
        return view('admin');
    }
}
```


_Now if you visit /admin and you’re not logged in or logged in as an administrator you won’t be able to access the page. In order to create an admin user you can use the tinker artisan comman_:

```
$ php artisan tinker
>>> use App\User;
>>>User::where('email', 'admin@gmail.com')->update(['type' => 'admin']);
```

