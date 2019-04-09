//-- model ----------------------------------------
__app__.Models = __app__.Models || {};
(function () {
    __app__.Models.Cart = Backbone.Model.extend({
        url: '',
        //urlRoot: '/cart',//url a cui inviare/legegre i dati
        initialize: function() {
        },
        defaults: {
        },
        validate: function(attrs, options) {
        },
        parse: function(response, options)  {
            return response;
        }
        // BB assumes you are interacting with a RESTful API but allows you to override one method,
        // Backbone.sync(), if not. You tell your model where the resource is on the server (the URL)
        // and then you can just call save().
    });
})();
// uso save:
//user.save(userDetails, {
//  success: function (user) {
//    console.log(user.toJSON());
//  }
//});


//-- collection ----------------------------------------
__app__.Collections = __app__.Collections || {};
(function () {
    __app__.Collections.CartItems = Backbone.Collection.extend({
        model: __app__.Models.CartItems
    });
})();
