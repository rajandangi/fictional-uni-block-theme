import { InnerBlocks } from "@wordpress/block-editor"
import { registerBlockType } from "@wordpress/blocks"

registerBlockType("ourblocktheme/slideshow", {
    title: "Slide Show",
    icon: "welcome-learn-more",
    supports: {
        align: ["full"]
    },
    attributes: {
        align: { type: "string", default: "full" },
    },
    edit: EditComponent,
    save: SaveComponent
})

function EditComponent() {
    return (
        <div style={{ background: "#333", padding: "35px" }}>
            <p style={{ textAlign: "center", fontSize: "20px", color: "#fff" }}>Slideshow</p>
            <InnerBlocks allowedBlocks={["ourblocktheme/slide"]} />
        </div>
    )
}

function SaveComponent() {
    return <InnerBlocks.Content />
}