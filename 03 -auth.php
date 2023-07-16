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
use Illuminate\http\request
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
