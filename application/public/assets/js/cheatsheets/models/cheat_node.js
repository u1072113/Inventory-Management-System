/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var app = app || {};

(function ($) {
    'use strict';

    //Create Cheatsheet node
    app.cheat_nodes_model = Backbone.RelationalModel.extend({
        relations: [{
                type: Backbone.HasMany,
                key: 'nodeitems',
                relatedModel: 'app.cheat_node_item_model',
                collectionType: 'app.cheat_nodes_collection',
                reverseRelation: {
                    key: 'sheetnode_id',
                    includeInJSON: 'id'
                            // 'relatedModel' is automatically set to 'Zoo'; the 'relationType' to 'HasOne'.
                }
            }]
    });
    
})(jQuery);