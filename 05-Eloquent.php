<?php
/*
|--------------------------------------------------------------------------
| ORM: Object Relational Mapping
|--------------------------------------------------------------------------
|
| 1. Model with Migration: php artisan make:model User -migration/-m , --all/-a, --policy, --factory, --controller, 
|    --controller --resource, -mcr (migration, resource controller with route model binding), -mcr --api, -mcr -R, -fs
|  (factory and seeder), -crR(resource controller, request), -mfcs
| 2. Overview of the Model: php artisan model:show User
| 3. "Composite" primary keys are not supported by Eloquent models. 
| 4. php artisan model:show User
*/

use Illuminate\Database\Eloquent\Model;

//Explicitly Define in Model to override default values
protected $table = 'users';
protected $primaryKey = 'users_id';
public $incrementing = false;
protected $keyType = 'string';
public $timestamps = false;
protected $dateFormat = 'U';
const CREATED_AT = 'creation_date';
const UPDATED_AT = 'updated_date';
protected $connection = 'sqlite';
protected $attributes = [
    'name' => 'Abdul Alim',
    'delayed' => false,
];

//Retrieving Model
use App\Models\User;
//Return Multiple Collection
$users = User::all();
$user1 = User::where('id', '>', 1)->orderBy('id')->get();

//Retrieving Subset of Model
use Illuminate\Database\Eloquent\Collection;
User::chunk(200, function (Collection $users) {
    foreach ($users as $user) {
    }
});
User::where('is_admin', true)
    ->chunkById(200, function (Collection $users) {
        $users->each->update(['is_admin' => false]);
}, $column = 'id');
//Use Lazy Loading: Load what needs (In Details)

//Refreshing Model
$userFresh = $user1->fresh();
$user1->id = '2';
$user1->refresh()

//Advanced SubQueries
return Assignment::addSelect(['assignment' => User::select('id')
    ->whereColumn('assignment_id', 'assignments.id')
    ->orderByDesc('created_at')
    ->limit(1)
])->get();

//Single Model: find(), first(), firstWhere(), findOr(1, function(){}), firstOr(), findOrFail(), firstOrFail()
$user1 = User::firstOrCreate(
    ['name' => 'New user'],
    ['delayed' => 1, 'arrival_time' => '11:30']
); //firstOrNew()

//Aggregate Methods: count(), sum(), max(), min(), avg()

//Inserting Models
public function store(Request $request): RedirectResponse{
    $user = new User;
    $user->name = $request->name;
    $user->save(); //Time Stamps will automatically be saved
    return redirect('/profile');
}

//Insert Using Single Instance
$user = User::create(['name' => 'New user']);//Must use mass assignment $fillable for the name to work.
protected $guarded = []; //Means all are $fillable or miss assignable

//Updating Models
$user = User::find(1);
$user->name = 'New User';
$user->save();

User::where('active', 1)->update(['delayed' => 1]);

/*
|--------------------------------------------------------------------------
| Examining Attribute Changes
|--------------------------------------------------------------------------
|
| 1. isDirty(): If any attribute changed when retrieved like send email when password changed. $task->isDirty()
| 2. isClean(): No change made to the model
| 3. wasChanged(): If specific attribute changed at last request or save. $task->wasChanged('status')
| 3. getOriginal(): Attribute value before Update. Like: $previousPrice = $product->getOriginal('price');
*/

//Mass Assignment Exception Threw in App Service Provider
public function boot(): void
{
    Model::preventSilentlyDiscardingAttributes($this->app->isLocal());
}

//Upsert: Update or Create if not Exist
$user = User::updateOrCreate(
    ['name' => 'A', 'destination' => 'Dhaka'],
    ['price' => 99]
); //If name A to Dhaka exists, update the price

//Multiple upsert in a single query: User::upsert()

//Deleting Models
$user = User::find(1);
$user->delete();
User::truncate();

//Deleting using Primary Key
User::destroy(1);
User::destroy(1, 2, 3);
User::destroy([1, 2, 3]);
User::destroy(collect([1, 2, 3]));

//If we do Route Model Binding and want slug rather than ID
public function getRouteKeyName(): string{
    return slug;
}

/*
| Query Scopes:
| 1. Global Scopes: php artisan:make scope  UserScope
| 2. orWhere: local scop chaining
*/
public function apply(Builder $builder, Model $model): void{
    $builder->where();
}
//Add in Model
protected static function booted(): void{
    static::addGlobalScope(new UserScope);
}
//Can Define Scope in addGlobalScope directly: Anonymous global scope
User::all(); //with scope
User::withOutGlobalScope(UserScope::class)->get();
User::withoutGlobalScope('user')->get();
User::withoutGlobalScopes()->get(); //Remove all scopes, or can be array

//Local Scopes
public function scopeLatestUser(Builder $query): void{
    $query->where();
}
public function scopePopularUser(Builder $query, string $votes): void{
    $query->where();
}
$users = User::latestUser()-get();
$users = User::latestUser()->orWhere->popularUsers('vote')-get();

