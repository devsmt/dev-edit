
//-- view -----------------------------------------------
// dispatching events that originate from the UI and propagate Model events
__app__.Views = __app__.Views || {};
(function () {
    __app__.Views.Cart = Backbone.View.extend({
        tagName: 'div',// usati da BB per puntare a this.$el
        id: 'cartView',
        initialize: function () {
          // collegamento con altri model e sub-view
          // binding sulle modifiche al model
          this.listenTo(this.model, 'change', this.render);
          // prima di initialize viene fatta _ensureElement()
          // this.setElement( this.el );
          // appena dopo initilize scatta la delegateEvents(),
          // quindi il dom e this.$el deve essere presente
          this.render();
        },
        // mappa evento UI => sub selector => this.method
        // delega su elementi contenuti nel id corrente
        events: {
            "click .toggle":  "onToggleClick",
            //"dblclick .view": "onViewDblclick",
            //"keypress .edit": "onEditEnter",
            //"blur .edit":     "onEditBlur"
        },
        //NOTA: fondamentale ridefinirla
        render: function () {
            var data = this.model.attributes;
            var html = template_func(data);
            this.$('#CartView').html(html);
            //this.delegateEvents();// a seconda del contesto può essere necessario

            // subviews: preferibile configurarle per renderle manualmente jit
            //this.collection.each(function(m){
            //    var tmpView = new subView({ model: m });
            //    this.$el.append(tmpView.render().el);
            //}, this);
        },
        //-- altri gestori di eventi --------------------------
        onEditEnter: function(){
            // aggiorna il model in risposta a input utente
            this.model.set({name:$("#name").val()});
        }
    });
})();
// uso:
//   var person = new Backbone.Model({name:''});
//   messageView = new MessageView({el: $('#message-container'), model: person });