/* jshint ignore:start */
const gulp = require('gulp');
const args = require('yargs').argv;
var config = require('./gulp.config')();
const gulpLoadPlugins = require('gulp-load-plugins');
const runSequence = require('run-sequence');
const del = require('del');
const browserSync = require("browser-sync");
const php = require('gulp-connect-php');
const $ = gulpLoadPlugins();


// --------------------------------------------------------------------
// Task: Server
// --------------------------------------------------------------------

gulp.task('php', function() {
  php.server({
    base: 'dist/',
    port: 8010,
    keepalive: true
  });
});

gulp.task('browser-sync',['php'], function() {
  browserSync({
    // Settings browser-sync
    proxy: '127.0.0.1:8010',
    port: 8080,
    open: true,
    notify: false,
    reloadDelay: 1000
  });
});

gulp.task('reload', function () {
  log('browserSync reload');
  browserSync.reload();
});

// --------------------------------------------------------------------
// Task: Build php(html)
// --------------------------------------------------------------------
gulp.task('build:php', () => {
  log('Build PHP');
  return gulp.src(config.src.php)
    .pipe($.plumber())  
    .pipe($.useref({searchPath: ['.']}))     
    .pipe($.if(['*.js'], $.uglify()))
    /*.pipe($.if('*.css', $.cssnano({safe: true, autoprefixer: false})))
    .pipe($.if('*.html', $.htmlmin({collapseWhitespace: true})))*/
    .pipe(gulp.dest(config.build.php));
});

gulp.task('copy:lib_php', () => {
  log('Copy lib php');
  return gulp.src('lib/*.php')
    .pipe(gulp.dest('dist/lib/'));
});

gulp.task('copy:configs_php', () => {
  log('Copy configs php');
  return gulp.src('configs/*.php')
    .pipe(gulp.dest('dist/configs/'));
});


// --------------------------------------------------------------------
// Task: Build js
// --------------------------------------------------------------------
gulp.task('build:js', function() {
  log('Build config php');  
  return gulp.src(config.src.js)
   /*.pipe($.if(args.verbose, $.print()))// gulp vet --verbose (print vet folders)
    .pipe($.jscs())
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish', { verbose: true }))
    .pipe($.jshint.reporter('fail'))*/
   /* .pipe($.sourcemaps.init())*/
    /*.pipe($.babel({
            presets: ['es2015']
        }))*/
    .pipe($.concat('main.js'))
    /*.pipe($.sourcemaps.write('.')) */ 
    .pipe(gulp.dest(config.build.js));
});

// --------------------------------------------------------------------
// Task for compilation less to css
// --------------------------------------------------------------------
gulp.task('build:styles', ['clean-styles'], function() {
  log('Compiling Less --> CSS');
  return gulp
    .src(config.src.less_src)
    .pipe($.plumber())
    .pipe($.less())
   /* .pipe($.autoprefixer({browsers:['last 2 version','> 5%']}))*/
    .pipe($.autoprefixer({browsers: ['> 1%', 'last 2 versions', 'Firefox ESR']}))
    .pipe(gulp.dest(config.build.css_dist));
});

// Task for clean folders
gulp.task('clean-styles', function() {
  log('Clean styles');  
  var files = config.build.css_dist + '*.css';
  clean(files);
})

gulp.task('copy:fonts', () => {
  log('Copy fonts');  
  return gulp.src(config.src.fonts)
    .pipe(gulp.dest(config.build.fonts));
});

// --------------------------------------------------------------------
// Task for compilation less to css
// --------------------------------------------------------------------
gulp.task('image:build', function () {
    log('Build image');
    gulp.src(config.src.img) //Выберем наши картинки
        .pipe($.imagemin({ //Сожмем их
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(config.build.img)) //И бросим в build        
});
// --------------------------------------------------------------------
// Task: Watch
// --------------------------------------------------------------------
gulp.task('build:app', () => {
  log('Build app');  
  return new Promise(resolve => {
    dev = false;
    runSequence(
        [
            'build:php',
            'build:js',
            'build:styles',            
            'copy:fonts',
            'copy:lib_php',
            'copy:configs_php'
        ], resolve);
  });
});

// --------------------------------------------------------------------
// Task: Watch
// --------------------------------------------------------------------
gulp.task('watch', function(){
    log('Watch');
    $.watch([config.watch.php], function(event, cb) {
        gulp.start(['build:php','reload']);        
    });
    $.watch([config.watch.php_lib], function(event, cb) {
        gulp.start('copy:lib_php');        
    });
    $.watch([config.watch.php_configs], function(event, cb) {
        gulp.start('copy:configs_php');        
    });      
    $.watch([config.watch.js], function(event, cb) {
        gulp.start(['build:js','reload']);
    });      
    $.watch([config.watch.style_less], function(event, cb) {
        gulp.start(['build:styles','reload']);
    });    
});

// --------------------------------------------------------------------
// Task: Default
// --------------------------------------------------------------------

/*gulp.task('default', () => {
  return new Promise(resolve => {
    dev = false;
    runSequence(
        [
            'build:php',
            'build:js',
            'build:styles',            
            'copy:fonts',
            'copy:lib_php',
            'copy:configs_php'
        ], resolve);
  });
});*/

gulp.task('default', ['browser-sync','watch']);

// --------------------------------------------------------------------
// Task: Function log
// --------------------------------------------------------------------
function log(msg) {
  if(typeof(msg) === 'object') {
    for (var item in msg ) {
      if (msg.hasOwnProperty(item)) {
        $.util.log($.util.colors.blue(msg[item]));
      }
    }
  } else {
    $.util.log($.util.colors.blue(msg));
  }
}

// --------------------------------------------------------------------
// Function to clean the file according to a predetermined path
// --------------------------------------------------------------------
function clean(path) {
  log('cleaning:' + $.util.colors.blue(path));
  del(path);
}

/* jshint ignore:end */