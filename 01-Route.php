<?php
use Illuminate\Support\Facades\Route;
//Web middleware group provides session state and CSRF protection
//Cross-Site Request Forgery (CSRF) is an attack that forces authenticated users to submit a request to a Web application against which they are currently authenticated.
//Basic Route: Accept a URL and a Closure
Route::get('/', function () {
    return view('welcome');
});
Route::match(['get', 'post'], '/welcome', 'WelcomeController@index'); //any

//Fallback Route
Route::fallback(function () {});

/*
|--------------------------------------------------------------------------
| Route Artisan Commands
|--------------------------------------------------------------------------
|
| 1. php artisan route:list
| 2. Display with Route Middleware: php artisan route:list -v
| 3. php artisan route:list --path=api
| 4. php artisan route:list --except-vendor
| 5. php artisan route:list --only-vendor
| 6. php artisan route:cache - to deploy
| 7. php artisan route:clear - to clear cache
*/

//Redirect and View Routes
Route::redirect('/here', '/there', 404); //If 404 not defined, normally give 302 status code
Route::permanentRedirect('/here', '/there'); //301 Status Code
Route::view('/', 'welcome');


/*
|--------------------------------------------------------------------------
| HTTP Verbs
|--------------------------------------------------------------------------
|
| 1. Get: Request Data from the Server, Fetch Data
| 2. Post: Submit Data to the Server to Process like when we register
| 3. Put: Update a Resource. Replace the Entire Resource or Create another one
| 4. Patch: Partial Update
| 5. Delete: Removal of a Resource
| 6. Any: Respond to All Possible Methods
| 7. Options: It asks about communication options or capabilities available for specific URL: What can I do with
|    this URL?
| 8. Match: Specify an array of HTTP methods
| 9. We normally use Get and Post method, because HTML form, all browsers and server support them. Normally,
|    when we use API, then we can use other methods explicitly.
| 10. POST, PUT, PATCH, DELETE must define @csrf token in the form tag
*/

//Using Method from Controller Class
Route::get('/home', [UserController::class, "index"]);


// Route Parameters
Route::get('/posts/{post}/comments/{comment?}', function(Request $request, string $postId, string $commentId = null){
    return 'Post '.$postId;
})->where(['post' => '[A-Za-z]+', 'comment' => '.*']);
//comment? is an optional parameter
//Regular Expression Constraints: whereIn, whereNumber, whereAlphaNumeric, whereUuid
//Route::pattern('id', '[0-9]+') => Global constraints in Route Service Provider's boot method

//Encoded Forward Slash
Route::get('/search/{search}', function (string $search) {
    return $search;
})->where('search', '.*');


//Route Grouping
Route::middleware(['admin'])->group(function(){});
Route::controller(AdminController::class)->group(function(){});
Route::domain('{account}.example.com')->group(function () {}); //Sub Domain Routing
Route::prefix('admin')->group(function(){});
Route::name('admin.')->group(function(){}); //domain(), resource(), apiResource()
Route::middleware()->name()->prefix()->controller()->group(function(){
    
});
//Organizing Routes: Public Route, Private Route as middleware grouped
//If controllers in the separate folder, that can be called as namespace

Route::group(['middleware'=>'auth'], function(){

    Route::group(['middleware'=>'admin', 'prefix'=> 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function(){
        //Partial Resource Controller
        Route::resource('admin', AdminController::class)->except('edit');
        //->only()
        //Extra Method must be defined before the resource controller to work
    });

    Route::group(['middleware'=>'user', 'prefix'=> 'user', 'namespace' => 'User', 'as' => 'user.'], function(){
        //Partial Resource Controller
        Route::resource('admin', AdminController::class)->except('edit');
        //->only()
        //Extra Method must be defined before the resource controller to work
    });

}); //Route Parameter Validation

//Soft Delete
Route::get('/', function (){})->withTrashed();


/*
|--------------------------------------------------------------------------
| Route Model Binding
|--------------------------------------------------------------------------
|
| 1. Short Way to Find By ID. Typically Used in Edit or Show Method
| 2. Instead of Finding ID, Just Pass argument show(User $user), edit(User $user)
| 3. Explicit Binding in boot Method of Route Service Provider
| 4. We can conditionally make our own customized route model binding like find this id if condition
*/
//Implicit Binding
//automatically bind where variable name compares with model name
Route::get('users/{user}', function(User $user){}); //$user and {user} matches, withTrashed() for Soft Delete
Route::get('users/{user:slug}', function(User $user){return $user;}); //Rather than ID - slug. If Define in Model, don't need :slug here
Route::get('users/{user}/posts/{post:slug}', function(User $user, Post $post){}); //Gradually User, and the Post for relationship
Route::get('users/{user}/posts/{post:slug}', function(User $user, Post $post){})->scopBindings(); //Scope Child Bindings, withoutScopBindings()
Route::scopBindings()->group(function(){});
Route::get('users/{user}', [LocationController::class, 'show'])->missing(function (Request $request){
    return Redirect::route('error.index'); //if route model binding's model not found or missing
});
//Scoped bindings" in Laravel limit data retrieval to a specific context, for instance, getting posts that belong to a particular user. With ->scopeBindings(), Laravel only finds a Post that is related to the provided User, otherwise, it returns a 404 error. Without scoped bindings (without ->scopeBindings()), Laravel will fetch a Post based on its ID across all posts, regardless of the user it's related to. It broadens the search to the entire dataset, ignoring the user-post relationship.
//In Model
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->firstOrFail();
    }
//Route::model(), own binding resolution logic: Route::bind(), If we want to pass a column as a parameter rather than id , In model-  getRouteKeyName()

//Rate Limiting:Restrict amount of traffic for a given route/routes (RouteServiceProvider)
//If exceed, 429 status code

RateLimiter::for('global', function (Request $request){
    return Limit::perMinute(1000)->response(function (Request $request, array $headers){
        return response('Custom Message', 429, $headers);
    });
});

RateLimiter::for('uploads', function(Request $request){
    return $request->user()->isAdmin()
           ? Limit::none()->by($request->user()->id)
           : Limit::perMinute(100)->by($request(ip));
}); //by means per ip address or per user here

//Assign Rate Limiter to Route
Route::middleware(['throttle:uploads']);

//Accessing Current Route from Route Facade: Route:current(), Route:currentRouteName(), Route:currentRouteAction()






