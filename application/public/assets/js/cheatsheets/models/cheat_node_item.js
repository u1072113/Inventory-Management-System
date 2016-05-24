/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var app = app || {};

(function ($) {
    'use strict';

    app.cheat_node_item_model = Backbone.RelationalModel.extend({
        urlRoot:'sheetnodeitems',
        defaults: {
            sheetnodeitem_text: "",
            sheetnodeitem_description:"",
            sheetnodeitem_itemtype:"",
            sheetnodeitem_itemfrom:"",
            sheetnode_id:"",
            _token:app.token
            
        }
    });
})(jQuery);