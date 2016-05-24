/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var app = app || {};

(function ($) {
    'use strict';

    //Create cheatsheet relation to Nodes    
    app.cheatsheet = Backbone.RelationalModel.extend({
       
        relations: [{
                type: Backbone.HasMany,
                key: 'nodes',
                relatedModel: 'app.cheat_nodes_model',
                collectionType: 'app.cheat_nodes_collection',
                reverseRelation: {
                    key: 'parent',
                    includeInJSON: 'id'
                            // 'relatedModel' is automatically set to 'Zoo'; the 'relationType' to 'HasOne'.
                }
            }]
    });
})(jQuery);