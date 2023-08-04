<?php
/*
|--------------------------------------------------------------------------
| Logging
|--------------------------------------------------------------------------
|
| 1.  Log files are used to record and store information about events, errors, and other relevant messages that occur during the   
|     application's runtime. 
| 2. Package: Laravel Log Viewer
| 3. Can Create Custom Log Files
| 4. Uses: Error Show, debugging and troubleshooting, Security, Performance Monitoring, Events and Tasks 
*/
use Illuminate\Support\Facades\Log;
Log::debug('message');
Log::emergency('system is down');
Log::channel('slack')->info('Something happened!');
Log::stack(['single', 'slack'])->info('Something happened!');
//critical, error, warning, information, notice

/*
|-----------------------------------------------------------------------------------
| Local Debugging Tools: Laravel DebugBar, Telescope, Spatie Ray Premium, clockwork
| Custom Error Page: views/errors/404.blade.php- laravel automatically detects
| or, Publish Default Error Page by vendor:publish and Customize it- Recommended
| Fallback HTTP Error Page: 4xx.blade.php, 5xx.blade.php
| php and Javascript Error Tracking: Flare, Sentry
|-----------------------------------------------------------------------------------
*/

//Exception Handler- try, catch, throw


