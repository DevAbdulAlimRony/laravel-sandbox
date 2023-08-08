<?php
/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
|
| 1. Route: as like as web.php
| 2. Resourceful Controller for API with Model: php artisan make:model ModelName --api
| 3. Just Controller: php artisan make:controller NameController --api
| 4. /api URI prefix is automatically applied. Can be modified in RouteServiceProvider
| 5. Postman: https://www.youtube.com/watch?v=VywxIQ2ZXw4&ab_channel=freeCodeCamp.org
| 6. Test API with Postman Automation dynamically
| 7. Controllers for api in Api folder is just to organize in a better way
| 8. newman, newman html reports
| 9. HTTP Status Codes Meaning: https://www.webfx.com/web-development/glossary/http-status-codes/
*/
//Resource Route
Route::apiResource('users', UserController::class);
Route::apiResource([StudentController::class, TeacherController::class]);

//Returning Data as Json automatically
return Category::all();

/*
|--------------------------------------------------------------------------
| Eloquent Resource
|--------------------------------------------------------------------------
|
| 1. Transform a Model or Model Collection into Json
| 2. One Model: php artisan make:resource UserResource
| 3. Collection of Model: php artisan make:resource User --collection or,
| 4. php artisan make:resource UserCollection
| 5. Key can be preserved
*/
class UserResource extends JsonResource{
    //if model name different
    public $collects = Student::class;

    //Data Wrapping: Custom key instad of data for converted json
    public static $wrap = 'user';

    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'name' => $this->name,

            //Relationships
            'post' => PostResource::collection($this->posts),
            //conditional Relationship
            'post' => PostResource::collection($this->whenLoad('posts')),
            'posts_count' => $this->whenCounted('posts'),
            'words_avg' => $this->whenAggregated('posts', 'words', 'avg'), //min, max, sum
            //whenPivotLoaded, whenPivotLoadedAs

            //Conditional Attribute
            'password' => $this->when($request->user()->isTeacher, 'password_value'),
            'password' => $this->when($request->user()->isStudent, function(){
                return 'password_value';
            }),
            'name' => $this->whenHas('name'),
            'name' => $this->whenNotNull($this->name),
            //mergeWhen() - merging multiple values conditionally

            //Including Metadata
            'links' => [],
        ];

    }

    //Return only metadata
    public function with(Request $request): array{}

    //Paginated Collection
    public function paginatedInformation($request, $paginated, $default){}

    //With Response
    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->header('X-Value', 'True');
    }
}
return new UserResource(User::findOrFail($id));
return new UserResource(User::paginate());
return UserResource::collection(findOrFail($id));
return new UserCollection(User::all());

//return data with some calculation at a end point
return User::select(['id', 'created_at'])->get()->map(function($user){
    $user->registered = $user->created_at->diffForHumans(now);
    return $user;
});

/*
|--------------------------------------------------------------------------
| Sanctum Authentication
|--------------------------------------------------------------------------
|
| 1. Authentication Package for SPA creating API token
| 2. composer require laravel/sanctum
| 3. php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
| 4. php artisan make:resource UserCollection
| 5. and migrate the database
| 6. Ignoring Dafault Migration in AppServiceProvider
*/


// API Documentation- OpenApi/Swagger, Beyondcode- Laravel api doc generator, Scribe

//Passport: OAUTH2 Authentication