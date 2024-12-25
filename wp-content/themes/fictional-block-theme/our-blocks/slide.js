import apiFetch from "@wordpress/api-fetch"
import { Button, PanelBody, PanelRow } from "@wordpress/components"
import { useBlockProps, InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor"
import { registerBlockType } from "@wordpress/blocks"
import { useEffect } from "@wordpress/element"

registerBlockType("ourblocktheme/slide", {
    title: "Slide",
    icon: "welcome-learn-more",
    supports: {
        align: ["full"]
    },
    attributes: {
        themeimage: { type: "string" },
        align: { type: "string", default: "full" },
        imgID: { type: "number" },
        imgURL: { type: "string" },
    },
    edit: EditComponent,
    save: SaveComponent
})

function EditComponent(props) {
    const blockProps = useBlockProps();

    useEffect(function () {
        if (props.attributes.themeimage) {
            props.setAttributes({ imgURL: `${slide.themeimagepath}${props.attributes.themeimage}` })
        }
    }, [])

    useEffect(function () {
        if (props.attributes.imgID) {
            async function go() {
                // endpoint to retrieve a specific Media Item record using the ID
                const response = await apiFetch({
                    path: `/wp/v2/media/${props.attributes.imgID}`,
                    method: "GET"
                })
                props.setAttributes({ themeimage: "", imgURL: response.media_details.sizes.pageBanner.source_url })
            }
            go()
        }
    }, [props.attributes.imgID])

    function onFileSelect(image) {
        props.setAttributes({ imgID: image.id })
    }

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title="Background" initialOpen={true}>
                    <PanelRow>
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={onFileSelect}
                                value={props.attributes.imgID}
                                render={({ open }) => {
                                    return <Button onClick={open}>Choose Image</Button>
                                }}
                            />
                        </MediaUploadCheck>
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <div class="hero-slider__slide" style={{ backgroundImage: `url('${props.attributes.imgURL}')` }}>
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay t-center">
                        <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} />
                    </div>
                </div>
            </div>
        </div>
    )
}

function SaveComponent() {
    return <InnerBlocks.Content />
}
