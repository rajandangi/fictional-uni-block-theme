import { InnerBlocks } from "@wordpress/block-editor"

wp.blocks.registerBlockType("ourblocktheme/banner", {
    title: "Banner",
    icon: "welcome-learn-more",
    edit: EditComponent,
    save: SaveComponent
})

function EditComponent() {
    return (
        <div className="page-banner">
            <div className="page-banner__bg-image"
                style={{ backgroundImage: "url('/wp-content/themes/fictional-block-theme/images/library-hero.jpg')" }}>
            </div>
            <div className="page-banner__content container t-center c-white">
                <InnerBlocks />
            </div>
        </div >
    )
}

function SaveComponent() {
    return <p>This is from our banner bloc</p>
}
console.log("Banner block loaded")