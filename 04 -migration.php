<?php
/*
|--------------------------------------------------------------------------
| Migration Artisan Commands
|--------------------------------------------------------------------------
|
| 1. New Migration: php artisan make:migration create_users_table, custom path: use --path
| 2. All Migration in a single File: php artisan schema:dump --prune 
| 3. If Migration have another database connection: protected $connection = 'pgsql';
| 4. Run Migration: php artisan migrate, Which migration have run: php artisan migrate:status, see SQL statements: php artisan migrate 
|    --pretend, Force in Deployment: --force
| 5. Run Migration on Multiple Server: php artisan migrate --isolated
| 6. Rollback Last Migration: php artisan migrate:rollback --step=5, Specific Batch: --batch-3, Not Run Just See the SQL Statements: 
|    --pretend, All Rollback: php artisan migrate:reset
| 7. Recreate All migrations: php artisan migrate:refresh --seed
*/

//Checking Table and Column
if(Schema::hasTable('users'))
if(Schema::hasColumn('users', 'name')){}

//Mysql Configuration Property and Methods
Schema::create('users', function (Blueprint $table) {
    //storage engine
    $table->engine = 'InnoDB';

    //Char Set
    $table->charset = 'utf8mb4';

    //How A and a be Compared
    $table->collation = 'utf8mb4_unicode_ci';

    //Temporary Table(Drop Database when connection is closed)
    $table->temporary();
    $table->comment('Table Comment');

    //Adding Columns
    $table->integer('id');
    $table->bigIncrements('id');
    $table->bigInteger('votes');
    $table->binary('photo');
    $table->boolean('confirmed');
    $table->char('name', 100);
    $table->dateTime('created_at', $precision = 0);
    $table->date('created_at');
    $table->decimal('amount', $precision = 8, $scale = 2);
    $table->double('amount', 8, 2);
    $table->enum('difficulty', ['easy', 'hard']);
    $table->float('amount', 8, 2);
    $table->foreignId('user_id');
    $table->foreignIdFor(User::class);
    $table->geometry('positions');
    $table->id(); //Big Increments
    $table->increments('id');
    $table->integer('votes');
    $table->ipAddress('visitor');
    $table->json('options');
    $table->longText('description');
    $table->macAddress('device');
    $table->morphs('taggable');// Create taggable_id and taggable_type
    $table->nullableTimestamps(0);
    $table->nullableMorphs('taggable');
    $table->rememberToken();
    $table->set('flavors', ['strawberry', 'vanilla']);
    $table->softDeletes($column = 'deleted_at', $precision = 0); //Nullable Deleted at
    $table->string('name', 100);
    $table->text('description');
    $table->time('sunrise', $precision = 0);
    $table->year('birth_year');

    //Column Modifiers
    $table->year('birth_year')->nullable()->after('id')->autoIncrement()
          ->comment()->default('2017')->first()->from('2017')->invisible()
          ->nullable($value = true)->useCurrent()->useCurrentOnUpdate();

   //Modify Column
   $table->string('name', 50)->default(1)->change();

   //Rename Column
   $table->renameColumn('from', 'to');

   //Dropping Columns
   $table->dropColumn('votes');
   $table->dropColumn(['votes', 'name']);
   $table->dropMorphs('taggable');
   $table->dropRememberToken();
   $table->dropSoftDeletes();
   $table->dropTimestamps();
   $table->dropForeign('posts_user_id_foreign');

   //Indexes/Unique Value
   $table->year('birth_year')->unique();
   $table->unique('email');
   $table->index(['id', 'created_at']);
   $table->unique('email', 'unique_email');
   $table->primary('id');

   //Foreign Key Constraints
   $table->foreign('user_id')->references('id')->on('users');
   $table->foreignId('user_id')->constrained(); //Constrained Method must be at last
   $table->foreignId('user_id')->constrained( table: 'users', indexName: 'posts_user_id' );
   $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
   //cascadeOnDelete(), restrictonUpdate(), nullonDelete()
});

//Renaming and Dropping
Schema::rename('from', 'to');
Schema::drop('users');
Schema::dropIfExists('users');

//Foreign Key Constraints
Schema::enableForeignKeyConstraints();
Schema::disableForeignKeyConstraints();
Schema::withoutForeignKeyConstraints(function () {});