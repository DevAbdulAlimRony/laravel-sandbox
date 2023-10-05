<?php
//Facades- Documentation
//Wrapper around a non static function to a static function. You can call a non static function, just like you call a static function. It's so clean and it can resolve the dependencies.
//Custom facade:    
    // 1. Integration with Third-Party Services: If your application interacts with third-party   services or APIs, you can create custom facades to encapsulate the communication logic and provide a cleaner interface.
    //2.Complex Business Logic: When you have complex business logic that spans multiple parts of your application, a custom facade can provide a high-level API for accessing that logic.
    //3.Utility Functions: If you have utility functions or helper methods that are used frequently throughout your application, you can create a custom facade to make them easily accessible.
    //4.Database Operations: For complex database queries or operations that involve multiple tables or complex relationships, a custom facade can simplify the process.
    //Making Process: Create and Register a Service Provider, Make a Facade Class, Create the Service, Bind the Service, Register into service Provider

class Sample{
    public function NonStaticHello(){
        echo "hello";
    }
    //...more function
}
class SampleFacade{
    public static function __callStatic($name, $arguments)
    {
        (new Sample())->{$name}(...$arguments);
    }
}
SampleFacade::NonStaticHello(); //when calling this method, it is going to the $name var in callStatic and resolve that class of that object.

//In laravel Ex.
// cache()->set(); - Helper Function
// Cache::set(); - Using Facade

//Request Life Cycle: Documentation
//Request->public/index.php->bootstrap/autoload.php + bootstrap/app.php (Load AutoLoader and retrieve instance of Application, See App Class)->app/http/kernel.php(Loads configuration files, detects environments, loads service providers etc)->Service Providers(Bootstrap Core Features, register Service Providers, Bootstrap Service Providers)->Dispatch Request->Router->Middleware->Controller->Model->Data Get->View->Response to User

//Service Container: Documentation
//Array of Bindings, we can use that binding anytime anywhere
class Container{
    protected $bindings = [];
    public function bind($name, callable $resolver){
        $this->bindings[$name] = $resolver;
    }
    public function make($name){
        return $this->bindings[$name]();
    }
}
$container = new Container;
$container->bind('Game', function(){
    return 'Football';
});
print_r($container->make('Game'));

//Service Provider: Documentation
//Service providers are the central place to configure your application. Services are added to service container by service provider. All service providers are available at config/app.php, some loaded for every request, some are deferred. Generally have two methods:
    //1. register Method: Only use to bind things to service container
    //2. boot method: Which are loaded in service container

//Bootstrap
//Kernel
//Environment
//Configuration
//Which we got default in installed laravel(what middleware, what service providers, what bindings in service container, what views, controllers, models etc.)
//Observe All Service Providers
//Helper Functions

?>