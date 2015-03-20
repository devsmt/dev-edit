define(function(){
    function add(a, b){
        return a + b;
    }
    return {
        add:add
    };
});
require(['MyMath'], function(MyMath){
    console.log(MyMath.add(1, 2));
});
