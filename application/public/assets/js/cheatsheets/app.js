/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*global $ */
/*jshint unused:false */
var app = app || {};
var ENTER_KEY = 13;
var ESC_KEY = 27;

$(function () {
    'use strict';
    $('input').keyup(function (e) {
        if (e.keyCode == 13) {
            $(this).trigger('enter');
        }
    });
    // kick things off by creating the `App`
    new app.AppView();
    new app.cheatsheetView();
    new app.cheatsnodeView();


    /*
     var cheatsheet = new app.cheatsheet({name: "IT cheatsheet", id: 45756});
     
     var cheat_nodes_model = new app.cheat_nodes_model({id: 4567, name: 'Exchange 2010', parent: cheatsheet});
     cheat_nodes_model.id = 1034;
     console.log(cheat_nodes_model.cid);
     
     var getmessagelog = new app.cheat_node_item_model({text: "Hello world", isIn: cheat_nodes_model});
     var getmessagelog = new app.cheat_node_item_model({text: "Hello world 34", isIn: cheat_nodes_model});
     var getmessagelog = new app.cheat_node_item_model({text: "Hello world 345", isIn: cheat_nodes_model});
     
     
     var cheat_nodes_collection = new app.cheat_nodes_collection();
     cheat_nodes_collection.add(cheat_nodes_model);
     
     
     cheat_nodes_collection.each(function (model) {
     console.log(model.toJSON());
     console.log(model.get('nodeitems').toJSON());
     
     });
     */

});