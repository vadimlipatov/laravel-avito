/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

$("#flash-overlay-modal").modal();

$(document).on("click", ".phone-button", function () {
	const button = $(this);
	axios
		.post(button.data("source"))
		.then(function (response) {
			button.find(".number").html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
});
