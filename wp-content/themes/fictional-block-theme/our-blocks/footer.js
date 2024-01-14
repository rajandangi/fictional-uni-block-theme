wp.blocks.registerBlockType("ourblocktheme/footer", {
    title: "Fictional University Footer",
    icon: "smiley",
    edit: function () {
        return wp.element.createElement("div", { className: "our-placeholder-block" }, "Footer Placeholder")
    },
    save: function () {
        return null;
    }
})