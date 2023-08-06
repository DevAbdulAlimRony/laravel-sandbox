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


/*
|--------------------------------------------------------------------------
| Layout Breeze
|--------------------------------------------------------------------------
|
| 1. Table must have email_verified_at col
| 2. Three Routing: Verification link, Page after Verification, Resend Verification Link
| 3. Illuminate\Foundation\Auth\User, Illuminate\Auth\Events\Verified, Illuminate\Auth\Events\Registered
*/
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
}
//Verification Link
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice'); //verification.notice must not be changed

//After Verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

//Resend Link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//Now, Protect any routes by middleware(['auth', 'verified'])
//Custom Message in AuthServiceProvider

/*
|--------------------------------------------------------------------------
| Socialite
|--------------------------------------------------------------------------
|
| 1. Social Login: Laravel supports facebook, twitter, linkedin, google, github, gitlab, bitbucket, slack
| 2. composer require laravel/socialite
*/
