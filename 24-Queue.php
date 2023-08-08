<?php
/*
|--------------------------------------------------------------------------
| Queue, Job, Work
|--------------------------------------------------------------------------
| 1. config/queue.php - drivers are amazon SQS, Redis, BeanStalked, null with discard queued jobs
| 2. Database Queue: php artisan queue:table, migrate, In .env- QUEUE_CONNECTION=database
| 3. Configuration for Redis Queue
| 4. php artisan make:job ProcessSomething (app/jobs)
| 5. php artisan queue:work --tries=3
| 6. php artisan queue:work --timeout=30
| 7. The pcntl PHP extension must be installed in order to specify job timeouts
| 8. Job Batching: php artisan queue:batches-table, migrate
*/
class ProcessSomething implements ShouldQueue{
    use Dispatchable, InteractWithQueue, Queueable, SerializesModel;

    public $tries = 10; //after 10, failed job.
    public $maxExceptions = 3;
    public $timeout = 120;
    public $failOnTimeout = true;

    public function __construct(
        public User $user,
    ){}

    //Deserialized using withoutRelations()

    public function handle(UserProcessor $processor): void{

    }

    //implements shouldBeUnique: With cache lock
    public $uniqueFor = 3600;
    public function uniqueId(): string{
        return $this->user->id;
    } //any dispatch with same id will be ignored until processed and all retry for one.
    public function uniqueVia(): Repository{
        return Cache::driver('redis');
    }
    //or, withoutOverlapping middleware route

    //Not Retry, immediately after one process: implements ShouldBeUniqueUntilProcessing

    //implements ShouldBeEncrypted

    public function middleware(): array{
        return [new RateLimited];
        //RatLimited class can be implemented in Jobs/Middleware or anywhere
        // ->dontRelease() - don't retry

        return [new WithoutOverlapping($this->user->id)];
        return [(new WithoutOverlapping($this->order->id))->releaseAfter(60)];
        return [(new WithoutOverlapping($this->order->id))->dontRelease()]; //Immediately Delete, if overlap
        return [(new WithoutOverlapping($this->order->id))->expireAfter(180)];

        //Prevent Overlapping for Different class - shared()

        //ThrottlesException(), retryUntil(), backoff(), by('key')

        //Manually Release
        $this->release();
        $this->release(10);
        $this->release(now()->addSeconds(10));

        //Manually Failed
        $this->fail();
        $this->fail($exception);
        $this->fail('Something went wrong.');
    }
}

//Dependency Injection in AppServiceProvider
$this->app->bindMethod([ProcessSomething::class, 'handle'], function (ProcessSomething $job, Application $app) {
    return $job->handle($app->make(UserProcessor::class));
});

//Job RateLimiter Middleware in AppServiceProvider

//Dispatching Job
class SomeController extends Controller{
    public function store(){
        ProcessSomething::dispatch($user);

        //dispatchIf(), dispatchUnless(), delay(), dispatchAfterResponse(), dispatchSync(), afterCommit(), beforeCommit()

        //Job Chaining: If one job fails, others of that sequence won't work
        Illuminate\Support\Facades\Bus;
        Bus::chain([new ProcessPodcast, new OptimizePodcast, new ReleasePodcast,])->dispatch();
        //onConnection, onQueue
        Bus::chain([new ProcessPodcast, new OptimizePodcast, function(){ Podcast::update(); }])->catch(function (Throwable $e) {
            // A job within the chain has failed...
        })->dispatch();


}}

//Database Transaction: using afterCommit = true in configuration (globally)

//Job Batching: Series of Job Together (Insert from CSV file)
//More in future..

