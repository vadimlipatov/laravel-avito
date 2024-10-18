/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

$(document).on("click", ".phone-button", function () {
	const button = $(this);
	axios
		.post(button.data("source"))
		.then(function (response) {
			button.find(".number").html(response.data);
		})
		.catch(function (error) {
			console.error(error);
		});
});

$(".banner").each(function () {
	const block = $(this);
	const url = block.data("url");
	const format = block.data("format");
	const category = block.data("category");
	const region = block.data("region");

	axios
		.get(url, {
			params: {
				format: format,
				category: category,
				region: region,
			},
		})
		.then(function (response) {
			block.html(response.data);
		})
		.catch(function (error) {
			console.error(error);
		});
});

$(document).on("click", ".location-button", function () {
	const button = $(this);
	const target = $(button.data("target"));

	window.geocode_callback = function (response) {
		if (
			response.response.GeoObjectCollection.metaDataProperty
				.GeocoderResponseMetaData.found > 0
		) {
			target.val(
				response.response.GeoObjectCollection.featureMember["0"].GeoObject
					.metaDataProperty.GeocoderMetaData.Address.formatted
			);
		} else {
			alert("Unable to detect your address.");
		}
	};

	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(
			function (position) {
				const location =
					position.coords.longitude + "," + position.coords.latitude;
				const url =
					"https://geocode-maps.yandex.ru/1.x/?format=json&callback=geocode_callback&geocode=" +
					location;
				const script = $("<script>").appendTo($("body"));
				script.attr("src", url);
			},
			function (error) {
				console.warn(error.message);
			}
		);
	} else {
		alert("Unable to detect your location.");
	}
});
