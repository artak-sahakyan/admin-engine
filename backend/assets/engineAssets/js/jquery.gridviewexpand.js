(function( $ ){
    var self = null,
        options = {};

    var methods = {
        init: function( settings ) {

            options = $.extend({
                'open': {
                    cssClass: 'far fa-plus-square',
                },
                'close': {
                    cssClass: 'far fa-minus-square',
                },
                'id': function(t){
                    return $(t).parent().attr('data-key');
                },
                'dataMap': null, // null or object
            }, settings);

            // options.dataMap = dataMap;

            return this.each(function(){

                self = $(this);
                data = self.data('gridviewdetail');

                if ( ! data ) {
                    self.data('gridviewdetail', {
                        target: self,
                        state: {},
                        cache: {},
                    });


                    self.find('.expand').each(function(){
                        var expand = $(this);

                        return methods.expandClick.call(this);

                    });
                }

            });

        },
        expandClick: function () {

            var expand = $(this);
            var id = options.id(expand);

            // skip non expandable block
            if (typeof(options.dataMap) != 'null') {
                var childrenMap = methods.makeChildrenMap(options.dataMap);
                if (!(id in childrenMap)) {
                    self.removeClass('expand');
                    return true;
                }
            }


            expand.click(function(){

                if (expand.hasClass('open')) {
                    methods.hide.call(expand, id);
                } else {
                    methods.show.call(expand, id);
                }
            });

            // css init
            expand.addClass(options.open.cssClass);

        },
        hide: function(id) {

            this.parent().parent().find('tr[data-parent-id=' + id +  ']').remove();
            this.removeClass('open');
            this.removeClass(options.close.cssClass);
            this.addClass(options.open.cssClass);

            methods.saveCloseBlock.call(this, id);

        },
        show: function(id) {

            var cache = self.data('gridviewdetail').cache;

            if (typeof(cache[id]) == 'undefined') {
                methods.getData.call(this, id);
                return;
            }

            if (typeof(cache[id]) != 'undefined' && cache[id].length) {
                methods.getDetail.call(this, id);
            }

            return;

        },
        getDetail: function(id) {

            this.parent().after(self.data('gridviewdetail').cache[id]);
            this.parent().parent().find('tr[data-parent-id=' + id +  '] .expand').each(function(){
                methods.expandClick.call(this);
            });
            this.addClass('open');
            this.removeClass(options.open.cssClass);
            this.addClass(options.close.cssClass);

            methods.saveOpenBlock.call(this, id);
            methods.openAllChildrens.call(this, id);

        },
        getData: function(id) {

            var cache = self.data('gridviewdetail').cache;
            var $this = this;
            $.get('/admin/article-category/', {parent_id: id}, function(data, textStatus, jqXHR) {
                var child = '<tr data-parent-id=' + id + '><td colspan="7">' + data + '</td></tr>';
                cache[id] = child;
                cache = cache;

                methods.getDetail.call($this, id);
            }).fail(function () {
                alert('Error load data');
            });

        },
        saveOpenBlock: function(id) {

            var state = self.data('gridviewdetail').state;
            var pid = 0;
            if (this.parents('[data-parent-id]').length) {
                pid = this.parents('[data-parent-id]').attr('data-parent-id');
            }
            if (typeof(state[pid]) == 'undefined') {
                state[pid] = [];
            }
            if (state[pid].indexOf(id) === -1) {
                state[pid].push(id);
            }

        },
        saveCloseBlock: function(id) {

            var state = self.data('gridviewdetail').state;
            var pid = 0;
            if (this.parents('[data-parent-id]').length) {
                pid = this.parents('[data-parent-id]').attr('data-parent-id');
            }
            var pos = state[pid].indexOf(id);
            if (pos > -1) {
                state[pid].splice(pos, 1);
            }

        },
        openAllChildrens: function(id) {

            var state = self.data('gridviewdetail').state;

            if (typeof(state[id]) != 'undefined') {
                var tree = function (value) {
                    for (var n = 0; n < value.length; n++) {
                        var child = value[n];

                        self.find('tr[data-key="' + child + '"] td').click();

                        if (child in state) {
                            tree(child);
                        }
                    }
                }
                tree(state[id]);
            }

        },
        makeChildrenMap: function(dataMap) {

            var children = {};
            for (var id in dataMap) {
                var parent = dataMap[id].parent_id;
                if (parent == null) {
                    parent == 0;
                }

                if (typeof(children[parent]) == 'undefined') {
                    children[parent] = [];
                }
                children[parent].push(id);
            }

            return children;

        },

    };

    $.fn.gridviewexpand = function( method ) {

        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.gridviewdetail' );
        }

    };

})( jQuery );