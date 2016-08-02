var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

elixir(function(mix) {
    mix
    	.sass('app.scss')
    	.browserify('app.js')
    	.copy('resources/assets/js/sw.js', 'public/sw.js');
});
