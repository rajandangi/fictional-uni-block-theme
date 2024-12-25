import { ToolbarGroup, ToolbarButton } from "@wordpress/components"
import { useBlockProps, RichText, BlockControls } from "@wordpress/block-editor"
import { registerBlockType } from "@wordpress/blocks"
import { __ } from "@wordpress/i18n"

registerBlockType("ourblocktheme/genericheading", {
    title: "Generic Heading",
    attributes: {
        text: { type: "string" },
        size: { type: "string", default: "large" }
    },
    edit: EditComponent,
    save: SaveComponent
})

function EditComponent(props) {
    const blockProps = useBlockProps();

    function handleTextChange(newText) {
        props.setAttributes({ text: newText })
    }

    return (
        <div {...blockProps}>
            <BlockControls>
                <ToolbarGroup>
                    <ToolbarButton isPressed={props.attributes.size === "large"} onClick={() => props.setAttributes({ size: "large" })}>
                        Large
                    </ToolbarButton>
                    <ToolbarButton isPressed={props.attributes.size === "medium"} onClick={() => props.setAttributes({ size: "medium" })}>
                        Medium
                    </ToolbarButton>
                    <ToolbarButton isPressed={props.attributes.size === "small"} onClick={() => props.setAttributes({ size: "small" })}>
                        Small
                    </ToolbarButton>
                </ToolbarGroup>
            </BlockControls>
            <RichText
                {...blockProps}
                allowedFormats={["core/bold", "core/italic"]}
                tagName="h1"
                className={`headline headline--${props.attributes.size}`}
                value={props.attributes.text}
                onChange={handleTextChange}
                placeholder={__('Generic Heading...')}
            />
        </div>
    )
}

function SaveComponent(props) {
    const blockProps = useBlockProps.save();

    function createTagName() {
        switch (props.attributes.size) {
            case "large":
                return "h1";
            case "medium":
                return "h2";
            case "small":
                return "h3";
            default:
                return "h1";
        }
    }

    return <RichText.Content
        {...blockProps}
        tagName={createTagName()}
        className={`headline headline--${props.attributes.size}`}
        value={props.attributes.text}
    />; // Saves <h2>Content added in the editor...</h2> to the database for frontend display
}
