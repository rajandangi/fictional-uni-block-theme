import apiFetch from "@wordpress/api-fetch"
import { Button, PanelBody, PanelRow } from "@wordpress/components"
import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor"
import { registerBlockType } from "@wordpress/blocks"
import libraryHero from '/images/library-hero.jpg';
import { useEffect } from "@wordpress/element"

registerBlockType("ourblocktheme/banner", {
    title: "Banner",
    icon: "welcome-learn-more",
    supports: {
        align: ["full"]
    },
    attributes: {
        align: { type: "string", default: "full" },
        imgID: { type: "number" },
        imgURL: { type: "string", default: libraryHero },
    },
    edit: EditComponent,
    save: SaveComponent
})

function EditComponent(props) {
    useEffect(function () {
        if (props.attributes.imgID) {
            async function go() {
                // endpoint to retrieve a specific Media Item record using the ID
                const response = await apiFetch({
                    path: `/wp/v2/media/${props.attributes.imgID}`,
                    method: "GET"
                })
                props.setAttributes({ imgURL: response.media_details.sizes.pageBanner.source_url })
            }
            go()
        }
    }, [props.attributes.imgID])

    function onFileSelect(image) {
        props.setAttributes({ imgID: image.id })
    }

    return (
        <>
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
            <div className="page-banner">
                <div className="page-banner__bg-image"
                    style={{ backgroundImage: `url('${props.attributes.imgURL}')` }}>
                </div>
                <div className="page-banner__content container t-center c-white">
                    <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} />
                </div>
            </div >
        </>
    )
}

function SaveComponent() {
    return <InnerBlocks.Content />
}
