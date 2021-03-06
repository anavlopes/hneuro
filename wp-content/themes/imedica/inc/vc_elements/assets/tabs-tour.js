(_, Backbone, vc), function ($) {
   var Shortcodes = vc.shortcodes;
   window.ImedicaTabsView = vc.shortcode_view.extend({
    new_tab_adding: !1,
    events: {
        "click .add_tab": "addTab",
        "click > .vc_controls .vc_control-btn-delete": "deleteShortcode",
        "click > .vc_controls .vc_control-btn-edit": "editElement",
        "click > .vc_controls .vc_control-btn-clone": "clone"
    },
    initialize: function (params) {
        window.VcTabsView.__super__.initialize.call(this, params), _.bindAll(this, "stopSorting")
    },
    render: function () {
        return window.VcTabsView.__super__.render.call(this), this.$tabs = this.$el.find(".wpb_tabs_holder"), this.createAddTabButton(), this
    },
    ready: function (e) {
        window.VcTabsView.__super__.ready.call(this, e)
    },
    createAddTabButton: function () {
        var new_tab_button_id = Date.now() + "-" + Math.floor(11 * Math.random());
        this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>'), this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls")), vc_user_access().shortcodeAll("vc_tab") || this.$add_button.hide()
    },
    addTab: function (e) {
        if (e.preventDefault(), !vc_user_access().shortcodeAll("vc_tab"))return !1;
        this.new_tab_adding = !0;
        var tab_title = window.i18nLocale.tab, tabs_count = this.$tabs.find("[data-element_type=vc_tab]").length, tab_id = Date.now() + "-" + tabs_count + "-" + Math.floor(11 * Math.random());
        return vc.shortcodes.create({
            shortcode: "vc_tab",
            params: {title: tab_title, tab_id: tab_id},
            parent_id: this.model.id
        }), !1
    },
    stopSorting: function (event, ui) {
        var shortcode;
        this.$tabs.find("ul.tabs_controls li:not(.add_tab_block)").each(function (index) {
            $(this).find("a").attr("href").replace("#", "");
            shortcode = vc.shortcodes.get($("[id=" + $(this).attr("aria-controls") + "]").data("model-id")), vc.storage.lock(), shortcode.save({order: $(this).index()})
        }), shortcode && shortcode.save()
    },
    changedContent: function (view) {
        var params = view.model.get("params");
        if (this.$tabs.hasClass("ui-tabs") || (this.$tabs.tabs({
            select: function (event, ui) {
                return !$(ui.tab).hasClass("add_tab")
            }
        }), this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs), vc_user_access().shortcodeAll("vc_tab") && this.$tabs.find(".ui-tabs-nav").sortable({
            axis: "vc_tour" === this.$tabs.closest("[data-element_type]").data("element_type") ? "y" : "x",
            update: this.stopSorting,
            items: "> li:not(.add_tab_block)"
        })), !0 === view.model.get("cloned")) {
            var $tab_controls = (view.model.get("cloned_from"), $(".tabs_controls > .add_tab_block", this.$content)), $new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore($tab_controls);
            this.$tabs.tabs("refresh"), this.$tabs.tabs("option", "active", $new_tab.index())
        } else $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore(this.$add_button), this.$tabs.tabs("refresh"), this.$tabs.tabs("option", "active", this.new_tab_adding ? $(".ui-tabs-nav li", this.$content).length - 2 : 0);
        this.new_tab_adding = !1
    },
    cloneModel: function (model, parent_id, save_order) {
        var new_order, model_clone, params, tag;
        return new_order = _.isBoolean(save_order) && !0 === save_order ? model.get("order") : parseFloat(model.get("order")) + vc.clone_index, params = _.extend({}, model.get("params")), tag = model.get("shortcode"), "vc_tab" === tag && _.extend(params, {tab_id: Date.now() + "-" + this.$tabs.find("[data-element-type=vc_tab]").length + "-" + Math.floor(11 * Math.random())}), model_clone = Shortcodes.create({
            shortcode: tag,
            id: vc_guid(),
            parent_id: parent_id,
            order: new_order,
            cloned: "vc_tab" !== tag,
            cloned_from: model.toJSON(),
            params: params
        }), _.each(Shortcodes.where({parent_id: model.id}), function (shortcode) {
            this.cloneModel(shortcode, model_clone.get("id"), !0)
        }, this), model_clone
    }
}), window.ImedicaTabView = window.VcColumnView.extend({
    events: {
        "click > .vc_controls .vc_control-btn-delete": "deleteShortcode",
        "click > .vc_controls .vc_control-btn-prepend": "addElement",
        "click > .vc_controls .vc_control-btn-edit": "editElement",
        "click > .vc_controls .vc_control-btn-clone": "clone",
        "click > .wpb_element_wrapper > .vc_empty-container": "addToEmpty"
    }, render: function () {
        var params = this.model.get("params");
        return window.VcTabView.__super__.render.call(this), params.tab_id || (params.tab_id = Date.now() + "-" + Math.floor(11 * Math.random()), this.model.save("params", params)), this.id = "tab-" + params.tab_id, this.$el.attr("id", this.id), this
    }, ready: function (e) {
        window.VcTabView.__super__.ready.call(this, e), this.$tabs = this.$el.closest(".wpb_tabs_holder");
        this.model.get("params");
        return this
    }, changeShortcodeParams: function (model) {
        var params;
        window.VcTabView.__super__.changeShortcodeParams.call(this, model), params = model.get("params"), _.isObject(params) && _.isString(params.title) && _.isString(params.tab_id) && $(".ui-tabs-nav [href='\\#tab-" + params.tab_id + "']").text(params.title)
    }, deleteShortcode: function (e) {
        _.isObject(e) && e.preventDefault();
        var answer = confirm(window.i18nLocale.press_ok_to_delete_section), parent_id = this.model.get("parent_id");
        if (!0 !== answer)return !1;
        if (this.model.destroy(), !vc.shortcodes.where({parent_id: parent_id}).length) {
            var parent = vc.shortcodes.get(parent_id);
            return parent.destroy(), !1
        }
        var params = this.model.get("params"), current_tab_index = $("[href=#tab-" + params.tab_id + "]", this.$tabs).parent().index();
        $("[href=#tab-" + params.tab_id + "]").parent().remove();
        var tab_length = this.$tabs.find(".ui-tabs-nav li:not(.add_tab_block)").length;
        tab_length > 0 && this.$tabs.tabs("refresh"), tab_length > current_tab_index ? this.$tabs.tabs("option", "active", current_tab_index) : tab_length > 0 && this.$tabs.tabs("option", "active", tab_length - 1)
    }, cloneModel: function (model, parent_id, save_order) {
        var new_order, model_clone, params, tag;
        return new_order = _.isBoolean(save_order) && !0 === save_order ? model.get("order") : parseFloat(model.get("order")) + vc.clone_index, params = _.extend({}, model.get("params")), tag = model.get("shortcode"), "vc_tab" === tag && _.extend(params, {tab_id: Date.now() + "-" + this.$tabs.find("[data-element_type=vc_tab]").length + "-" + Math.floor(11 * Math.random())}), model_clone = Shortcodes.create({
            shortcode: tag,
            parent_id: parent_id,
            order: new_order,
            cloned: !0,
            cloned_from: model.toJSON(),
            params: params
        }), _.each(Shortcodes.where({parent_id: model.id}), function (shortcode) {
            this.cloneModel(shortcode, model_clone.get("id"), !0)
        }, this), model_clone
    }
})

}( jQuery );