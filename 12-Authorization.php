<?php
/*
|--------------------------------------------------------------------------
| Though User is authenticated, he has not all permissions
| Gates (like routes) and Policies (like controllers)
| Define Gate in boot() of AuthServiceProvider
| Policy Make- php artisan make:policy CrPolicy
| Policy with create, view, update, delete methods- php artisan make:policy CrPolicy --Model=Student
--------------------------------------------------------------------------
*/

//Define or Create Gate
Gate::define('gate-name', function(Student $student, Role $role){
    //condition here
});
Gate::define('assign-group', [StudentsGroupPolicy::class, 'create']);
Gate::check();
//Gate::before, Gate::after() - like ifAdmin can do anything with students authorized data

//Gate Response
Gate::define('assign-group', function(Student $student, Role $role){
    return $student->isCr ? Response::allow() : Response::deny('Only Class Representatives can assign group members');
    //denyWithStatus(404), denyAsNotFound()
});
$response = Gate::inspect('assign-group'); //in controller
if($response->allowed()){} else {echo $response->message;}

//Check Authorization in Controller
if(! Gate::allows('assign-group', $group)){abort(403);}
if(Gate::forUser($notCr)->denies('assign-group', $group)){abort(403);}
Gate::any(['add-group', 'update-group'], $group); //Can Add and Update
Gate::none(['delete-group'], $group);
Gate::authorize(); //throw default or custom exception if not authorized

//Inline Authorization
Gate::allowIf(fn (Student $student) => $student->isCr());
Gate::denyIf(fn (Student $student) => $student->isNotCr());


/*
|--------------------------------------------------------------------------
| Policy Make- php artisan make:policy CrPolicy
| Policy with create, view, update, delete methods- php artisan make:policy CrPolicy --Model=Student
| If policies in Policy directory and name same as model with Policy suffix, no need to assign
--------------------------------------------------------------------------
*/
//Assign or Register Policy in AuthServiceProvider
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [Student::class => CrPolicy::class];
}
//Writing Policy: Add Conditions in the Policy Method like we did in define() and also before(), after()

//Guests Users: Not Authenticated, but authorized to view or something
class PostPolicy
{
    public function update(?User $user, Post $post): bool
    {
        return $user?->id === $post->user_id; //? means optional
    }
}

//Method from Model used in controller: can(),cannot() in controller - just use policy, don't need gate
//authorize('update')
class PostController extends Controller{
public function __construct()
{
    $this->authorizeResource(Post::class, 'post');
}} //authorize resource controller: Policy Method will be view, delete...

//Via Middleware
Route::get()->can('update');
Route::get()->middleware('can:update, App/Models/Post');

//Via BladeTemplates: @can(), @elsecan(), @endcan, @cannot, @elsecannot, @endcannot

