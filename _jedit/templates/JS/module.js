"use strict";
var MyMmodule = (function(){

    // Put your private variables and functions here
    var myprivate = 1;
    function add(a, b){
        return myprivate + a + b;
    }

    // Here are the public methods
    return {
        add: add
    };
})();

console.log(MyMmodule.add(1, 2));
