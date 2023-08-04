<?php
/*
|--------------------------------------------------------------------------
| Localization
|--------------------------------------------------------------------------
|
| 1.  Publishing Language Files: php artisan lang:publish
| 2.Fallback Locale in config.php
*/

//Locale for a Single Request
Route::get('/bangla-name/{locale}', function(string $locale){
    if(! in_array($locale, ['en'])){
        abort(400);
    }
    else if(App::isLocale('en')){}
    App::setLocale($locale);

    $current = App::currentLocale();
});

//Translation String - {{ __('messages.welcome') }} - from lang/en/messages

/*
|--------------------------------------------------------------------------
| Pluralization
|--------------------------------------------------------------------------
| 1. lang/bn.json = { "Fill the Form": "ফর্মটি পূরণ করুন"}
| 2. {"There is one apple|There are many apples": "Hay una manzana|Hay muchas manzanas"}
| 3. 'minutes_ago' => '{1} :value minute ago|[2,*] :value minutes ago',
| 4. echo trans_choice('time.minutes_ago', 5, ['value' => 5]);
| 5. Packages: Astrotomic Laravel Translatable, Laravel Multilang Models 
*/

