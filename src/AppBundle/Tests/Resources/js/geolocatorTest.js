var assert = chai.assert;
var geolocator = new Geolocator();

describe('geolocator.js', function () {

    it('should return user geolocation', function (done) {
        geolocator.getGeolocation(function (response) {
            assert.isFalse(response.error);
            done();
        });
    });

    it('should send to server user geolocation', function (done) {
        geolocator.sendGeolocation("", function (response) {
            assert.isTrue(response.success);
            done();
        });
    });

});




