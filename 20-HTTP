<?php
/*
|--------------------------------------------------------------------------
| Guzzle
|--------------------------------------------------------------------------
| Make HTTP Requests to external API, web services or any server
| Example- Weather Application fetching data from external weather api
| Auto Installed, if removed "composer require guzzlehttp/guzzle"
| head, get, post, put, patch, delete
*/
$response = HTTP::get('https:://pstu.ac.bd');

$response->body(); 
//json(), object(), collect(), status(), successful(), redirect(), failed(), failed(), clientError(), header(), headers()

//Status Code Check
$response->ok(); //200 status code
// created(), accepted(), noContent(), movedPermanantly(), found(), badRequest(), unauthorized(), paymentRequired(), forbidden(), notFound(), requestTimeOut() conflick(), unprocessableEntity(), tooManyRequest(), serverError(), dd()

//URI Template
HTTP::withUrlParameters([
    'endpoint' => 'pstu.ac.bd',
    'page' => 'pageName',
    'version' => '',
    'topic' => 'validation'
])->get('{+endpoint}/{page}/{version}/{topic}');

//Request Data
$response = HTTP::post('url path', [
    'name'=>'Abdul'
]);
HTTP::retry(3, 100)->withQueryParameters([
    'name' => 'Abdul'
])->get('url'); //HTTP:: asForm(), withBody(), attach(), withHeaders(), accept(), acceptJson(), replaceHeaders()

//Authentication
$response = HTTP::withBasicAuth('email', 'password')->post();
$response = HTTP::withDigestAuth('email', 'password')->pos();
$response = HTTP::withToken('token')->post();

//Timeout: Max wait for a response. Default is 30 SEeconds
$response = HTTP::timeout(3)->get();
//Timeout to connect to a server
$response = HTTP::connectTimeOut(3)->get();

//Retry if timeout
$response = HTTP::retry(3, 100)->post();
$response = HTTP::retry(3, 100, function (Exception $exception, PendingRequest $request){
  return $exception instanceof ConnectionException;  
})->post();

//Error Handling: successful() - status >=200and<300, failed()>=400, clientError() - 400 level status code, serverError() - 500, onError - Client or Server Error

//Throwing Exceptions
$response->throw(); //throwIf(), throughUnless(), throughIfStatus(), throughUnlessStatus(200)

//Guzzle Middleware
$response = HTTP::withRequestMiddleware(function (RequestInterface $request){}); //withResponseMiddleware()

//In boot: globalRequestMiddleware(), globalResponseMiddleware()

//Guzzle Option: withOptions()

//Concurrent API: Parallel Multiple Request
$responses = Http::pool(fn (Pool $pool) => [
    $pool->get('Https://rupkathak.com'),
    $pool->get('Https://poratecai.com'),
    $pool->get('Https://ghoramibd.com'),
]);
return $responses[0]->ok();

//macro

//Test with faking responses
Http::fake();
Http::fake([
    'github.com/*' => Http::sequence()->push()->whenEmpty(Http::response()),
]); //sequence(), fakeSequence()

//Fake Callback
Http::fake(function (Request $request){});

//preventStaryRequests()

//Inspecting Request: assertSent(), assertNotSent()...

//Recording Request

//Events

