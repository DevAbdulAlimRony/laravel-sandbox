<?php
/*
|--------------------------------------------------------------------------
| Web Sockets
|--------------------------------------------------------------------------
| 1. Real Time Live Updating Interface Implementation
| 2. Continually polling your application's server for data changes that should be reflected in your UI.
| 3. Broadcasting your Laravel events allows you to share the same event names and data between your server-side  
|    Laravel application and your client-side JavaScript application.
| 4. Laravel Drivers for Broadcasting: Pusher, Ably; Community Driven Driver: laravel-websockets, soketi
| 5. config/broadcasting.php: Pusher, Redis, log driver for local, null driver to stop broadcasting while testing
| 6. All event broadcasting is done via queued jobs
| 7. Laravel Echo is a JavaScript library that makes it painless to subscribe to channels and listen for events       broadcast by your server-side broadcasting driver.
| 8. Laravel's event broadcasting allows you to broadcast your server-side Laravel events to your client-side JavaScript application using a driver-based approach to WebSockets.
| 9. Based on authentication, event's channel can be public or private
| 10.php artisan channel:list
*/

/*
    In config/app.php - uncomment the broadcastServiceProvider
    Pusher Install for server side: composer require pusher/pusher-php-server
    Configure your key, secret from pusher
    Assign in .env file- BROADCAST_DRIVER=pusher
*/

/*
    Configure Laravel Echo (A Javascript Library) which receive the broadcast event on client side
    npm install --save-dev laravel-echo pusher-js
    resources/js/bootstrap.js - uncomment the pusher and echo configuration
    If already have pusher installed, assign pusher key in that options array
    Compile: npm run dev
    see your socket id: var socketId = Echo.socketId();
*/

//Event Broadcasting
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

use Illuminate\Broadcasting\PrivateChannel;
class OrderShipment implements ShouldBroadcast{
    //shouldBroadcastNow- if we use sync driver for queue

    //Should broadcast after this moment's database transaction, if not, database update may not be reflected 
    public $afterCommit = true;

    //If you use multiple broadcaster or, in channel controller
    use InteractsWithBroadcasting;
    public function __construct()
    {
        $this->broadcastVia('pusher');
    }

    public function broadcastOn(): Channel{
        return new PrivateChannel('orders.'.$this->order->id); //multiple channel: []
    }

    //Custom Naming Instead of Event Name
    public function broadcastAs(): string{
        return 'order.crated';
        //In listener, use . - .listen(){}
    }

    //Broadcast specific data instead of all from this event
    public function broadcastWith(): array{
        return ['id' => $this->user->id];
    }

    //Conditional Broadcasting
    public function broadcastWhen(): bool{
        return $this->order->value > 100;
    }
}

//We can customize driver for broadcasting queue in queue.php- broadcastQueue()


//Authenticate Private Channel: routes-channels.php
Broadcast::channel('orders.{orderId}', function(User $user, int $orderId){
    //Route Model Binding: function(User $user, Order $order)
    return $user->id === Order::findOrNew($orderId)->user_id;
});

//Multiple Guard
Broadcast::channel('channel', function(){}, [guard => ['admin', 'teacher']]);

//Authorization Routes: Provider/BroadCastServiceProvider- we can customize
Broadcast::routes($yourAttributes);
//Can Define authEndpoint in laravel echo


//Creating Channel Class like controller: php artisan make:channel OrderCreatedChannel (App/Broadcasting/)
Broadcast::channel('orders.{orderId}', OrderCreatedChannel::class);
class orderCreatedChannel{
    public function join(User $user, Order $order): array|bool{
        return $user->id === $order->user_id;
        orderCreatedEvent::dispatch($order);

        //Only to Others: Message sent to others, not to myself
        // use Illuminate\Broadcasting\InteractsWithSockets;
        //If you are not using a global Axios instance, you will need to manually configure your JavaScript application to send the X-Socket-ID header with all outgoing requests. 
        broadcast(new orderCreatedEvent($update))->toOthers();

        //If use multiple driver
        broadcast(new orderCreatedEvent($update))->via('pusher');
        
    }
}

//Receiving Broadcast using echo
/*
Echo.channel('orders.${orderId}').listen('OrderShipment', (e) => {
    console.log(e.order);
});
*/

//Private Channel: Echo.private()
//Stop Listening without removing channel: Echo.private().stopListening() [Ex. Order Cancelled, don't send orderShipment Notification]
//Leaving Channel: Echo.leaveChannel(), Echo.leave() [Ex. User don't want friend request notification]
//Echo automatically know events are placed in App/event. We can explicitly configure this namespace

//Presence Channel: private channel with awareness of subscribers of the channel [Ex: online chatting]

//Model BroadCasting
//Client Events
//Notifications


