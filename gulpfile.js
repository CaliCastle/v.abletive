var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    mix.styles([
            'normalize.css',
            'font-awesome.min.css',
            'app.css',
            'animate.min.css',
            'sweetalert.css',
            'dialog.css',
            'dialog-ricky.css'
        ], 'public/assets/styles.css', 'public/css')
        .scripts([
            'jquery.min.js',
            'classie.js',
            'notificationFx.js',
            'sweetalert.min.js',
            'dialogFx.js',
            'main.js'
        ], 'public/assets/scripts.js');

    mix.version([
            'public/assets/styles.css',
            'public/assets/scripts.js'
        ]);
});
