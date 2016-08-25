const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

elixir(function(mix) {
    mix
    	.sass('app.scss')
    	.webpack('app.js')
    	.copy('resources/assets/js/sw.js', 'public/sw.js');
});
