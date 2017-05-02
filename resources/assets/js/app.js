
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});


$(document).ready(function(){
	$('body .new_author').on('click', function(){
		var html =  `<input style="margin-top: 5px" type="text" class="form-control" name="new_author" placeholder="Tên đồng tác giả"/>`;
		$('#form_create_new_article').find('.add_author').append(html);
	})
	$('body .new_citation').on('click', function(){
		var ci_html =  `<input style="margin-top: 5px" type="text" class="form-control" name="new_citation" placeholder="Tiêu đề citation"/>`;
		console.log(ci_html);
		$('#form_create_new_article').find('.add_citation').append(ci_html);
	})
});