'use strict';

//npm install --save-dev gulp gulp-include gulp-imagemin gulp-minify gulp-wp-pot gulp-sort gulp-zip
//npm install --save-dev gulp

// Dependencies
var gulp = require('gulp');  
var include = require('gulp-include');
var minify = require('gulp-minify');
var notify = require('gulp-notify'); // Sends message notification to you
var wpPot = require('gulp-wp-pot'); // For generating the .pot file.
var sort = require('gulp-sort'); // Recommended to prevent unnecessary changes in pot-file.
var zip = require('gulp-zip');
var replace = require('gulp-replace');

// Settings
var wpTheme = "./";
var plugin_name = 'roi-hunter-easy-for-woocommerce';
var zip_files = ['./**/*', '!node_modules/**/*','!.vscode/**/*', '!node_modules', '!gulpfile.js', '!.gitignore', '!package.json', '!package-lock.json', '!*.zip', '!todo.txt', '!*.md'];

// Translation related.
var text_domain             = 'roi-hunter-easy'; // Your textdomain here.
var translationFile         = 'roi-hunter-easy.pot'; // Name of the transalation file.
var packageName             = 'roi-hunter-easy'; // Package name.
var translationDestination  = './languages'; // Where to save the translation files.
var bugReport               = 'https://kybernaut.cz/kontakt/'; // Where can users report bugs.
var lastTranslator          = 'Karolína Vyskočilová <karolina@kybernaut.cz>'; // Last translator Email ID.
var team                    = 'Kybernaut <karolina@kybernaut.cz>'; // Team's Email ID.

// Watch files paths.
var projectPHPWatchFiles    = './**/*.php'; // Path to all PHP files.

/** 
 * INCLUDE JS SCRIPTS AND MINIFY
 * https://www.npmjs.com/package/gulp-include
 * https://www.npmjs.com/package/gulp-minify
 */

gulp.task( "js", function() {
    //console.log( '-- including files to assets/js/admin.js' );
    console.log( '-- minifying to assets/js/admin.min.js' );
    gulp.src( wpTheme + 'assets/js/admin.js' )
      .pipe(minify({
          ext:{
              //src:'.js',
              min:'.min.js'
          }
      }))
      .pipe( gulp.dest( 'assets/js' ) );
      //console.log( '-- including files to assets/js/public.js' );
      console.log( '-- minifying to assets/js/public.min.js' );
    gulp.src( wpTheme + 'assets/js/public.js' )
      .pipe(minify({
          ext:{
              //src:'.js',
              min:'.min.js'
          }
      }))
      .pipe( gulp.dest( 'assets/js' ) );
  });

/**
  * WP POT Translation File Generator.
  * https://github.com/ahmadawais/WPGulp/blob/master
  *
  * * This task does the following:
  *     1. Gets the source of all the PHP files
  *     2. Sort files in stream by path or any custom sort comparator
  *     3. Applies wpPot with the variable set at the top of this file
  *     4. Generate a .pot file of i18n that can be used for l10n to build .mo file
  */
 gulp.task( 'translate', function () {
    return gulp.src( projectPHPWatchFiles )
        .pipe(sort())
        .pipe(wpPot( {
            domain        : text_domain,
            package       : packageName,
            bugReport     : bugReport,
            lastTranslator: lastTranslator,
            team          : team
        } ))
       .pipe(gulp.dest(translationDestination + '/' + translationFile ))
       .pipe( notify( { message: 'TASK: "translate" Completed!', onLast: true } ) )

});

/**
 * Generate plugin instalable zip
 */
// activeBeProfile = production
gulp.task('zip', function() {
    gulp.start('js');
    gulp.start('translate');
    gulp.src( zip_files )
        .pipe(zip( plugin_name + '.zip' ))
        .pipe(gulp.dest('.'))
    }
);
// activeBeProfile = staging
gulp.task('zip-staging', function() {
    gulp.start('js');
    gulp.start('translate');
    gulp.src( zip_files )
        .pipe(replace("'activeBeProfile' => 'production'", "'activeBeProfile' => 'staging'"))
        .pipe(replace("https://goostav-fe.roihunter.com/", "https://goostav-fe-staging.roihunter.com/"))
        .pipe(zip( plugin_name + '_staging.zip' ))
        .pipe(gulp.dest('.'))
    }
);