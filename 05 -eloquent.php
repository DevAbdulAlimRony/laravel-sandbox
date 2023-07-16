<?php
/*
|--------------------------------------------------------------------------
| ORM: Object Relational Model
|--------------------------------------------------------------------------
|
| 1. Model with Migration: php artisan make:model User -migration/-m , --all, --policy, --factory, --controller, 
|    --controller --resource
| 2. Overview of the Model: php artisan model:show User
| 3. "Composite" primary keys are not supported by Eloquent models. 
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
//Use Lazy Loading: Load what needs

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
