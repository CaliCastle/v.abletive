<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', function () {
        return view('pages.welcome');
    });
    Route::get('/home', 'HomeController@index');

    Route::get('test', 'HomeController@test');

    /*
     * Pages
     */
    Route::get('faq', 'HomeController@faq');
    Route::get('about', 'HomeController@about');
    Route::get('contact', 'HomeController@contact');
    Route::get('testimonials', 'HomeController@testimonials');
    Route::get('join', 'HomeController@apply');

    /*
     * Actions
     */
    Route::get('language/{language}', 'HomeController@switchLanguage');
    Route::post('allows_cookie', 'HomeController@allowsCookie');
    Route::post('validated', 'HomeController@validated');

    /*
     * Profiles
     */
    Route::get('@{name}', 'ProfileController@showProfile');

    /*
     * Series
     */
    Route::get('series', 'SeriesController@showSeries');
    Route::get('series/{name}', 'SeriesController@show');
    Route::get('series/{name}/episode/{episode}', 'SeriesController@showLesson');

    /*
     * Lessons Catalog
     */
    Route::get('lessons', 'SeriesController@showLessons');
    Route::get('lessons/difficulty', 'SeriesController@sortLessonsInDifficulty');
    Route::get('lessons/type', 'SeriesController@sortLessonsInType');
    Route::post('lessons/{lesson}/comments/{page}', 'SeriesController@fetchMoreComments');

    /*
     * Tags routes
     */
    Route::get('tags', 'HomeController@showTags');
    Route::get('tags/{tag}', 'HomeController@showTag');

    /*
     * Skills
     */
    Route::get('skills/{skill}', 'HomeController@showSkill');

    /*
     * Settings routes
     */
    Route::get('settings', 'ProfileController@showSettings');
    Route::post('settings', 'ProfileController@saveAccountSettings');
    // Notification related
    Route::get('settings/notification', 'ProfileController@showNotificationSettings');
    Route::post('settings/notification/subscribe', 'ProfileController@subscribe');
    Route::post('settings/notification/unsubscribe', 'ProfileController@unsubscribe');
    // Subscription related
    Route::get('settings/subscription', 'ProfileController@showSubscription');
    // Level related
    Route::get('settings/level', 'ProfileController@showLevel');
    // Profile related
    Route::get('profile', 'ProfileController@myProfile');
    Route::get('update-account', 'ProfileController@refresh');

    /*
     * Search ajax
     */
    Route::post('search/{keyword}', 'HomeController@searchEverything');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    /*
     * Watch laters
     */
    Route::get('laters', 'UserController@showWatchLaters');
    Route::post('series/watch_later/{series}', 'UserController@addSeriesToWatchLater');
    Route::post('lessons/watch_later/{lesson}', 'UserController@addLessonToWatchLater');
    Route::post('series/unwatch_later/{series}', 'UserController@removeSeriesFromWatchLater');
    /*
     * Favorites
     */
    Route::get('favorites', 'UserController@showFavorites');
    Route::post('series/favorite/{series}', 'UserController@addSeriesToFavorite');
    Route::post('lessons/favorite/{lesson}', 'UserController@addLessonToFavorite');
    Route::post('series/unfavorite/{series}', 'UserController@removeSeriesFromFavorite');

    /*
     * Notifications
     */
    Route::post('notify/{series}', 'UserController@seriesNotify');
    Route::post('series/cancel/{series}', 'UserController@cancelNotification');

    /*
     * Watched
     */
    Route::put('lessons/completed/{lesson}', 'UserController@watchedLesson');
    Route::get('history', 'UserController@showHistory');

    /*
     * Comments
     */
    Route::post('lessons/discuss/{lesson}', 'UserController@submitLessonComment');

    /*
     * Comment likes
     */
    Route::put('comments/like/{comment}', 'UserController@likeComment');
    Route::post('comments/like_list/{comment}', 'UserController@fetchLikeList');

    Route::post('comments/upload_image', 'UserController@uploadImage');
});

Route::group(['middleware' => ['web', 'auth', 'tutor']], function () {
    Route::get('publish/lessons', 'HomeController@showLessons');
    Route::get('publish/lessons/create', 'HomeController@showCreateLesson');
    Route::get('publish/lessons/edit/{lesson}', 'HomeController@showEditLesson');
    Route::post('publish/lessons/create', 'HomeController@createLesson');
    Route::post('publish/lessons/edit/{lesson}', 'HomeController@updateLesson');
    Route::get('publish/lessons/search/{keyword}', 'HomeController@searchLessons');

});

Route::group(['middleware' => ['web', 'auth', 'manager']], function () {
    // Index overview
    Route::get('manage', 'ManageController@index');
    // Series related
    Route::get('manage/series', 'ManageController@showSeries');
    Route::get('manage/series/create', 'ManageController@showCreateSeries');
    Route::get('manage/series/edit/{series}', 'ManageController@showEditSeries');
    Route::post('manage/series/create', 'ManageController@createSeries');
    Route::post('manage/series/edit/{series}', 'ManageController@updateSeries');
    Route::get('manage/series/search/{keyword}', 'ManageController@searchSeries');
    Route::delete('manage/series/{series}', 'ManageController@deleteSeries');

    // Lessons related
    Route::get('manage/lessons', 'ManageController@showLessons');
    Route::get('manage/lessons/create', 'ManageController@showCreateLesson');
    Route::get('manage/lessons/edit/{lesson}', 'ManageController@showEditLesson');
    Route::post('manage/lessons/create', 'ManageController@createLesson');
    Route::post('manage/lessons/edit/{lesson}', 'ManageController@updateLesson');
    Route::get('manage/lessons/search/{keyword}', 'ManageController@searchLessons');
    Route::delete('manage/lessons/{lesson}', 'ManageController@deleteLesson');

    // Skills related
    Route::get('manage/skills', 'ManageController@showSkills');
    Route::get('manage/skills/edit/{skill}', 'ManageController@showEditSkill');
    Route::post('manage/skills/edit/{skill}', 'ManageController@updateSkill');

    // Users related
    Route::get('manage/users', 'ManageController@showUsers');
    Route::put('manage/users/promote/{user}', 'ManageController@promoteUser');

    // Comments related
    Route::get('manage/comments', 'ManageController@showComments');
    Route::get("manage/comments/search/{keyword}", 'ManageController@searchComments');
    Route::delete('manage/comments/{comment}', 'ManageController@deleteComment');
});