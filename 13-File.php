<?php
//File Upload
$path = $request->file('name')->store('location'); //or,
$path = Storage::putFile('location', $request->file('name'));
$path = $request->file('name')->storeAs('location', $request->user()->id); //or,
$path = Storage::putFileAs('location', $request->file('name'), $request->user()->id);
$path = Storage::putFile('/location'.$request->file('name'), 's3');

$file = $request->file('name');
$name = $file->getClientOriginalName();
$extension = $file->getClientOriginalExtension(); //Original Name Extension is unsafe, name can be malicious

$name = $file->hasName(); //Unique Random Name(Safe)
$extension = $file->extension(); //Determine extension
//File Visibility: public, getVisibility(), setVisibility(), storePubliclyAs()
Storage::delete('file_name');
Storage::delete(['file1', 'file2']);
Storage::disk('s3')->delete('folder/file.jpg');
//File Directories

//From Tutorials
if($request->hasFile('name')){
    $file = $request->file('avatar');
    $extension = $file->extension();

    $request->update(['avatar' => $file->store('path')]); //Random Name, path: storage/app/path
    $request->update(['avatar' => $file->store('path/'.$user->id)]); //storage-app-path-id-file
    $request->update(['avatar' => $file->storeAs('path', $user->id.'.png')]); //id.png
    $request->update(['avatar' => $file->storeAs('path', $user->id.'.'.$extension)]);
    $request->update(['avatar' => $file->store('path', 'public')]); //file path will be public
    $request->update(['avatar' => $file->store('path', 's3')]);
}

/*
|--------------------------------------------------------------------------
| config - filesystem.php and Amazon AWS
|--------------------------------------------------------------------------
|
| 1.local means files uploaded on same server
| 2. Path: storage/app- It is not a public folder and can't be accessed from url
| 3. php artisan make:storage link - storage will be linked in the public folder
| 4. If path is public, image will be accessed through url
| 5. In filesystem.php, we can edit storage_path as public_path
| 6. In filesystem.php - s3[simple storage service] is Amazon AWS[Amazon Web Services] for storage which is not free
| 7. s3 Setup: folder create in s3 and setup in .env file and install package provided by laravel
| 8. Make Visibility public and s3 settings to view, edit the image/file
| 9. <img src="{{ Storage::disk('s3')->url('path')}}" alt="">
*/

/*
|--------------------------------------------------------------------------
| File Validation
|--------------------------------------------------------------------------
|
| 1.$avatar=>'file', $avatar=>'image|mimes:jpg|size:30' - size 30 kilobyte
| 2. dimensions:min_width=200,min_height=200' dimensions:ratio=3/2. 
| 3. Edit max_file_size in php.ini and change apache settings
*/

//Image Resize when uploading: Resize image is preferable
//Use Package: PHP package- Image Intervention
//Laravel Package- File Pond (Resize and Data about Image) -Recommended and Laravel Media Library by Spatie

//File Storage


