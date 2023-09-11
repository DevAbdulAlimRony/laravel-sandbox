<?php
/*
|--------------------------------------------------------------------------
| Test
|--------------------------------------------------------------------------
|
| 1. Unit Test: A portion, Feature Test: A large functionality or full http request
| 2. Run Test = config:clear -> php artisan test
| 3. Environment will be automatically in testing, no caching, no session or create .env.testing
| 4. php artisan make:test UserTest  (or, with --unit, --pest or --unit --pest)
| 5. Parallel Testing to get fast and advantages, Coverage
*/
use Illuminate\Foundation\Testing\RefreshDatabase;
class UserTest extends TestCase{

    //Reset Database
    use RefreshDatabase;

    public function test_student_has_result(): void{
        $student = Student::factory()->create();

        // In TestCase Class - protected $seed = true or specific class
        $this->seed(StudentSeeder::class);

        $this->assertTrue(true);

        //assertDatabaseCount(), assertDatabaseHas(), assertDatabaseMissing()
        //assertSoftDeleted(), assertNotSoftDeleted()
        //assertModelExists(), assertModelMissing(), expextsDatabseQueryCount()
    }
}

//Project with TDD

//Mocking: Don't execute test like test controller, not the event for it.

//Laravel Dusk: Easy to use browser automated testing API

/*
|--------------------------------------------------------------------------
| Writing Testable Code
|--------------------------------------------------------------------------
1. It’s simply a test that runs against an individual ‘unit’ or component of software. We are testing the smallest possible implementation of a class/method. A unit test does not rely on any dependencies (other classes/libraries or even the database). Writing effective unit tests help us to ensure we are writing SOLID code. 
2. Our code can be refactored so that it can be easily testable. (do more loosely coupled, less tightly coupled)
3. We can use loosely coupled pattern.
4. Writing unit tests is the easiest way to create a short feedback loop between developing an app and knowing that something is wrong with it. Badly written code can make it really hard to write such tests and trickier to move towards a more confident development process. 
5. Split up Complex code by Event-Driven Architecture
6. in great architecture, we’d like to have loose coupling and high cohesion. If a project is loosely coupled, a change in one place should not require a change in another place. High cohesion means that related behavior sits in one place, and other unrelated behavior sits in a different place. You can interpret this at the level of classes, for example, in the Order class, you don’t want a method called getProductPrice(), you want it in the Product class.
7. Tight and loose coupling are terms used to describe the relationship between components of an application. In tight coupling, the components of a system are highly dependent on each other. If one component fails, it will also affect the others and eventually bring down the entire system. This creates inflexibility issues since any modifications to one component may require modifications to others. This can make the system difficult to scale and maintain. In a loosely coupled system, the components are independent of each other. Each component has its own well-defined interface and communicates with other components through standardized protocols.
*/