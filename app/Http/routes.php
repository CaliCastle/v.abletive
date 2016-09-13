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

/**
 * Routes can be visited for everyone
 */
Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', function () {
        return view('pages.welcome');
    });
    Route::get('/home', 'HomeController@index');

// In case we need to test something out real quick
//    Route::get('test', 'HomeController@test');
    
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

/**
 * Routes needed to be logged in
 */
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

/**
 * Routes for tutors only
 */
Route::group(['middleware' => ['web', 'auth', 'tutor']], function () {
    Route::group(['prefix' => 'publish/lessons'], function () {
        Route::get('/', 'HomeController@showLessons');
        Route::get('create', 'HomeController@showCreateLesson');
        Route::get('edit/{lesson}', 'HomeController@showEditLesson');
        Route::post('create', 'HomeController@createLesson');
        Route::post('edit/{lesson}', 'HomeController@updateLesson');
        Route::get('search/{keyword}', 'HomeController@searchLessons');
    });
});

/**
 * Routes for managers only
 */
Route::group(['middleware' => ['web', 'auth', 'manager']], function () {
    Route::get('report', function () {
        Artisan::call('report:daily');    
    });
    
    Route::group(['prefix' => 'manage'], function () {
        // Index overview
        Route::get('/', 'ManageController@index');
        
        // Series related
        Route::group(['prefix' => 'series'], function () {
            Route::get('/', 'ManageController@showSeries');
            Route::get('create', 'ManageController@showCreateSeries');
            Route::get('edit/{series}', 'ManageController@showEditSeries');
            Route::post('create', 'ManageController@createSeries');
            Route::post('edit/{series}', 'ManageController@updateSeries');
            Route::get('search/{keyword}', 'ManageController@searchSeries');
            Route::delete('{series}', 'ManageController@deleteSeries');
        });

        // Lessons related
        Route::group(['prefix' => 'lessons'], function () {
            Route::get('/', 'ManageController@showLessons');
            Route::get('create', 'ManageController@showCreateLesson');
            Route::get('edit/{lesson}', 'ManageController@showEditLesson');
            Route::post('create', 'ManageController@createLesson');
            Route::post('edit/{lesson}', 'ManageController@updateLesson');
            Route::get('search/{keyword}', 'ManageController@searchLessons');
            Route::delete('{lesson}', 'ManageController@deleteLesson');
        });

        // Skills related
        Route::group(['prefix' => 'skills'], function () {
            Route::get('/', 'ManageController@showSkills');
            Route::get('edit/{skill}', 'ManageController@showEditSkill');
            Route::post('edit/{skill}', 'ManageController@updateSkill');
        });

        // Users related
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'ManageController@showUsers');
            Route::put('promote/{user}', 'ManageController@promoteUser');
            Route::get('search/{keyword}', 'ManageController@searchUsers');
        });

        // Comments related
        Route::group(['prefix' => 'comments'], function () {
            Route::get('/', 'ManageController@showComments');
            Route::get("search/{keyword}", 'ManageController@searchComments');
            Route::delete('{comment}', 'ManageController@deleteComment');
        });

        // Examinations related
        // TODO: New Feature of Examinations
        Route::get('examinations', 'ManageController@showExaminations');
        Route::get('examination/create', 'ManageController@showCreateExamination');
        Route::post('examination/create', 'ManageController@createExamination');
        Route::get('examination/{examination}', 'ManageController@showEditExamination');
        Route::post('examination/{examination}', 'ManageController@updateExamination');
        Route::delete('examination/{examination}', 'ManageController@deleteExamination');

        Route::get('examination/{examination}/questions', 'ManageController@showExamQuestions');
        Route::get('examination/{examination}/questions/create', 'ManageController@showCreateQuestion');
        Route::post('examination/{examination}/questions/create', 'ManageController@createQuestion');
        Route::get('questions/{question}', 'ManageController@showEditQuestion');
        Route::post('questions/{question}', 'ManageController@updateQuestion');
        Route::delete('question/{question}', 'ManageController@deleteQuestion');
    });
});

/**
 * API routes
 */
Route::group(['middleware' => ['web']], function () {
    // JSON api related
    Route::group(['prefix' => 'api'], function () {
        Route::get('catalogs', 'APIController@showCatalogs');
        Route::get('index', 'APIController@showIndex');
        Route::get('series/{series}', 'APIController@showSeries');
    });

    // tvOS related
    Route::group(['prefix' => 'tvOS/templates'], function () {
        Route::get('Index.xml', 'APIController@showIndexTVML');
        Route::get('Series.{series}.xml', 'APIController@showSeriesTVML');
        Route::get('Skill.{skill}.xml', 'APIController@showSkillTVML');
        Route::get('Tutor.{tutor}.xml', 'APIController@showTutorTVML');
        Route::get('Login.xml', 'APIController@showLoginTVML');
        Route::get('MySeries.xml', 'APIController@showMySeriesTVML');
    });
    Route::get('tvOS/search/{keyword}', 'APIController@searchContent');
});