<?php
//Laravel Blade Templating Engine
//Echo Vars and Methods
{{ $name }}
{{ time() }}
{!! Escaped Data !!}
@{{ Untouched by Laravel, Render by Javascript Framework }}

//If Statement
@if ()
@elseif ()
@else ()
@endif ()

@unless (Auth::check())
@endunless

@isset()
@endisset()
@empty()
@endempty()


//Loop Structure
@for ($i = 0; $i < 10; $i++)
@endfor
 
@foreach ($users as $user)
<p>{{ $user->id }}</p>
@continue($user->id == 1)
@break($user->id == 5)
@if ($loop->last)
  <p>We got $loop variable for foreach loop</p>
@endif
@endforeach
//Loop Property: $loop->index, iteration, remaining, count, first, last, even, odd, depth, parent
 
@forelse ($users as $user)
    <li>{{ $user->name }}</li>
@empty
    <p>No users</p>
@endforelse
 
@while (true)
@endwhile

/*
|--------------------------------------------------------------------------
| Layout Directives
|--------------------------------------------------------------------------
|
| 1. @include: Include another Blade View
| 2. @extend: Extend master layout blade file in child file.
| 3. @section: Replace the Content of yeild in child layout
| 4. @yeild: Use in Master Layout to Clarify which Section it will be
*/

//Use Auth->user() object. Because it is accessable in any blade file. auth()-id, auth()-user()->name
//Date Formatting: format()
//Use Blade Error Page

//Asset Helper
<img src="{{asset('logo.png')}}">

//Using Default Page Title
<title>{{ $page_title ?? "Default Page Title" }}</title>

//Extending Blade Directives/Creating Custom Directives: Provide additional functionality to your views
public function boot(){
  Blade::directive('formatDate', function ($expression){
    return "<?php echo ($expression)->format('F j, Y');
     ?>";
  });
} //Now, in some blade view
<p>Published On: @formatDate($news->publication_date)</p>





