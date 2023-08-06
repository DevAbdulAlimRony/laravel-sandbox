<?php
/*
|--------------------------------------------------------------------------
| Event
|--------------------------------------------------------------------------
| 1. Observer Pattern Implementation
| 2. php artisan event:list
| 3. Generate Events listed in event service provider: php artisan:event generate
| 4. php artisan make:event UserCreated
| 5. php artisan make:listener UserCreatedNotification --event=UserCreated
| 6. On Deployment event discovery: event:cache, event:clear
*/
//Assign in Event Service Provider
protected $listen = [UserCreated::class => [UserCreatedNotification::class,],];
//or, we can manually register events in boot() method

// Queue Based Event
public function boot(): void{
    Event::listen(queueable(function (UserCreatedEvent $event){}));
}->onConnection('redis')->onQueue('users')->delay(now()->addSeconds(10))->catch(function (UserCreatedEvent $event, Throwable $e));

//Wildcart Listeners(Multiple Events on theSame Listener)
Event::listen('event.*', function(string $eventName, array $data){});

//Automatic Event Discovery in Event Service Provider: Detect handle or __invoke in Listeners directory
public function shouldDiscoverEvents(): bool{
    return true;

    //scan additional directory except listener
    return [$this->app->path(''),];
}

//Event Class Writing
class UserCreated{
    public function __construct{
        public User $user;
    }
}

//Listener
class SendUserCreatedNotification{
    public function __construct(){}
    public function handle(UserCreated $event): void{}
}
//Queue: Slow Process
class SendCreateNotification implements ShouldQueue{
    public $connection = '';
    public $queue = '';
    public $delay = '';

    public function handle(UserCreated $event): void{
        //If Queue use release, delete
        use InteractWithQueue;

        public $afterCommit = true
    }

    //conditional Queue
    public function shouldQueue(UsrCreated $event): bool{
        return $event->user->isCr => true;

    //Job Failure Handling
    public function failedUserCreated $event, Throwable $exception(): void{}

    //Queued Listener maximum attempt
    use DateTime;
    public function retryUntil(): dateTime{
        return now()->addMinutes(5);
    }
}}

//Dispatching Events
$user = User::findOrFail($id);
UserCreated::dispatch($user); //dispatchIf(). dispatchUnless()

//Event Subscriber: Multiple Events in a Event Class

//Testing Events
//Faking Events

