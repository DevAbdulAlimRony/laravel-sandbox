<?php
/*
|--------------------------------------------------------------------------
| Command Line Interface included with Laravel
|--------------------------------------------------------------------------
| php artisan list
| Know about a command- php artisan help migrate
| Using Tinker: composer require laravel/tinker
| php artisan tinker, php artisan vendor:publish --provider="Laravel\Tinker\TinkerServiceProvider"
| Tinker Command Allow List -'commands' =[App\Console\Commands\ExampleCommand::class,],
| Disallow Class to tinker: 'dont_alias' => [App\Models\User::class,],
| Custom Command: php artisan make:command SendEmails (app/console/commands)
| php artisan schedule:list
| schedule:clear-cache
| Must add schedule:run in cron configuration entry
| php artisan schedule:interrupt
| Run Locally: php artisan schedule:work
*/

class SendEmails extends Command{
    protected $signature = 'email:send {user}';
    protected $description = "Send a Email to a User";

    public function handle(DripEmailer $drip): void{
        $drip->send(User::find($this->argument('user')));
        //or just do write logic as we do in controller
        $this->info('success');
    }
}

class CreateUser extends Command{
    //with parameters:  protected $signature = 'user:create {name} {email} {password}';
    //with optional:  protected $signature = 'user:create {name} {email} {password?}';
    //with Flag: 
    protected $signature = 'user:create {--verified} {--name=} {--email=} {--password=}';

    //command will be: php artisan user:create --name='a' --email='' --password='' --verified

    protected $description = "Create a User";

    public function handle(): void{
        $name = $this->option('name'); //option instead of argument
        $email = $this->option('email');
        $password = $this->option('password') ?? Str::random(12);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'email_verified_at' => $this->option('verified') ? now() : null,
        ]);
        $this->info('success');
    }
}

//Progressbar, Creating 10 Random Users like database factory seed
class CreateUsers extends Command{
    public function handle(){
        $count = $this->option('count');

        $bar = $this->output->createProgressBar($count);
        $bar->start();
        for($i=1;$i<=$count;$i++){
            $name = Str::random(8);
            User::create(['name' => $name,]);
            $bar->advance();
        }
        $bar->finish();
    }
}




Artisan::command('mail:send {user}', function (string $user){
    $this->info('Sending Email');
});

//Calling Command From Anywhere
Artisan::call('user:create');

//Isolated Command

//Task Scheduling: app/console/kernel.php
class Kernel extends ConsoleKernel{

    //clear a table daily at midnight
    protected function schedule(Schedule $schedule){
        $schedule->call(function (){
            DB::table('cr-tasks')->delete();
        })->daily();

        //or using invokable object
        $schedule->call(new DeleteCrTasks)->daily();

        //scheduling command
        $schedule->command(SendEmailsCommand::class, ['Abdul', '--force'])->weekly()->mondays()->at('13.00');

        //Command On Operating System
        $schedule->exec('node /home/forge/script.js')->daily();

        //Queued Jobs Scheduling

        //truth test- when(function(){ }), Inverse- skip()

        $schedule->command('emails:send')
            ->daily()
            ->environments(['local', 'production'])->timeZone()->at();

        //When one task running, no overlap
        $schedule->command('emails:send')->withoutOverlapping();

        //onOneServer(), job(), runInBackground()- multiple tasks simultaneously

        //Run on Maintenance Mode: evenInMaintenanceMethod()
    }
}

//TaskOutput: sendOutputTo(), appendOutputTo(), emailOutputTo(), emailOutputOnFailure()

//Task Hook: before(function(){}), after(), onSuccess(), onFailure()

//Pinging URLS: urls before and after: pingBefore(), pingAfter(), pingBeforeIf(), pingAfterIf(), pingOnSuccess(), pingOnFailure()

//Events: LogScheduledTaskStarting, ....

