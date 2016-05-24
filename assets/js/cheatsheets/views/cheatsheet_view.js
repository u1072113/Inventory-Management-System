/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*global Backbone, jQuery, _, ENTER_KEY */
var app = app || {};
$.fn.serializeObject = function ()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
(function ($) {
    'use strict';

    // The Application
    // ---------------

    // Our overall **AppView** is the top-level piece of UI.
    app.cheatsheetView = Backbone.View.extend({
        // Instead of generating a new element, bind to the existing skeleton of
        // the App already present in the HTML.
        el: 'body',
        // Our template for the line of statistics at the bottom of the app.
        nodeItemTemplate: _.template($('#node_item_template').html()),
        // Delegated events for creating new items, and clearing completed ones.
        events: {
            "click #new_cheatsheet": "newCheatsheet",
            "click .save_cheatsheet": "saveCheatSheet"
        },
        // At initialization we bind to the relevant events on the `Todos`
        // collection, when items are added or changed. Kick things off by
        // loading any preexisting todos that might be saved in *localStorage*.
        initialize: function () {
            this.model = new app.cheatsheet({cheatsheet_description: "", cheatsheet_name: "", cheatsheet_category: 7, cheatsheet_subcategory: 2, _token: app.token});
            this.listenTo(this.model, "change", this.render);
            this.model.urlRoot = 'cheatsheets';
            app.cheatsheet = this.model;
            //this.model.save();

        },
        render: function () {
            console.log("Created Cheatsheet");
            $(".cheatsheet_heading").html(this.model.get("cheatsheet_name"));
        },
        newCheatsheet: function () {
            var template = _.template($('#new_cheatsheet').html());
            console.log(this.model.toJSON());
            var html = template(this.model.toJSON());
            $(".modal-body").html(html);
            // As pointed out in comments, 
            // it is superfluous to have to manually call the modal.
            $('#myModal').modal('show')
        },
        saveCheatSheet: function () {
            var data = $(".cheatsheet_form").serializeObject();
            console.log(data);
            this.model.set(data);
            console.log(this.model.toJSON());
            this.model.save(null, {
                success: function (model, response) {
                    console.log("CheatSheet Saved");
                    $('#myModal').modal('hide')

                },
                error: function (model, response, sa) {
                    console.log(response.responseJSON);
                     
                    $.each(response.responseJSON, function (key, value) {
                        //key - each object in the json object
                        //value - its value
                        console.log(key +" : "+ value);
                        var elem = "."+key;
                       console.log($(elem).parent().html());
                       
                        
                    });
                    //console.log(model.toJSON());
                    //console.log(response.toJSON());
                    // console.log(sa.toJSON());
                }
            });
        },
    });
})(jQuery);