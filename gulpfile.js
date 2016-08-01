var elixir = require('laravel-elixir');
var gulp = require('gulp');
var manifest = require('gulp-manifest');
var Elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

var Task = Elixir.Task;

new Task('manifest', function() {
	return gulp.src(['public/css/*.css', 'public/js/*.js', 'public/*.png'], { base: './public'})
			.pipe(manifest({
				hash: true,
				preferOnline: false,
				network: ['*'],
				filename: 'manifest.appcache',
				exclude: 'manifest.appcache',
				timestamp: true
			}))
			.pipe(gulp.dest('public'));
})
.watch('./resources/**');

elixir(function(mix) {
    mix.sass('app.scss');
    mix.browserify('app.js');
});
