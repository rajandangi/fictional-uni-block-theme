import apiFetch from "@wordpress/api-fetch"
import { Button, PanelBody, PanelRow } from "@wordpress/components"
import { useBlockProps, InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor"
import { useEffect, useState } from "@wordpress/element"

export default function Edit(props) {
    const blockProps = useBlockProps();
    const [imgURL, setImgURL] = useState(props.attributes.imgURL);

    useEffect(function () {
        if (props.attributes.themeimage) {
            const newImgURL = `${ourThemeData.theme_image_path}${props.attributes.themeimage}`;
            props.setAttributes({ imgURL: newImgURL });
            setImgURL(newImgURL);
        }
        if (!props.attributes.themeimage && !props.attributes.imgURL) {
            const defaultImgURL = ourThemeData.root_url + '/images/library-hero.jpg';
            props.setAttributes({ imgURL: defaultImgURL });
            setImgURL(defaultImgURL);
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
                const newImgURL = response.media_details.sizes.pageBanner.source_url;
                props.setAttributes({ themeimage: "", imgURL: newImgURL });
                setImgURL(newImgURL);
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

            <div {...blockProps}>
                <div class="hero-slider__slide" style={{ backgroundImage: `url('${imgURL}')` }}>
                    <div class="hero-slider__interior container">
                        <div class="hero-slider__overlay t-center">
                            <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} />
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}