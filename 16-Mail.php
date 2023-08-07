<?php
/*
|--------------------------------------------------------------------------
| Mail
|--------------------------------------------------------------------------
|
| 1.  Simple Mail api from Symphony, config/mail.php -> mailer array -> default driver, assign in config.services.php
| 2. Mailgun: composer require symfony/mailgun-mailer symfony/http-client
| 3. In case service down, wen could have a backup configuration to send mail
| 4. php artisan make:mail AccountCreated 
| 5. with Markdown template: --markdown=emails.users.created
| 6. Customize Markdown Components: php artisan vendor:publish --tag=laravel-mail
*/
class UserAccountCreated extends Mailable{
    //Queueing by Default: implements ShouldQueue

    public function envelope(): Envelope{
        return new Envelope(
            from: new Address('mail@address', 'name'),
            subject: 'subject name',
            replyTo: [new Address()],
           
            //metadata: supported by mailgun and postmark
            tags: [],
            metadata: ['user_id' => $this->order->id],

            //Customizing Symphony's Message
            using: [function(Email $message){

            },],
        );
    }

    //Accessing Data from a model
    public function __construct(
        public User $user, //automatically available in view from content()
        protected User $users, //If use with()
    ){}

    public function content(): Content{
        return new Content(
            view: 'emails.users.account', //or,
            html: 'emails.users.account',
            text: 'emails.users.account-text', //If plain text
            with: ['userName' => $this->users->name,], //Formatting Data before Sending

            //If markdown used, instead of view
            markdown: 'emails.user,created',
        );
    }
    public function attachments(): array{
        return [Attachment::fromPath()->as('name.pdf')->withMime('application/pdf'),];
        //Default Disk- Attachment::fromStorage(), Other Disk- fromStorageDisk()
        //Inline attach in view: <img src="{{ $message->embed($pathToImage) }}">
        //Embed RawData:$message->embedData($data, 'example-image.jpg' 
        return [$this->user]; //from model
    }
    public function headers(): Headers{
        return new Headers(
            messageId: 'custom@example.com',
            references: ['previuos@example.com'],
            text: ['X-Custom-Header' => 'Custom Value'],
    );
    }
}

//Global from address in mail.php
// 'from' => ['address' => env(), 'name' => env()];

//Attach data from Model
class User extends Model implements Attachable{
    //implements HasLocalePreference

    public function toMailAttachment(): Attachment{
        return Attachment::fromPath();
    } 

    //User's Prefereed language
    public function preferredLocale(): string
    {
        return $this->locale;
        //automatically mail will detect language
    }
}

//Sending Email
Mail::to($request->user())->locale('bn')->send(new UserAccountCreated($user)); // cc(), //bcc(), foreach for multiple users

//Queuing Mail to get better performance: unified queue API
Mail::to($request->user())->queue(new UserAccountCreated($user));
Mail::to($request->user())->later(now()->addMinutes(10), new UserAccountCreated($user)); //Delayed Queue
//onConnection(), onQueue(), afterCommit()

//Previewing Mail
Route::get('/mailable', function () {
    $user = App\Models\User::find(1);
    return new App\Mail\UserCreated($user);
});

//Events: MessageSending, MessageSent

/*
|--------------------------------------------------------------------------
| Notification
|--------------------------------------------------------------------------
|
| 1.  php artisan make:notification AccountCreatedNotification
| 2. Sending Notification: Using notify method or notifiable trait
| 3. On Demand Notification: Notify when not a user, guest
| 4. Customize Template: php artisan vendor:publish --tag=laravel-notifications
*/
use Notifiable;
$user->notify(new UserCreatedNotification($created));

//Notifiable facade: usable when multiple users
use Illuminate\Support\Facades\Notification;
Notification::send($users, new UserCreatedNotification($created)); //sendNow()- send now also if queue defined

//Notification Class, Queue
class UserCreatedNotification extends Notification implements shouldQueue{
    use Queueable;

    //specify channel(mail, sms, database..)
    public function via(object $notifiable): array{
        return $notifiable;
    }

    //delay using method
    public function withDelay(object $notifiable): array{
        return [
            'mail' => now()->addMinutes(5),
            'sms' => now()->addMinutes(10),
        ];
    }

    //Queue Connection if not want default
    public $connection = 'redis';
    public function viaConnections(): array{
        return [
            'mail' => 'redis',
            'database' => 'sync',
        ];
    }
    //viaQueue(), afterCommit(), shouldSent()

    //Formatting Mail
    public function toMail(object $notifiable): MailMessage{
        $url = url('path');
        return (new MailMessage)->greeting('Hello!');
        //from(), line(), lineIf(), action('view', $url), error(), subject(), mailer('mailgun'), attach(), attachMany(), attachData(), tag(), metadata(), withSymphonyMessage()

        return (new MailMessage)->view();
    }
}

//Recipients in Model
class User{
    public function routeNotificationForMail(Notification $notification): array|string{
        return $this->email;
        return [$this->email => $this->name];
    }
}

//Markdown Notification, View Notification Template

//Database Notification(store notification in database and show in the panel)
//Broadcast Notification
//SMS Notification
//Slack Notification(Real Time Communication)

//Welcome Email 3 Ways: Notification, Observer, Events and Listeners




