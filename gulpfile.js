var gulp = require('gulp'),
		sass = require('gulp-sass'),
		browserSync = require('browser-sync'),
		uglify = require('gulp-uglifyjs'),
		concat = require('gulp-concat'),
		rename = require('gulp-rename'),
		cssnano = require('gulp-cssnano'),
		del = require('del'),
		imagemin = require('gulp-imagemin'),
		pngquant = require('imagemin-pngquant'),
		cache = require('gulp-cache'),
		autoprefixer = require('gulp-autoprefixer'),
		cssfont64 = require('gulp-cssfont64');

gulp.task('sass', function(){
	return gulp.src('app/sass/**/*.sass')
	.pipe(sass())
	.pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], {cascade: true}))
	.pipe(gulp.dest('app/css'))
	.pipe(browserSync.reload({stream: true}))
});

gulp.task('browser-sync', function(){
browserSync.init({
    proxy: "localhost/college/app/",
    port: 8000  
 });		
	browserSync({
		server: {
			baseDir: 'app'
		},
		notify: false
	});
});

gulp.task('scripts', function(){
	return gulp.src([
		'app/libs/jquery/dist/jquery.min.js'
	])
	.pipe(concat('libs.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest('app/js'))
});

gulp.task('css-libs', ['sass'], function(){
	return gulp.src('app/css/main.css')
	.pipe(cssnano())
	.pipe(rename({suffix: '.min'}))
	.pipe(gulp.dest('app/css'))
});

gulp.task('watch', ['css-libs', 'fontsConvert', 'browser-sync', 'scripts'], function(){
	gulp.watch('app/sass/**/*.sass', ['sass']);
	gulp.watch('app/*.html', browserSync.reload);
	gulp.watch('app/*.php', browserSync.reload);
	gulp.watch('app/js/**/*.js', browserSync.reload);
	gulp.watch('app/fonts/**/*', ['fontsConvert']);
});

gulp.task('img', function(){
	return gulp.src('app/img/**/*')
	.pipe(cache(imagemin({
		interplaced: true,
		progressive: true,
		svgoPlugins: [{removeViewBox: false}],
		use: [pngquant()]
	})))
	.pipe(gulp.dest('dist/img'));
});

gulp.task('clean', function(){
	return del.sync('dist');
});

gulp.task('build', ['clean', 'fontsConvert', 'img', 'css-libs', 'scripts'], function(){
	var buildCss = gulp.src([
		'app/css/*.css'
	])
	.pipe(gulp.dest('dist/css'))

	var buildFonts = gulp.src('app/fonts/**/*')
	.pipe(gulp.dest('dist/fonts'))

	var buildJs = gulp.src('app/js/**/*')
	.pipe(gulp.dest('dist/js'))

	var buildHTML = gulp.src('app/*.*')
	.pipe(gulp.dest('dist'));

	var buildCron = gulp.src('app/cron/*.*')
	.pipe(gulp.dest('dist/cron'));

	var buildPages = gulp.src('app/pages/*.*')
	.pipe(gulp.dest('dist/pages'));		
});

gulp.task('default', ['watch']);

gulp.task('clear', function(){
	return cache.clearAll();
});

gulp.task('fontsConvert', function () {
	return gulp.src(['app/fonts/*.woff', 'app/fonts/*.woff2'])
		.pipe(cssfont64())
		.pipe(gulp.dest('app/css'))
		.pipe(browserSync.stream());
});