/*
| Soft Deletes:
| 1. contains deleted_at attribute
| 2. In Model: use softDeletes;
| 3. In Migration: $table->softDeletes() or dropSoftDeletes()
| 4. withTrashed(): force query to include softDeleted models
*/
if($user->trashed()){
    $user->restore();
}
$user->withTrashed()->where()->restore();
$user->onlyTrashed()->where()->get();
$user->comments()->restore();
$user->forceDelete(); //permanently delete

/*
|--------------------------------------------------------------------------
| Observer
|--------------------------------------------------------------------------
|
| 1. Observe if a model created, updated, deleted and perform operations if it happens 
| 2. Update Related Data, Sending Notifications, Cache, Event Handling
| 3. php artisan make:observer UserObserver --model=User 
| 4. Assign observer in AppServiceProvider
| 5. We can use isDirty() on updating method and wasChanged() on updated method
| 6. withoutEvents(): muting events for a certain action
| 7. deleteQuietly(), forceDeleteQuietly(), restoreQuietly(), saveQuietly() : No event for a Model
*/
public function boot(): void{
    User::observe(UserObserver::class); //or,
    User::class => [UserObserver::class],
}
class UserObserver{

    //Operation after Created
    public $afterCommit = true;
}

//Eloquent 20 Tips//

// 1. Increment Decrement
$article = Article::find($id)->increment('read_count');
$article = Article::find($id)->increment('read_count', 10);
$product = Product::find($id)->decreament('stock');

//2: XorY
$user = User::findOrFail($id);
$user = User::firstOrCreate(['email' => $email]);

//Model Boot Method
public static function boot(){
    parent::boot();
    self::creating(function($model)){
        $model->uuid = (string)Uuid::generate();

    //always order by id
    static::addGlobalScope('students', function(Builder $builder){
        $builder->orderBy('id', 'asc');
    });
 }}

//Relationship with Condition
return $this->hasMany(ClassRepresentative::class)->where('is_cr', true)->orderBy('id');

//Model Properties
protected $table, $fillable, $dates, $appends, $primaryKey, $perPage;
public $incrementing, $timestamps;
const CREATED_AT, UPDATED_AT;

//Multiple Entries
$users = User::find([1, 2]);

//whereField. Assume, we have an attribute isCr
$users = User::whereIsCr(1)->get();
//whereDate(), whereDay(), whereMonth(), whereYear()

//Order By Relationship
public function latestNotice(){
    return $this->hasOne(Notice::class)->latest();
}
$notices = Admin::with('latestNotice')->get()->sortByDesc(latestPost.created_at);

//End of If-else, use when
$query = User::query();
$query->when(request('role', false), function($q, $role){
    return $q->where('rol_id', $role);
});
$authors = $query->get();

//If Model Empty- typically throw error. We can use Default Value
{{ $post->author->name ?? '' }} //or,
public function author(){
    return $this->belongsTo('User')->withDefault();
    //withDefault(['name' => 'Dummy Name'])
}

//Raw Query: whereRaw(), havingRaw(), orderByRaw()

//Duplicate a row
$user = User::find(1);
$newUser = $user->replicate();
$newUser->save();

//Chunking for big tables
User::chunk(100, function($users){
    foreach($users as $user){}
    //first retrieve 100 data, process it, then again retrieve 100 data if needs
});

//override updated_at
$user->update_at = '';
$user->save(['timestamps' => false]);

//update()
$result = $users->whereNull('name')->update(['name' => 'no name']);

//Complex Eloquent Query
$q->where(function ($query){
    $query->where('gender', 'male')->where('age', '>=', 18);
})->orWhere(function ($query){
    $query->where('gender', 'female')->where('age', '>=', 65);
});

//when performing some aggregation on data like max, min, avg- Grouping should be done on database level, not in php to get better performance and less model, less memory

//Best Way to check Empty Collection: isEmpty(), isNotEmpty()
//Refactoring to Collections//

//map(): Going through each collection and transform into another
const departments = ['communication science', 'information technology'];
return collect(self::departments)->map(function ($department){ return Str::slug($department)})->toArray();

//shortcut way- array function
return collect(self::departments)
    ->map(fn ($department) => Str::slug($department))
    ->toArray();

//two level each instead of foreach
$tags = Tag::factory(10)->create();
User::factory(20)->create()->each(function($user) use($tags){
    Listing::factory(rand(1, 4))->create([
        'user_id' => $user->id
    ])->each(function($listing) use($tags){
        $listing->tags()->attach($tags->random(2));
    });
});

//Collection Pipelining (chaining)
$result = $collection
    ->filter(function ($item) {
        // Filter only even numbers
        return $item % 2 === 0;
    })
    ->map(function ($item) {
        // Map to square each even number
        return $item * $item;
    });

//Higher Order Messaging: directly accessing methods and properties of collection items using the arrow (->) notation
// Before
$employees->reject(function($employee) {
    return $employee->retired;
})->each(function($employee){
    $employee->sendPayment();
});
 
// After using Higher Order Messaging for Collections
$employees->reject->retired->each->sendPayment();
$totalAmount = $orders->sum->amount;

//Serialization

