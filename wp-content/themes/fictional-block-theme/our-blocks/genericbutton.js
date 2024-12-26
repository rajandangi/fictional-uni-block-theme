import { link } from "@wordpress/icons"
import { ToolbarGroup, ToolbarButton, Popover, Button, PanelBody, PanelRow, ColorPalette } from "@wordpress/components"
import { useBlockProps, RichText, InspectorControls, BlockControls, __experimentalLinkControl as LinkControl, getColorObjectByColorValue } from "@wordpress/block-editor"
import { registerBlockType } from "@wordpress/blocks"
import { useState } from "@wordpress/element"
import { __ } from "@wordpress/i18n"
import ourColors from "../inc/ourColors"

registerBlockType("ourblocktheme/genericbutton", {
    title: "Generic Button",
    attributes: {
        text: { type: "string" },
        size: { type: "string", default: "large" },
        linkObject: { type: "object", default: { url: "" } },
        colorName: { type: "string", default: "blue" }
    },
    edit: EditComponent,
    save: SaveComponent
})

function EditComponent(props) {
    const blockProps = useBlockProps();
    const [isLinkPickerVisible, setLinkPickerVisible] = useState(false);

    function handleTextChange(newText) {
        props.setAttributes({ text: newText })
    }

    function buttonHandler() {
        setLinkPickerVisible(prev => !prev);
    }

    function handleLinkChange(newLink) {
        props.setAttributes({ linkObject: newLink })
    }

    const currentColorValue = ourColors.filter(color => {
        return color.name === props.attributes.colorName
    })[0].color;

    function handleColorChange(newColorCode) {
        // from the hex value of the color, find the name of the color
        const { name } = getColorObjectByColorValue(ourColors, newColorCode)
        props.setAttributes({ colorName: name })
    }

    return (
        <>
            <BlockControls>
                <ToolbarGroup>
                    <ToolbarButton onClick={buttonHandler} icon={link} />
                </ToolbarGroup>
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
            <InspectorControls >
                <PanelBody title={__('Button Color')} initialOpen={true}>
                    <PanelRow>
                        <ColorPalette
                            disableCustomColors={true}
                            clearable={false}
                            colors={ourColors}
                            value={currentColorValue}
                            onChange={handleColorChange}
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls >
            
            <div {...blockProps}>
                <RichText
                    allowedFormats={[]}
                    tagName="a"
                    className={`btn headline btn--${props.attributes.size} btn--${props.attributes.colorName}`}
                    value={props.attributes.text}
                    onChange={handleTextChange}
                    placeholder={__('Find Your Major')}
                />
                {isLinkPickerVisible && (
                    <Popover position="middle center" onFocusOutside={() => setIsLinkPickerVisible(false)}>
                        <LinkControl value={props.attributes.linkObject} title={props.attributes.linkObject.title} onChange={handleLinkChange} />
                        <Button variant="primary" onClick={() => setLinkPickerVisible(false)} style={{ display: "block", width: "100%" }}>{__('Confirm Link')}</Button>
                    </Popover>
                )}
            </div>
        </>
    )
}

function SaveComponent(props) {
    return (
        <a href={props.attributes.linkObject.url} className={`btn headline btn--${props.attributes.size} btn--${props.attributes.colorName}`}>
            {props.attributes.text}
        </a>
    )
}
