<?php
/*
|--------------------------------------------------------------------------
| Database Seeder
|--------------------------------------------------------------------------
|
| 1. php artisan make:seeder UserSeeder
| 2. One Method: run
| 3. php artisan db:seed
| 4. php artisan db:seed --class=UserSeeder
| 5. php artisan migrate:fresh --seed
| 6. php artisan db:seed --force
*/
class DatabaseSeeder extends Seeder{
    public function run(): void{
        DB::table('users')->insert(['id'=>Str::random(10)]);
        $this->call([PostSeeder::class, CommentSeeder::class]);

        //Seed 100 data Using, Use Factory Instead
        $faker = Factory::create();
        for($i=1; $i<=100; $i++){
            User::create([ 'name' => $faker->name,'email' => $faker->email]);
        }

        //Calling Factories
        $user = User::factory()->count(3)->admin()->make(); //admin() states
        //Overriding Attributes
        $user = User::factory()->make(['name'=>'Abdul ALim']);
        $user = User::factory()->state(['role' => 'admin'])->make();

        $user = User::factory()->count(3)->create();
        $user = User::factory()->state(new sequence(['admin' => 1], ['admin' => 0]))->create();
        $users = User::factory()->state(new sequence(
            fn (Sequence $sequence) => ['role' => UserRoles::all->random()],
        ))->create();

        //Factory Relationships
        $user = User::factory()->has(Post::factory()->count(3), 'posts')->create(); //has many, 'posts method' explicitly. or, use magic method
        $user = User::factory()->hasPosts(3)->create();
        //Belongsto: for(), forUser()
        //Many to Many: has(), with pivot table: hasAttached(), Magic: hasRoles

  }
}

/*
|--------------------------------------------------------------------------
| Database Factory
|--------------------------------------------------------------------------
|
| 1. php artisan make:factory UserFactory 
| 2. Package: Laravel Test Factory Generator
| 3. Generate Relationship which Seeder can't do
| 4. If Factory Name has no Factory word in last, explicitly call it on mode overriding newFactory() method and define the model property on the Factory
| 5. All faker method: https://fakerphp.github.io/
*/

class UserFactory extends Factory{
    public function definition(): array{
        return 
        ['email' => fake()->unique()->safeEmail(),
         'user_id' => User::factory() //Relationship
        ];
    }
    //State Manipulation: Specific Attribute Values
    public function admin(): Factory{
        return $this->state(function(array $attributes){
            return ['role' => 'admin'];
        });
    }
    
    //Factory Callbacks in configure():- afterMaking(): After calling Model before into Database -make(). afterCreating(): after saving to the database- create(), save()

}

//Raw Expressions/Query
$users = DB::table('users')->select(DB::raw('count(*) as user_count, status'))->get();

/*
|--------------------------------------------------------------------------
| Factory Generator Package
|--------------------------------------------------------------------------
|
| 1. Install: composer require thedoctor0/laravel-factory-generator --dev
| 2. php artisan generate:factory
| 3. php artisan generate:factory ModelName
| 4. Overriding: --force
*/