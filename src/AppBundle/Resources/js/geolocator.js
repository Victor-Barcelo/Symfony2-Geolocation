Geolocator = (function ($) {

    function Geolocator() {

        this.getGeolocation = function getGeolocation(cb) {
            var latitude,
                longitude;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        latitude = position.coords.latitude;
                        longitude = position.coords.longitude;
                        cb({
                            error: false,
                            latitude: latitude,
                            longitude: longitude
                        });
                    },
                    function (error) {
                        error.error = true;
                        cb(error);
                    });
            }
        };

        this.sendGeolocation = function sendGeolocation(url, cb) {
            this.getGeolocation(function (response) {
                if (!response.error) {
                    $.post(url, {latitude: response.latitude, longitude: response.longitude},
                        function (returnedData) {
                            cb(returnedData);
                        });
                } else {
                    cb(false);
                }
            });
        };

    }

    return Geolocator;
})($);