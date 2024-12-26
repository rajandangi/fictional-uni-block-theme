import { useBlockProps, InnerBlocks } from "@wordpress/block-editor"

export default function Edit(props) {
    const blockProps = useBlockProps();
    return (
        <div {...blockProps}>
            <div style={{ background: "#333", padding: "35px" }}>
                <p style={{ textAlign: "center", fontSize: "20px", color: "#fff" }}>Slideshow</p>
                <InnerBlocks allowedBlocks={["ourblocktheme/slide"]} />
            </div>
        </div>
    )
}