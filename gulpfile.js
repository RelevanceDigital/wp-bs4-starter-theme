var gulp = require('gulp');
var gutil = require('gulp-util');
var rename = require('gulp-rename');
var replace = require('gulp-replace');
var insertLines = require('gulp-insert-lines');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');
var imagemin = require('gulp-imagemin');
var imageminPngquant = require('imagemin-jpeg-recompress');
var imageminJpegRecompress = require('imagemin-jpeg-recompress');
var browserSync = require('browser-sync').create();

gulp.task('install', function() {
    gulp.src('node_modules/bootstrap/scss/_variables.scss')
        .pipe(rename('_variables-reference.scss'))
        .pipe(gulp.dest('scss'));

    gulp.src(['node_modules/bootstrap/scss/bootstrap.scss'])
        .pipe(replace('@import "', '@import "../node_modules/bootstrap/scss/'))
        .pipe(insertLines({
            'before': '@import "../node_modules/bootstrap/scss/variables";',
            'lineBefore': '@import "custom";'
        }))
        .pipe(rename('_bootstrap.scss'))
        .pipe(gulp.dest('scss'));

    gulp.src(['node_modules/slick-carousel/slick/*.scss'])
        .pipe(rename({
            prefix: "_"
        }))
        .pipe(gulp.dest('scss'));
});

gulp.task('log', function() {
    gutil.log('== My Log Task ==')
});

gulp.task('sass', function() {
    gulp.src('scss/**/*.scss')
        .pipe(sass({outputStyle: 'expanded'}))
        .on('error', gutil.log)
        .pipe(sourcemaps.write('css'))
        .pipe(gulp.dest('css'))
        .pipe(browserSync.reload({stream:true}));

    gulp.src('scss/**/*.scss')
        .pipe(sass({outputStyle: 'compressed'}))
        .on('error', gutil.log)
        .pipe(sourcemaps.write('assets/css'))
        .pipe(gulp.dest('assets/css'))
        .pipe(browserSync.reload({stream:true}))
});

gulp.task('js', function() {
    gulp.src([
        'node_modules/lazysizes/**/*.min.js',
        'node_modules/slick-carousel/**/*.min.js',
        'js/*.js',
    ])
        .pipe(uglify())
        .on('error', gutil.log)
        .pipe(gulp.dest('assets/js'))
        .pipe(browserSync.reload({stream:true}))
});

gulp.task('php', function() {
    gulp.src('**/*.php')
        .pipe(browserSync.reload({stream:true}))
});

gulp.task('imagesreduced', function () {
    return gulp.src('images/**/*.{png,jpeg,jpg,svg, gif}')
        .pipe(imagemin())
        .pipe(gulp.dest('assets/images'));
});

gulp.task('imagescompress', function () {
    return gulp.src('images/**/*.{png,jpeg,jpg,svg, gif}')
        .pipe(imagemin([ //override the default by setting our own
            //because we overrode, we want to call all the defaults that were called behind the scene
            imagemin.optipng(), //call default for imagemin
            imagemin.svgo(), //call default for imagemin
            imagemin.gifsicle(),//call default for imagemin
            imagemin.jpegtran(),//call default for imagemin
            imageminPngquant(), //call the lossy compression for PNG
            imageminJpegRecompress()//call the lossy compression for JPEG/JPG
        ]))
        .pipe(gulp.dest('assets/images'));
});

// Static server
/*
gulp.task('browser-sync', function() {
    browserSync.init({
        server: {
            baseDir: "./"
        }
    });
});
*/

// Proxy
gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "localhost:8080/"
    });
});


gulp.task('watch', function() {
    gulp.watch('js/*.js', ['js']);
    gulp.watch('scss/**/*.scss', ['sass']);
    gulp.watch('**/*.php', ['php']);
    gulp.watch('images/**/*.{png,jpeg,jpg,svg,gif}', ['imagescompress'])
});

gulp.task('default', ['js', 'sass', 'browser-sync', 'watch']);