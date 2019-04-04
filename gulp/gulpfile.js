//THIS SHOULD MATCH YOUR THEME NAME
var THEME = 'gathering';

var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var autoprefixer = require('gulp-autoprefixer');
var plumber = require('gulp-plumber');
var rename = require('gulp-rename');
var browserSync = require('browser-sync').create();

var sass = require('gulp-sass');

//file paths
var SRC_PATH = './';
var THEME_PATH = '../wp-content/themes/'+THEME+'/';
var SCRIPTS_PATH = SRC_PATH+'scripts/*.js';
var SCRIPTS_WATCH_PATH = SRC_PATH+'scripts/**/*.js';

var SCSS_PATH = SRC_PATH+'scss/styles.scss';
var SCSS_WATCH_PATH = SRC_PATH+'scss/**/*.scss';


/**
 * SCSS/SASS task
 */

gulp.task('sass-styles', function() {
    console.log('Styling...');

    return gulp.src(SCSS_PATH)
        .pipe(plumber(function(err){
          //this function will run WHEN an error occurs in this task
          console.log('Styles Task Error');
          console.log(err);
          this.emit('end'); //this line will stop this task chain but continue running gulp
        }))
        .pipe(autoprefixer({
          browsers: ['last 2 versions']
        }))
        .pipe(sass().on('error', sass.logError))
        .pipe(rename({
            basename: "dev"
          }))
        .pipe(gulp.dest(THEME_PATH + '/styles'))
        .pipe(sass({
          outputStyle: 'compressed'
        })
          .on('error', sass.logError))
        .pipe(rename({
            basename: "live"
          }))
        .pipe(gulp.dest(THEME_PATH + '/styles'));
});


// Scripts
gulp.task('scripts', function() {
    console.log('Scripting...');

    return gulp.src([SRC_PATH+'scripts/variables.js',SRC_PATH+'scripts/functions.js',SRC_PATH+'scripts/site.js', SCRIPTS_PATH])
        .pipe(plumber(function(err){
          //this function will run WHEN an error occurs in this task
          console.log('Styles Task Error');
          console.log(err);
          this.emit('end'); //this line will stop this task chain but continue running gulp
        }))
        .pipe(concat('dev.js'))
        .pipe(gulp.dest(THEME_PATH + '/js'))
        .pipe(uglify())
        .pipe(concat('live.js'))
        .pipe(gulp.dest(THEME_PATH + '/js'));
});

gulp.task('browser-sync', function(){
	console.log('Syncing...');
	browserSync.init({
		proxy: "http://"+THEME+".local/"
	});

	gulp.watch(THEME_PATH + "/styles/*.css").on('change', browserSync.reload);
	gulp.watch(THEME_PATH + "/js/*.js").on('change', browserSync.reload);
	gulp.watch(THEME_PATH + "/**/*.php").on('change', browserSync.reload);
});

// Default task, will run all common tasks at once
gulp.task('default', ['sass-styles','scripts','browser-sync'], function() { //
    console.log('Gulping...');
});

// Setup gulp dev server
gulp.task('watch', ['default'], function() {
    console.log('Watching you...');
    gulp.watch(SCRIPTS_WATCH_PATH, ['scripts']);
    gulp.watch(SCSS_WATCH_PATH, ['sass-styles']);
});
