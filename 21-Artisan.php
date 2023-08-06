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
    protected $signature = 'mail:send {user}';
    protected $description = "Send a Email to a User";
    public function handle(DripEmailer $drip): void{
        $drip->send(User::find($this->argument('user')));
    }
}

Artisan::command('mail:send {user}', function (string $user){
    $this->info('Sending Email');
});

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

