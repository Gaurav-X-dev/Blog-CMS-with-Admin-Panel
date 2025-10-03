<?php
//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});
Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'cleared!';
});

//frontend

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/blog/{slug}', 'HomeController@slugDetails')->name('blog.details');
    Route::get('/list/{slug}', 'HomeController@blogList')->name('blogs.list');
    Route::get('/search-blogs', 'HomeController@search')->name('blogs.search');
    Route::get('/user/register', 'UserController@register')->name('user.register');
    Route::get('/user/login', 'UserController@login')->name('user.login');
    Route::post('/user/getRegister', 'UserController@getRegister')->name('user.getRegister');
    Route::get('/user/logout', 'UserController@logout')->name('user.logout');
    Route::post('/user/getLogin', 'UserController@getLogin')->name('user.getLogin');
    Route::get('/user/story', 'StoryController@story')->name('user.story');
    Route::post('/user-create-story', 'StoryController@store')->name('story.add');
    Route::get('/edit-story/{id}', 'StoryController@editStory')->name('user.editStory');
    Route::post('/user-update-story/{id}', 'StoryController@update')->name('story.update');
    Route::get('/story/{slug}', 'StoryController@storyDetails')->name('story.details');
    Route::get('/user-story/{id}', 'StoryController@userStory')->name('user.userStory');
    Route::get('/{slug}', 'PageController@showPage')->where('slug', '^(?!blog|user|list|portal|author).*')->name('page');

});

// Admin Routes
Route::group(['namespace' => 'App\Http\Controllers\admin', 'prefix' => 'portal'], function () {
    Route::get('/', 'AuthController@index')->name('admin.index');
    Route::get('refreshCaptcha', 'AuthController@refreshCaptcha')->name('refreshCaptcha');
    Route::post('/check-login', 'AuthController@login')->name('admin.login');
    Route::get('/logout', 'AuthController@logout')->name('admin.logout');

    // Protected Routes (Requires Authentication)
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        Route::resource('permissions', 'PermissionController')->middleware('permission:manage permissions');
        Route::resource('roles', 'RoleController')->middleware('permission:manage roles');
        Route::resource('users', 'UserController')->middleware('permission:manage users');
        Route::get('users/{id}/permissions', 'UserController@managePermissions')->name('users.permissions');
        Route::get('/deleteUserPhoto/{id}', 'UserController@deletePhoto')->name('user.deletePhoto');
        Route::post('users/{id}/permissions', 'UserController@updatePermissions')->name('users.update.permissions');
        Route::resource('setting', 'SettingController');
        Route::get('/deletePhoto/{id}/{title}', 'SettingController@deletePhoto')->name('deletePhoto');
        Route::resource('profile', 'ProfileController');
        // Blog Management
        Route::resource('category', 'CategoryController');
        Route::resource('blog', 'BlogController');
        Route::get('/publishBlog/{id}/', 'BlogController@publishBlog')->name('blog.publish');
        Route::get('/deleteBlogPhoto/{id}', 'BlogController@deletePhoto')->name('blog.deletePhoto');
        // Page Management
        Route::resource('menu', 'MenuController');
        Route::resource('page', 'PageController');
        Route::resource('section', 'SectionController');
        Route::resource('pagesection', 'PageSectionController');
        Route::post('update_order', 'PageSectionController@updateOrder')->name('updateOrder');
        Route::post('/fetchSection', 'PageSectionController@fetchSection')->name('page.section_data');

        //member 
        Route::get('/member-list', 'MemberController@memberList')->name('member.list');
        Route::get('/block-member/{id}/', 'MemberController@blockMember')->name('member.block');
        Route::get('/post-story', 'MemberController@postStory')->name('story.list');
        Route::get('/publish-story/{id}/', 'MemberController@publishStory')->name('story.publish');
        Route::get('/delete-story/{id}/', 'MemberController@deleteStory')->name('story.delete');
        //advertisement 
        Route::resource('advertisement', 'AdvertisementController');
        Route::get('/publishAdvertisement/{id}/', 'AdvertisementController@publishAdvertisement')->name('advertisement.publish');



    });

});




