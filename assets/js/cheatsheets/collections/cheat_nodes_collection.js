/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var app = app || {};

(function ($) {
    'use strict';
    
 app.cheat_nodes_collection = Backbone.Collection.extend({
        model: app.cheat_nodes_model,
        url:"sheetnodes"
    });
    
})(jQuery);


