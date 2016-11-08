var InfiniteHelper = (function() {

    function InfiniteHelper() {

    }

    InfiniteHelper.prototype.LOADING_STARTED = 0;
    InfiniteHelper.prototype.CURRENT_PAGE = 1;
    InfiniteHelper.prototype.LOAD_MORE = 1;

    InfiniteHelper.prototype.setLoadLink = function(link) {
        this.loadLink = link;
        return this;
    }

    InfiniteHelper.prototype.setInfiniteRange = function(range) {
        this.range = range;
        return this;
    }

    InfiniteHelper.prototype.setQuery = function(query) {
        this.query = query;
        return this;
    }

    InfiniteHelper.prototype.onLoadingStart = function(callback) {
        this.loadingStartHandler = callback;
        return this;
    }

    InfiniteHelper.prototype.startListen = function(elemObject, listenerName, callback) {
        var self = this;
        elemObject.on(listenerName, function() {
            if (!self.LOADING_STARTED) {
                self.LOADING_STARTED = 1;

                $.get(self.loadLink, self.query, function (response) {
                    if (callback) {
                        callback(response);
                    }

                    self.LOADING_STARTED = 0;
                });
            }
        });
    }

    InfiniteHelper.prototype.startInfinite = function(callback) {
        var self = this;
            $(window).scroll(function () {
                if ($(window).scrollTop() >= $(document).height() - $(window).height() - self.range) {
                    if(self.loadingStartHandler)
                        self.loadingStartHandler(self);

                    if(!self.LOADING_STARTED && self.LOAD_MORE) {
                        self.LOADING_STARTED = 1;


                        $.get(self.loadLink, self.query, function (response) {
                            self.LOAD_MORE = false;
                            if (callback) {
                                callback(response);
                            }

                            self.LOADING_STARTED = 0;
                        });
                    }
                }
            })
    }


    return InfiniteHelper;
})();
