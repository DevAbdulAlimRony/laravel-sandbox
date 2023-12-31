<?php
/*
|--------------------------------------------------------------------------
| Resource Controller: Create, Read, Update, Delete
|--------------------------------------------------------------------------
|
| 1. php artisan make:controller UserController --resource
| 2. php artisan make:controller UserController --model=User --resource
| 3. Additional Route must be before the Resource Route
*/

//Resource Route
Route::resource('users', UserController::class);
Route::resource('users', UserController::class)->withTrashed(); //Soft Delete
Route::resource('users', UserController::class)->only(['index', 'show']);
Route::resource('users', UserController::class)->except(['index', 'show']);

//Nested Resource
Route::resource('users.courses', UserCourseController::class); //url: users/courses
Route::resource('users.courses', UserCourseController::class)-shallow(); //url will be: /courses

//Modify Default Resource Route
Route::resource('users', UserController::class)->names(['create' => 'users.build']);
Route::resource('users', UserController::class)->parameters(['users' => 'user_view']); // users/user_view
Route::resource('users.courses', UserCourseController::class)->scoped(['course' => 'slug']); // users/user/courses/slug

//Customizing Default URL  in RouteServiceProvider boot method
Route::resourceVerbs(['create' => 'add', 'edit' => 'update']); //url: users.add

//Singleton Resource Route
Route::singleton('profile', ProfileController::class); //profile.show, profile.edit, profile.update
Route::singleton('profile', ProfileController::class)->creatable();
Route::singleton('profile', ProfileController::class)->destroyable();


//Database Pagination: Configure Tailwind Content
//Paginate Query Builder
return view('user.index', [ 'users' => DB::table('users')->paginate(15)]);
$users = DB::table('users')->simplePaginate(); //Next and Previous Just

//Paginate Eloquent
$user = User::paginate(15);
$user = User::paginate(15)->withQueryString(); //parameter in the link
$user = User::paginate(15)->fragment('user'); //Hash Fragment: #user in the link
$user = User::simplePaginate(15);
$user = User::cursorPaginate(15);
$users = User::where('age', '>', 22)->paginate($perPage = 5, $columns = ['*'], $pageName = 'users');
//Custom Pagination Manually
//Pagination URL
//Display: {{ $users->links() }}, {{ $users->onEachSide(5)->links() }}, {{ $paginator->links('view.name') }}
//paginator methods

//Invokable Controller/Single Action Controller: When a Method does complex function, we can separate this method in a controller
//php artisan make:controller ResultController --invokable
Route::post('/result', ResultController::class);
class ResultController extends Controller{
    public function __invoke(){}
}







