/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*global Backbone, jQuery, _, ENTER_KEY */
var app = app || {};

(function ($) {
    'use strict';

    // The Application
    // ---------------

    // Our overall **AppView** is the top-level piece of UI.
    app.AppView = Backbone.View.extend({
        // Instead of generating a new element, bind to the existing skeleton of
        // the App already present in the HTML.
        el: '#cheat_sheet_canvas',
        // Our template for the line of statistics at the bottom of the app.
        nodeItemTemplate: _.template($('#node_item_template').html()),
        // Delegated events for creating new items, and clearing completed ones.
        events: {
        },
        // At initialization we bind to the relevant events on the `Todos`
        // collection, when items are added or changed. Kick things off by
        // loading any preexisting todos that might be saved in *localStorage*.
        initialize: function () {
        
            //var y = app.cheatsheet;
          //  y.bind('change', this.render);
            //var x = new app.cheatsheet({a: "hello"});
           // x.set({a: "b"});

        },
        render: function () {
            console.log("Created Cheatsheet");
        }
    });
})(jQuery);