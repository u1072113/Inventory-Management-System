/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var app = app || {};

(function ($) {
    'use strict';

    app.cheatsheets_collection = Backbone.Collection.extend({
        model: app.cheatsheet,
        url: function () {
            // Important! It's got to know where to send its REST calls. 
            // In this case, POST to '/donuts' and PUT to '/donuts/:id'
            return this.id ? '/donuts/' + this.id : '/donuts';
        }
    });

})(jQuery);


