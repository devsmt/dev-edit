"use strict";

var GiantCorp = window.GiantCorp || {};


GiantCorp.Config = {
    // configurations and CONST def
    MSG:{
        INVALID_VALUE: "Invalid value"
    },
    URL:{
        INVALID: "/errors/invalid.php"
    },
    CSS:{
        SELECTED: "selected"
    }

};
GiantCorp.Templates = {
};
GiantCorp.Common = {
    // A singleton with common methods used by all objects and modules.
};


GiantCorp.PageName = (function(){

    var conf = GiantCorp.Config;

    // Put your private variables and functions here
    var priv = 1;
    function add(a, b){
        return priv + a + b;
    }

    function validate(value) {
        if (!value) {
            alert(conf.MSG_INVALID_VALUE);
            location.href = conf.URL_INVALID;
        }
    }
    function toggleSelected(element) {
        if (hasClass(element, conf.CSS_SELECTED)) {
            removeClass(element, conf.CSS_SELECTED);
        } else {
            addClass(element, conf.CSS_SELECTED);
        }
    }

    // Here are the public methods
    return {
        // Initialization method.
        init: function() {
        }
    };
})();
//------------------------------------------------------------------------------
// main
//------------------------------------------------------------------------------
$(function(){
    // DOM is ready
    GiantCorp.PageName.init();
});

$(window).load(function(){
    // page has loaded
});
