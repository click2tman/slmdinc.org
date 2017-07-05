// Define Dependencies
var gulp          = require('gulp'),
    cleancss      = require('gulp-clean-css'),
    compass       = require('gulp-compass'),
    concat        = require('gulp-concat'),
    del           = require('del'),
    gutil         = require('gulp-util'),
    notify        = require('gulp-notify'),
    plumber       = require('gulp-plumber'),
    rename        = require('gulp-rename'),
    uglify        = require('gulp-uglify');

/**
 * Prevents Gulp from crashing upon error
 */
function onError(err) {
  gutil.beep();

  var error = new Error(err);
  error.showStack = true; // Set true to see stack when debugging error messages
  return error;
};

/**
 * Stylesheet Gulp Task
 */
gulp.task('style', function(){
  return gulp.src('./sass/**/**/*.scss')
    .pipe(plumber({
      errorHandler: onError
    }))
    .pipe(compass({
      css: './css',
      sass: './sass',
      image: './images',
      javascript: './js'
    }))
    .pipe(gulp.dest('./css'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(cleancss())
    .pipe(gulp.dest('./css'))
    .pipe(notify({ message: '/ -- Completed executing stylesheet task. -- /' }));
});

/**
 * Script Gulp Task
 */
gulp.task('script', function() {
  // Include/Exclude Bootstrap JavaScript as needed
  return gulp.src(['./bootstrap/assets/javascripts/bootstrap/affix.js',
                    './bootstrap/assets/javascripts/bootstrap/alert.js',
                    './bootstrap/assets/javascripts/bootstrap/button.js',
                    './bootstrap/assets/javascripts/bootstrap/carousel.js',
                    './bootstrap/assets/javascripts/bootstrap/collapse.js',
                    './bootstrap/assets/javascripts/bootstrap/dropdown.js',
                    './bootstrap/assets/javascripts/bootstrap/modal.js',
                    './bootstrap/assets/javascripts/bootstrap/tooltip.js',
                    './bootstrap/assets/javascripts/bootstrap/popover.js',
                    './bootstrap/assets/javascripts/bootstrap/scrollspy.js',
                    './bootstrap/assets/javascripts/bootstrap/tab.js',
                    './bootstrap/assets/javascripts/bootstrap/transition.js',
                    './js/footer.js'])
    .pipe(plumber({ errorHandler: onError }))
    .pipe(concat('main.js'))
    .pipe(gulp.dest('./js'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(uglify())
    .pipe(gulp.dest('./js'))
    .pipe(notify({ message: '/ -- Completed executing JavaScript task. -- /' }));
});

/**
 * Clean Gulp Task
 */
gulp.task('clean', function() {
  return del.sync(['./css/style.css', './js/main.js', './css/style.min.css', './js/main.min.js']);
});

/**
 * Watch Gulp Task
 */
gulp.task('watch', function() {
  gulp.watch('./sass/**/**/*.scss', ['style']);
  gulp.watch('./js/*.js', ['script']);
});

/**
 * Default Gulp Task
 */
gulp.task('default', ['clean'], function() {
    gulp.start('style', 'script');
});
