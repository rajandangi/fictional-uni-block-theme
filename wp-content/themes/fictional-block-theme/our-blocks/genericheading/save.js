import { RichText } from "@wordpress/block-editor"

export default function Save(props) {

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

    // Saves <h2>Content added in the editor...</h2> to the database for frontend display
    return <RichText.Content
        tagName={createTagName()}
        className={`headline headline--${props.attributes.size}`}
        value={props.attributes.text}
    />;
}