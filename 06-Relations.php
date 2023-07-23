<?php
//One to One Relationship
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
class User extends Model
{
    public function mobile(): HasOne
    {
        return $this->hasOne(Mobile::class);
        return $this->hasOne(Mobile::class, 'foreign_key'); //Explicitly
        return $this->hasOne(Mobile::class, 'foreign_key', 'primary_key');
        //oldestOfMany(), latestOfMany(), ofMany(x, min/max)
    }
}
$mobile = User::find(1)->mobile;
//Inverse of the One to One
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Mobile extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
        //Explicitly as above
    }
}


//One to Many Relationship
use Illuminate\Database\Eloquent\Relations\HasMany;
class Post extends Model
{
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
        //Convert into HasOne: one()->ofMany()
    }
}
$comments = Post::find(1)->comments;
foreach ($comments as $comment) {}
//Inverse of One to Many
class Comment extends Model
{
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
Comment::find(1)->post->title;
$comment = Comment::where('comment_id', $post->id)->get();
$comment = Comment::whereBelongsTo($post)->get();


//If no relationship found, give default relation that is Null Object Pattern
return $this->belongsTo(User::class)->withDefault([
    'name' => 'Guest Author',
]);


//Many to Many Relationship
class User extends Model
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withPivot('active', 'created_by'); //Explicit  Pivot Extra attributes
        //Pivot Timestamp: ->withTimestamps();
        //Pivot Rename:  ->as('subscription')
        //orderByPivot(), wherePivot(), wherePivotIn(), wherePivotNotIn(), wherePivotBetween(), wherePivotNotNull()
    }
}
foreach (User::find(1)->roles as $role) {}
foreach ($user->roles as $role) {
    echo $role->pivot->created_at;
}

class Role extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}

//N + 1 Query Problem(We can Use N+1 Query Detector Package)
class Book extends Model
{
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
$books = Book::all();
foreach($books as $book){
    echo $book->author->name;
} 
//If we have 12 books, 1 query for finding all books, 12 queries for finding author for the books. Total 13 (N + 1) queries problem arrived

//Solve: Eager Loading- Just two queries
$books = Book::with('author')->get();
$books = Book::with(['author', 'publisher'])->get(); //Multiple Eager Loading
$books = Book::with('author.contacts')->get(); //Nested: Contacts belongs to Author and Author Belongs to Book
$books = Book::with(['author' => ['contacts', 'publishers']]); //Nested Multiple
$books = Book::with('author:id, name, book_id');

$books = Book::with(['author' => function(Builder $query){
    $query->where('price', '>', '300')->orderBy('price', 'desc');
}])->get(); //Conditional Eager Loading

$books = Book::withWhereHas('author', function ($query) {
    $query->where('featured', true);
})->get(); //Check the Relationship and Eager Load

//Specify Eager Loading in the Model
class Book extends Model
{
    protected $with = ['author']; //When call Book, always return author also
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
$books = Book::without('author')->get(); //Break always with
$books = Book::withOnly('genre')->get(); //author will not be here


//Lazy Eager Loading(Eager Load When Needs)
$books = Book::all();
if ($someCondition) {
    $books->load('author', 'publisher');
}
$book->loadMissing('author'); //Load when It has not loaded already

/*
|--------------------------------------------------------------------------
| Polymorphic Relation
|--------------------------------------------------------------------------
|
| 1. Same Model belongs to Multiple Model
| 2. Post and Video Model has Comment Model
| 3. Comment Model: commentable_id (id of post or video), commentable_type(Post or Video Model's Class name)
| 4. One of Many: Relation is One to Many, but want to retrieve one data of many. morphOne(), ofMany(), oldestOfMany(), latestOfMany()
| 5. Many to Many: posts and videos have tags- taggables. morphToMany(), morphedByMany()
| 6. We can store Morphed Model as custom name instead of storing full class. use enforceMorphMap() and assign into boot()
*/
//One to One
//One to Many Morph
use Illuminate\Database\Eloquent\Relations\MorphTo;
class Comment extends Model{
    public function commntable(): MorphTo{
        return $this->morphTo();}
    
    public function commentable(): MorphTo{
        return $this->morphTo(__FUNCTION__, 'type', 'commentable_id'); //Explicitly Defined
    }
    }

use Illuminate\Database\Eloquent\Relations\MorphOne;
class Post extends Model{
    public function comments(): MorphMany{
        return $this->morphMany(Comment::class, 'commentable');
        //Same for Video Model
        //One to One: morphOne
}}
$comment = Post::find(1)->comment; //Use for each for One to Many
$commentable = Comment::find(1)->commentable;
$alias = $post->getMorphClass();
$class = Relation::getMorphedModel($alias);
 

/*
|--------------------------------------------------------------------------
| Accessors: Data Manipulation When Accessed from Database
| Accessor can work with multiple attributes fn(mixed, array) 
| Caching: shouldCache(),withoutObjectCaching()
| Mutator: Data Manipulation When it is set
--------------------------------------------------------------------------
*/

class User extends Model{
    protected function teacherName(): Attribute{
        return Attribute::make(
            get: fn (string $value) => ucfirst($value), //Accessor
            set: fn (string $value) => strtolower($value), //Mutator
        );
    }
}








