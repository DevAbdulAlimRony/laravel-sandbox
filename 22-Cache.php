<?php
/*
|--------------------------------------------------------------------------
| Caching
|--------------------------------------------------------------------------
| 1. Configuration: config/cache.php (driver: memcached, redis, array or null for test cache)
| 2. Laravel is configured to use the file cache driver by default
| 3. Database Driver Prerequisite: Having a cache table with key, value and expiration attributes (php artisan cache:table)
| 4. Redis Prerequisite: Install PhpRedis php extension or predis/predis via composer
*/

//Cache Usage
use Illuminate\Support\Facades\Cache;
class UserController extends Controller{
    public function index(): array{

        $value = Cache::get('key');
        //If no cach value, it will return null
        $valueWithDefault = Cache::get('key', 'default');
        $valueWithDefaultClosure = Cache::get('key', function(){});

        //add(), increment(), decrement()

        Cache::store('redis')->put('key', 'value', $seconds = 10); //put: store in cache
        //or, instead of seconds- now()->addMinutes()
        $value2 = Cache::store('file')->get('');

        //Check if Cache Exists
        if(Cache::has('key')){}

        //Try to Retrieve from Cache, if not exist retrieve from database and store in cache
        Cache::remember('users', $seconds, function(){
            return DB::table('users')->get();
        }); //rememberForever()

        //Retrieve from Cache and delete
        Cache::pull('key');

        //Storing items: put(), add(), forever()

        //Removing Items from Cache
        Cache::forget('key');
        Cache::put('key', 'value', 0);
        Cache::put('key', 'value', -1);
        Cache::flush(); //entire cache cleared

        //Global cache function without facade
        $value = cache('key');
        $value2 = cache(['key' => 'value'], $seconds);
        cache()->remember();
        Cache::shouldReceive; //when testing cache

        //Cache Tags: Memcached use it to get better performance
        $store = Cache::tags(['students', 'teachers'])->put();
        $access = Cache::tags(['students', 'teachers'])->get();
        $remove = Cache::tags(['students', 'teachers'])->flush();

        //Pruning Stale Cache Tags only when using Redis

        /* Atomic Locks
        1. Only One Request/User can access and modify. Ex- one product available, two users trying to purchase. Using Lock will get this advantage in this scenario- user1 purchase product using atomic lock and lock released when user2 trying to purchase product and got product quantity 0.
        2. Database Must have cache_locks table with key, owner and expiration attributes
        */
        $lock = Cache::lock();
        if($lock->get()){
            //...
            $lock->release();
        } //or,
        Cache::lock()->get(function (){});
        try{ $lock->block(5); } //wait for 5 seconds;
        catch(LockTimeOutException $e){}
        finally{ $lock?->release(); }  //or refactoring,

        Cache::lock('foo', 10)->block(5, function(){});
        
        //Lock Across Processes: Lock one process and release to another process

        //Custom Cache Driver
        // Cache Events

    }
}