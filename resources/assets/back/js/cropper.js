;(function(window,$){

    var Cropper = function(options){

        var options = options || {};

        this.format         = options.format || 'png'; // jpeg, png
        this.type           = options.type || 'square'; // square, circle
	    this.width          = options.width || 200;
	    this.height         = options.height || 200;
		this.boundaryWidth  = options.boundaryWidth || 250;
		this.boundaryHeight = options.boundaryHeight || 250;


	    this.$uploadtrigger = $('#thumb-upload'); // file input element
        this.$thumbcurrent = $('#thumb-current'); // Current thumbnail image
        this.$cropperpreview = $('#thumb-preview');
        this.$datauri = $('#thumb-datauri');
        this.$originaldatauri = $('#originalthumb-datauri');

        // points
        this.$topLeftX = $('#crop_topLeftX');
        this.$topLeftY = $('#crop_topLeftY');
        this.$bottomRightX = $('#crop_bottomRightX');
        this.$bottomRightY = $('#crop_bottomRightY');
        this.$zoom = $('#crop_zoom');

        this._init();
    };

    Cropper.prototype = {

        _init: function(){

            var self = this;

            // The upload file event will trigger the cropper display
            // If cropper is not initiated yet, we'll do it right now.
            this.$uploadtrigger.on('change', function(){

	            if(!self.$cropper) self._initCroppie();
                self._readFile(this);
                self.$thumbcurrent.hide();
            });

            // If on load the datauri is already set, we will set the cropper preview to reflect this
            var datauri = this.$originaldatauri.val();
            if(datauri)
            {
                if(!self.$cropper) self._initCroppie();
                self.$cropper.croppie('bind', {
                    url: datauri,
                    points: [self.$topLeftX.val(),self.$topLeftY.val(),self.$bottomRightX.val(),self.$bottomRightY.val()]
                }).then(function(){
                    self.$cropper.croppie('setZoom',self.$zoom.val());
                });


                self.$thumbcurrent.hide();
            }
        },

        _initCroppie: function()
        {
            var self = this;

	        this.$cropper = this.$cropperpreview.croppie({
                viewport: {
                    width: self.width,
                    height: self.height,
                    type: self.type
                },
                boundary: {
                    width: self.boundaryWidth,
                    height: self.boundaryHeight
                },
                enableExif: true,
                enforceBoundary: true
            });

            this.$cropper.on('update',this._debounce(function(e){
                self._saveCroppedImage(function(dataURI){
                    self.$datauri.val(dataURI);

                    var data = self.$cropper.croppie('get');

                    // Keep our crop points so we can restore the view if needed
                    self.$topLeftX.val(data.points[0]);
                    self.$topLeftY.val(data.points[1]);
                    self.$bottomRightX.val(data.points[2]);
                    self.$bottomRightY.val(data.points[3]);
                    self.$zoom.val(data.zoom);
                });
            },150));
        },

        _readFile: function(input) {

            var self = this;

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {

                    self.$originaldatauri.val(e.target.result);

                    self.$cropper.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        // Bind complete
                    });
                };

                reader.readAsDataURL(input.files[0]);
            }
        },

        _saveCroppedImage: function(callback)
        {
            var self = this;

            this.$cropper.croppie('result', {
                type: 'canvas',
                size: 'viewport',
                format: self.format,
            }).then(function (dataURI){
                if(callback) callback.call(self.$cropper,dataURI);
            });
        },

        _debounce: function(func, wait, immediate) {
            var timeout;
            return function () {
                var context = this, args = arguments;
                var later = function () {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }

    };

    window.Cropper = Cropper;

})(window,jQuery);