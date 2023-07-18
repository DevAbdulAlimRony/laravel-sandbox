<?php
/*
|--------------------------------------------------------------------------
| Validation Rules
|--------------------------------------------------------------------------
|
| 1. accepted: yes, no, 1, true
| 2. accepted_if:course_type,cce | declined_if | missing_if | missing_unless | missing_with:name
| 3. active_url: If the url is active or valid
| 4. after:date: after:tomorrow, after:start_date | after_or_equal:date, before:date, before_or_equal:date, date, date_equals:date, 
    |date_format:format
| 5. alpha, alpha_dash, alpha_num, bail, boolean, declined
| 6. between:min,max | decimal:min,max | different:field | digits:value | digits_between:min,max | integer |dimensions:min_width=100
| 7. confirmed, current_password
| 8. distinct | doesnt_start_with:bar | doesnt_end_with | email | ends_with: | enum | file | filled | gt:field | gte:field | image | in:abdul,alim | in_array:another_field | lt:field, lte
| 9. gt = greater than, lt = less than, lowercase, max:value, max_digits, mimes:jpg,bmp,png, multiple_f:3
| 10/ not_in: , numeric, nullable, password and so on. See Documentation
*/


//Validation (validate method)
$validation = $request->validate(['title' => 'required|max:255', 'about' => 'unique', 'author.name' => 'required']); //author.name is nested
$validation = $request->validate(['title' => ['required', 'max:255', 'nullable']]); //Optional Value must be nullable checked
$validation = $request->validateWithBag(['title' => ['required', 'max:255']]); //With Error Message
$validation = $request->validate(['title' => 'bail|required|max:255']); //Bail: If first validation failed, no further validation check

//Validation Message- got $error variable in all view
@if ($errors->any())
 @foreach ($errors->all() as $error)
     {{ $error }}
 @endforeach
@endif

<input class="@error('title') is-invalid @enderror" value="{{ old('title') }}">

@error('title')
    {{ $message }}
@enderror

$title = $request->old('title'); //Previous Data after Validation Flashed


//Custom Validation Message(Change Default: lang/en/validation.php)
'custom' => [
    'email' => [
        'required' => 'We need to know your email address!',
        'max' => 'Your email address is too long!'
    ],
],
'attributes' => [
    'email' => 'email address',
],
'values' => [
    'course_type' => [
        'cce' => 'computer communication'
    ],
],

/*
|--------------------------------------------------------------------------
| Custom Complex Validation By Form Request
|--------------------------------------------------------------------------
|
| 1. php artisan make:request StoreUserRequest
| 2. FormRequest Class: authorize(), rules()
| 3. If project have no lang directory: php artisan lang:publish
*/
protected $stopOnFirstFailure = true; //bail
protected $redirect = '/dashboard';
protected $redirectRoute = 'dashboard'; //dashboard is the route name

public function authorize(): bool{}
protected function prepareForValidation(): void{
    //Prepare data for Validation
}
public function rules(): array{
    return ['title' => 'required'];
}
protected function passedValidation(): void{
   $this->replace['name' => 'Abdul'];
   $this->merge(['tags' => strtolower($this->tags)]);
}
public function messages: array{
    return ['title.required' => 'This is required'];
}
public function attributes: array{
    return ['title' => 'name']; //previous: title is required, now: name is required
}
public function store(StoreUserRequest $request): RedirectResponse{
    $validated = $request->validated() //Retrieve All Validate Data
    $validated = $request->safe()->only(['title', 'age']); //Specific Data
    $validated = $request->safe()->except(['title', 'age']);
    return redirect('/users')
    //Additional Validation: after() function
}


//Manually Creating Validators
use Illuminate\Support\Facades\Validator;
$validator = Validator::make($request->all, ['title' => 'required']);
$validator = Validator::make($request->all, ['title' => 'required'])->validate(); //Auto Redirection
//Can Use Custom Message, Additional Validation
if($validator->stopOnFirstFailure()->fails()){
    return redirect('/users')->withErrors($validator)->withInput();
}
$validated = $validator->validated(); //now can use safe()->only() or except(), all()



//Error Message
echo $validator->errors()->first('email'); //First Message
foreach($validator->errors()->get('email') as $message){} //All Message

<input type="text" name="files[]">
foreach($validator->errors()->get('files.*') as $message){} //Multiple Input
if($errors->has('mail')) //check existence of error message

//Check Unique
Rule::unique('users', 'email')->ignore($user->id) //In users table, if email not unique ignore that id

/*
|--------------------------------------------------------------------------
| Custom Validation Rule
|--------------------------------------------------------------------------
|
| 1. php artisan make:rule ruleName
*/


