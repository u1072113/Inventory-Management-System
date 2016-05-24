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

    app.cheatsnodeView = Backbone.View.extend({
        // Instead of generating a new element, bind to the existing skeleton of
        // the App already present in the HTML.
        el: '#cheat_sheet_canvas',
        // Our template for the line of statistics at the bottom of the app.
        nodeItemTemplate: _.template($('#node_item_template').html()),
        // Delegated events for creating new items, and clearing completed ones.
        events: {
            "click .sheetnode": "text",
            "keypress .additem": "kepressfilter",
            "click .save_node": "addNode",
            "click .cancel_node": "cancelNode",
            "focusout .node_label": "changeTitle"
        },
        // At initialization we bind to the relevant events on the `Todos`
        // collection, when items are added or changed. Kick things off by
        // loading any preexisting todos that might be saved in *localStorage*.
        initialize: function () {
            this.collection = new app.cheat_nodes_collection();
            this.collection.bind('add', this.render, this);
            this.collection.fetch();

            $(".add-node").popover({
                html: true,
                content: function () {
                    var template = _.template($('#new_node_item').html());
                    return template({});
                },
                title: function () {
                    //  var title = $(this).attr("data-popover-content");
                    // return $(title).children(".popover-heading").html();
                    return 'Create new Node';
                }
            });
            this.container = jQuery('#cheat_sheet_area');



            // Creates an instance of Masonry on #posts

            this.container.masonry({
                itemSelector: '.col-md-4',
                columnWidth: '.col-md-4'
            });

            //var template = _.template($("#node_item_template").html(), {});
            // this.addNode();

        },
        render: function () {
            console.log("Created Cheatnode");
            this.collection.each(function (model) {
                console.log(model.toJSON());
                console.log("CID" + model.cid);
                console.log(model.get('nodeitems').toJSON());

            });
        },
        text: function (e) {
            //console.log($(e.currentTarget).data("cid"));
        },
        addNode: function () {
            var self = this;
            var data = $(".node_form").serializeObject();
            var cheatsheet = app.cheatsheet;
            var cheat_nodes_model = new app.cheat_nodes_model({_token: app.token, cheatsheet_id: cheatsheet.get("id")});
            cheat_nodes_model.urlRoot = 'sheetnodes';
            cheat_nodes_model.set(data);
            $(".add-node").popover('hide');
            cheat_nodes_model.save(null, {
                success: function (model, response) {
                    var template = _.template($('#node_item_template').html());
                    var templateData = _.extend(model.toJSON());
                    var html = template(templateData);
                    //  $("#cheat_sheet_area").append(html).masonry('layout');
                    //self.container.masonry('appended', $(html) );
                    html = $(html);
                    $('#cheat_sheet_area').masonry()
                            .append(html)
                            .masonry('appended', html);
                     self.collection.add(model);

                },
                error: function (model, response) {
                    console.log("error");
                }
            });
           


            /*
             var id = Math.floor((Math.random() * 100) + 1);
             // Compile the template using underscore
             // var template = _.template($("#node_item_template").html(), {});
             // Load the compiled HTML into the Backbone "el"
             // this.$el.html(template);
             var cheat_nodes_model = new app.cheat_nodes_model({name: "Hehehe", parent: app.cheatsheet, id: id});
             var template = _.template($('#node_item_template').html());
             var templateData = _.extend(cheat_nodes_model.toJSON(), {cid: cheat_nodes_model.cid});
             var html = template(templateData);
             this.$el.append(html);
             console.log(cheat_nodes_model.toJSON());
             console.log(cheat_nodes_model.cid);
             this.collection.add(cheat_nodes_model);
             this.render();
             /*
             console.log(cheat_nodes_model.cid);
             
             var getmessagelog = new app.cheat_node_item_model({text: "Hello world", isIn: cheat_nodes_model});
             var getmessagelog = new app.cheat_node_item_model({text: "Hello world 34", isIn: cheat_nodes_model});
             var getmessagelog = new app.cheat_node_item_model({text: "Hello world 345", isIn: cheat_nodes_model});
             
             //Add the new item to the node.
             this.collection.add(cheat_nodes_model);
             */
        },
        saveNode: function () {
            console.log();
        },
        cancelNode: function () {
            $(".add-node").popover('hide');
        },
        kepressfilter: function (e) {
            if (e.keyCode != 13) {
                return;
            }
            this.addNodeItem(e);
        },
        addNodeItem: function (e) {
            var self = this;
            console.log($(e.currentTarget).data("id"));
            var id = $(e.currentTarget).data("id");
            console.log(this.collection.toJSON());
            var item = this.collection.get(id);
            console.log(item.toJSON());
            var node_item = new app.cheat_node_item_model({sheetnodeitem_text: $(e.currentTarget).val(), sheetnode_id: item, _token: app.token});
            node_item.save(null, {
                success: function (model, response) {
                    var tag = ".node_" + id;
                    $(tag).find(".addable").append("<li class=''>" + $(e.currentTarget).val() + "</li>");
                    $(e.currentTarget).val("");
                    $(".sortable").sortable({
                    }).disableSelection();
                    self.container.masonry();

                },
                error: function (model, response) {
                    console.log("error");
                }
            });
        },
        changeTitle: function (e) {
            self = this;
            var id = $(e.currentTarget).data("id");
            var title = $(e.currentTarget).text();
            console.log(this.collection.toJSON());
            var item = this.collection.get(id);
            console.log(item.toJSON());
            item.set({sheetnode_title: title, _token: app.token});
            console.log(item.toJSON());
            item.urlRoot = 'sheetnodes';
            item.save(null, {
                success: function (model, response) {
                    $(".sortable").sortable({
                    }).disableSelection();
                    self.container.masonry();

                },
                error: function (model, response) {
                    console.log("error");
                }
            });
        }
    });
})(jQuery);