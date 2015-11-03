'use strict';

var gulp = require('gulp');
var gulpLoadPlugins = require('gulp-load-plugins');
var plugins = gulpLoadPlugins();

var argv = require('yargs').argv;

var mainBowerFiles = require('main-bower-files');

var LessPluginCleanCSS = require("less-plugin-clean-css"),
    cleancss = new LessPluginCleanCSS({advanced: true});


gulp.task('css:web', function() {
    return gulp.src('app/Resources/less/web/main.less')
        .pipe(plugins.if(!argv.production, plugins.sourcemaps.init()))
        .pipe(plugins.less({
            plugins: [cleancss]
        }))
        .on('error', plugins.notify.onError("Less error: <%= error.file %> <%= error.message %>"))
        .pipe(plugins.if(!argv.production, plugins.sourcemaps.write()))
        .pipe(plugins.addSrc.prepend(mainBowerFiles()))
        .pipe(plugins.ignore.include('*.css'))
        .pipe(plugins.ignore.exclude('**/bootstrap.css'))
        .pipe(plugins.concat('web.css'))
        .pipe(plugins.if(argv.production, plugins.minifyCss()))
        .on('error', plugins.notify.onError("Minify error: <%= error.file %> <%= error.message %>"))
        .pipe(gulp.dest('web/css/'))
        .pipe(plugins.notify('CSS build finished'));
});

gulp.task('css:admin', function() {
    return gulp.src('app/Resources/less/admin/main.less')
        .pipe(plugins.if(!argv.production, plugins.sourcemaps.init()))
        .pipe(plugins.less({
            plugins: [cleancss]
        }))
        .on('error', plugins.notify.onError("Less error: <%= error.file %> <%= error.message %>"))
        .pipe(plugins.if(!argv.production, plugins.sourcemaps.write()))
        .pipe(plugins.addSrc.prepend(mainBowerFiles()))
        .pipe(plugins.ignore.include('*.css'))
        .pipe(plugins.ignore.exclude('**/bootstrap.css'))
        .pipe(plugins.concat('admin.css'))
        .pipe(plugins.if(argv.production, plugins.minifyCss()))
        .on('error', plugins.notify.onError("Minify error: <%= error.file %> <%= error.message %>"))
        .pipe(gulp.dest('web/css/'))
        .pipe(plugins.notify('CSS build finished'));
});

gulp.task('jshint', function(){
    return gulp.src('app/Resources/js/**/*.js')
        .pipe(plugins.jshint())
        .pipe(plugins.jshint.reporter('jshint-stylish'))

});

gulp.task('js', ['jshint'], function() {
    return gulp.src([
            'app/Resources/js/web.js',
            'app/Resources/js/main.js',
            'app/Resources/js/admin.js'
        ])
        .pipe(plugins.if(!argv.production, plugins.sourcemaps.init()))
        .pipe(plugins.es6ModuleTranspiler({
            formatter: 'bundle'
        }))
        .pipe(plugins.babel())
        .on('error', plugins.notify.onError("Babel error: <%= error.file %> <%= error.message %>"))
        .pipe(plugins.if(!argv.production, plugins.sourcemaps.write()))
        .pipe(plugins.if(argv.production, plugins.uglify()))
        .on('error', plugins.notify.onError("Uglify error: <%= error.file %> <%= error.message %>"))
        .pipe(plugins.flatten())
        .pipe(gulp.dest('web/js/'))
        .pipe(plugins.notify('JS build finished'));
});

gulp.task('js:libs', function() {
    return gulp.src(mainBowerFiles())
        .pipe(plugins.ignore.include('*.js'))
        .pipe(plugins.concat('libs.js'))
        .pipe(plugins.if(argv.production, plugins.uglify()))
        .on('error', plugins.notify.onError("Error: <%= error.file %> <%= error.message %>"))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('assets:fonts', function() {
    var fonts = ['ttf', 'woff', 'woff2', 'eot', 'svg'].join(',');

    return gulp.src(mainBowerFiles())
        .pipe(plugins.ignore.include('**/*.{'+ fonts +'}'))
        .pipe(plugins.addSrc('app/Resources/fonts/**/*.{'+ fonts +'}'))
        .pipe(plugins.flatten())
        .pipe(gulp.dest('web/fonts/'))
});

gulp.task('assets:images', function() {
    var images = ['jpg', 'jpeg', 'png', 'gif'].join(',');

    return gulp.src(mainBowerFiles())
        .pipe(plugins.ignore.include('**/*.{'+ images +'}'))
        .pipe(plugins.addSrc('app/Resources/img/**/*.{'+ images +'}'))
        .pipe(gulp.dest('web/img/'))
});

gulp.task('css', [
    'css:admin',
    'css:web'
]);

gulp.task('assets', [
    'assets:fonts',
    'assets:images'
]);

gulp.task('build', [
    'js:libs',
    'js',
    'css',
    'assets']
);

gulp.task('default', ['build'], function() {
    gulp.watch('app/Resources/less/web/**', ['css:web']);
    gulp.watch('app/Resources/less/admin/**', ['css:admin']);
    gulp.watch('app/Resources/js/**', ['js']);
    gulp.watch('app/Resources/img/**', ['assets:images']);
    gulp.watch('app/Resources/fonts/**', ['assets:fonts']);
});