window.__app__ = {
    Models: {},
    Collections: {},
    Views: {},
    Routers: {},
    init: function () {
        // app init
    }
};
// app init event
$(document).ready(function () {
    __app__.init();
});
//-- router ----------------------------------------
__app__.Routers = __app__.Routers || {};
(function () {
    __app__.Routers.R = Backbone.Router.extend({
      routes: {
          "": "homeAction"
      },
      initialize: function(){},
      homeAction: function(){
        // init views
        app.homeView = new app.views.HomeView({
          el: $('#homeView')
        });
        //app.homeView.render();
        //app.homeView.delegateEvents();
      }
    });
})();
