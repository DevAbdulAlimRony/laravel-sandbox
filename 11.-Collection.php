<?php
/*
|--------------------------------------------------------------------------
| Collections (Manipulating/Conditioning collection of arrays)
| If a Model returns multiple record, then it is a collection like User::all()
--------------------------------------------------------------------------
*/
//Available Methods
$collection->all()->avg()->count();
$collection->isEmpty(); //true, false, isNotEmpty()
$collection->get('name'); //Output: Value of the Key Name, If key not exists returns null
$collection->get('name', 'default value');
$collection->groupBy('category'); //Group By any key/attribute
$collection->dd(); //dumps all items, dum()
$collection->countBy(); // ['Abdul' => '2'] Abdul occurs 2 times in the collection, can pass function for custom check
$collection->chunk(4); //break collection into multiple small, better performance like import from large CSV file or in grid system
//chunkWhile(), collapse(): collection of arrays into a single flat collection

$collection = collect(['name', 'rol']);
$combined = $collection->combine(['Abdul', 'admin']);
$combined->all(); // ['name' => 'Abdul', 'rol' => 'admin']

$collection->concat(['Abdul'])->concat(['name' => 'Alim']); //['Abdul', 'Alim']
$collection->contains(function(int $value, int $key){return $value == 'Abdul';}); //If Contains Abdul- true
$collection->contains('Abdul'); $collection->contains('key_Name', 'value_Abdul');
//some() - alias for the contains()
//containsStrict(): int and string number won't be equal, doesntContain(), containsOneItem()
$matrix = collect([1, 2])->crossJoin(['a', 'b']); //[[1, a], [1, b], [2, a], [2, b]]
//zip() : [[1, a], [1, b]]
$setDifference =  collect([1, 2, 3, 4])->diff([1, 2]); // Output: [3, 4]
//difAssoc() : Based on Key Value pair, diffKeys()
//intersect(), interSectAssoc(), interSectByKeys(): Common Values
//union()
$remove = forget('name'); //remove items by keys

$reply = collect(['users' => ['posts' => ['comments' => 'reply']]]);
$reply->dot(); // ['posts.comments' => 'reply']
//duplicate() : Returns duplicating values, duplicate('key') - If name 'abdul' occurrence is 2 got, 2 => 'abdul', duplicateStrict()
//unique(), uniqueStrict()
// each(function(int $item, int $key)): iterate the collection, eachSpread(): nested Item
//unless(), unlessEmpty(), unlessNotEmpty(), when(), whenEmpty(), whenNotEmpty()
//where(), whereStrict(), whereBetween(), whereIn(), whereInStrict(), whereInstanceOf(), whereNotBetween(), whereNotIn(), whereNull(), whereNotNull()
// every(function(int $item, int $key)): if every item fulfill same condition, then true
// filter(function(int $item, int $key)): filter if match the condition, reject(): filter and remove those
//first(function(int $item, int $key)): Return the first item that matches the condition, firstOrFail(): If no items match, throw error
//last(): last item that match the condition
$collection->firstWhere('key', 'value'); $collection->firstWhere('age', '>=', '18');
$collection->except('name'); //Returns except name key
$collection->only('name'); //Returns only name key
//flatMap(): Iterate, pass data into closure, manipulate data by closure, flatten(): multi dimensional to single dimension
//flip(): swap value and key
$collection->forPage(2, 3); //show 3 items in page 2
//has(), hasAny([]): If collection has the keys/attributes
collect([1, 2])->implode('-'); //1-2
collect([1, 2])->join(',' , 'and'); // 1 and 2

$collection->keyBy('id'); //Replace the key name by id value
$collection->keys(); //All keys/attributes
//lazy(): working with huge data
//macro() : Extending Collection's method, make(): create new Collection instance, map(function()): iterate, manipulate
//mapSpread(): Same as Map, but return new Collection, mapInto(): make each item into a instance of class
//mapToGroups: Group by checking using closure of map, mapWithKeys()
//merge() : If key match between collections, override the value
//pad() : fill the array for specified size and replace by specified value - [1, 2] - pad(4, 0) - [1, 2, 0, 0]
//partition(): partition/divide array for given condition


//max(), min() median(), mode(), nth(4): 4th item, random(), range(min, max), sum()

//pipe(): closure data with data - [1, 2] = pipe(.....)->sum() = 1 + 2
//pipeInto(): items as instances , pipeThrough(): take multiple closures for multiple condition
$collection->pluck('name'); //all value of the name attribute or key
$collection->pluck('name', 'id'); // id => name, id will be the key
collect([1, 2, 3])->pop(); //[3]: removes last item and make new array
collect([1,2,3])->pop()->all(); // [1, 2]
collect([1, 2, 3])->pop(2); //[1]: removes last 2 items
// pull(): same as pop, but remove by key
//shift(): remove first item
//prepend() = adds item at starting, push(): add item at ending
//put(): set new key value in the collection
//shuffle(): randomly shuffle or order


//reduce(): reduce the collection to a single value, reduceSpread()
//replace(), replaceRecursive(), reverse(): reverse the order
//search(value), search(value, $strict = true), search(function(int $item, int $key))
//skip(), skipUntil(), skipWhile(), slice(), sliding()
//sole(): true- first and at most or only one item if match the condition

//sort(), sort(function()), sortBy(), sortDesc(), sortByDesc(), sortKeys(), sortKeyDesc(), sortKeyUsing()
$sorted = $collection->sortBy('title', SORT_NATURAL);
$sorted->values()->all();

//splice(), split(), splitIn()
//take(), takeUntil(), takeWhile(), tap(), times()
//toArray(), toJson(), transform(), unwrap()
//value(): first value of given key, values()

/*
|--------------------------------------------------------------------------
| Higher Order Messages: can be used as property. Ex. $var->unique
| All Higher Order Messages: average, avg, contains, each, every, filter, first, flatMap, groupBy, keyBy, map, max, min, partition, 
| reject, skipUntil, skipWhile, some, sortBy, sortByDesc, sum, takeUntil, takeWhile, and unique
--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Lazy Collection: Later
--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Eloquent Collection
--------------------------------------------------------------------------
| 1. Extends Laravel's Base Collection
| 2. append(attribute), contains($key), diff(), except(), find(), intersect()
| 3. fresh(): fresh instance from database
| 4. load() : Eager Loading, loadMissing(): Eager load if not loaded
| 5. modelKeys(): primary key for all model, only($keys): If have those primary key
| 6. setVisible(), setHidden(), makeVisible(), makeHidden()
| 7. toQuery(), unique()
| 8. Custom Collection: Locally in a Model or Globally in the Model Class using newCollection()
*/



