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

