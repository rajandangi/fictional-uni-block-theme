wp.blocks.registerBlockType("ourblocktheme/page", {
    title: "Fictional University Page",
    icon: "smiley",
    edit: function () {
        return wp.element.createElement("div", { className: "our-placeholder-block" }, "Page Placeholder")
    },
    save: function () {
        return null;
    }
})