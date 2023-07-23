<?php
/*
|--------------------------------------------------------------------------
| Layout Breeze
|--------------------------------------------------------------------------
|
| 1. Installation: composer require laravel/breeze --dev
| 2. Publish all authentication files: breeze:install
| 3. Compile Frontend assets: php artisan breeze:install blade, php artisan migrate, npm install, npm run dev
|    Dark Mode: php artisan breeze:install --dark
|    React or Vue with Inertia: php artisan breeze:install react/vue
|    Inertia Server Side Rendering: php artisan breeze:install vue -ssr
|    Typescript Support: Include --typescript
|    Also can Use with Next.Js as API
|    If we want bootstrap, we can use Laravel UI or Jetstrap
| 4. 
*/

//Retrieving Author Data
use Illuminate\Suuport\Facades\Auth;
$user = Auth::user();
$id = Auth::id();
//or,
use Illuminate\http\request;
class AuthController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
    }
}

//Determine if Authenticated on Auth Facade
if(Auth::check()){}

//Authentication Directives
@auth @endauth
@guest @endguest
@auth('admin') @endauth

/*
|--------------------------------------------------------------------------
| Middleware
|--------------------------------------------------------------------------
|
| 1. Inspecting and Filtering HTTP Request
| 2. php artisan make:middleware IsTokenValid (app/http/middleware) 
| 3. Task can be handled beforeMiddleware and afterMiddleware
| 4. Global Middleware: Run during every http request- app/http/kernel.php -> $middleware property -> Include class
| 5. Assigning aliases: app/http/kernel.php->$middlewareAliases[ 'token' => \App\Http\Middleware\IsTokenValid::class]
| 6. Middleware Groups: like web middleware that encrypt cookie, check session etc. We can make custom group with some middleware classes
| 7. Middleware Sorting: app/http/kernel -> $middlewareProperty -> write classes serially
| 8. Middleware Parameter: Like Check if User is teacher and also is a dean
| 9. Terminable Middleware: terminate() method, Do/Response something after passing the middleware. Register it and assign into kernel.php
*/
public function IsTokenValid(Request $request, Closure $next): Response{
    if($request->input('token') != 'Abdul Alim'){
        return redirect('/home');
    }
    return $next($request);
}
Route::get()->middleware(IsTokenInvalid::class);
Route::get()->middleware('token'); //from aliases
Route::get()->withoutMiddleware('token'); //When In a Middleware Group
Route::withoutMiddleware();
Route::get()->middleware([IsTokenInvalid::class, IsStudent::class]);