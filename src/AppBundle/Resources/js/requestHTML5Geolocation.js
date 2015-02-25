var geolocator = new Geolocator();
var url = document.URL.replace(/\/$/, "");

geolocator.sendGeolocation(url, function (response) {
    if (response.success) {
        window.location.replace(url + '/main');
    } else if (!response) {
        alert('User geolocation not available');
    }
});