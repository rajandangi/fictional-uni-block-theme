import { TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker } from "@wordpress/components";

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, BlockControls, AlignmentToolbar, useBlockProps } from "@wordpress/block-editor";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {Element} Element to render.
 */
export default function Edit(props) {
    const blockProps = useBlockProps({
        className: "paying-attention-edit-block",
        style: { backgroundColor: props.attributes.bgColor },
    }) // useBlockProps() gives us the default block props that Gutenberg provides

    function updateQuestion(value) {
        props.setAttributes({ question: value })
    }

    function deleteAnswer(indexToDelete) {
        const newAnswers = props.attributes.answers.filter(function (answer, index) {
            return index !== indexToDelete // keep all answers except the one we want to delete
        })
        props.setAttributes({ answers: newAnswers }) // update the answers attribute with the new array

        if (indexToDelete == props.attributes.correctAnswer) {
            props.setAttributes({ correctAnswer: undefined })
        }
    }

    function markAsCorrect(index) {
        props.setAttributes({ correctAnswer: index })
    }

    return (
        <div {...blockProps}>
            <BlockControls>
                <AlignmentToolbar value={props.attributes.theAlignment} onChange={x => props.setAttributes({ theAlignment: x })} />
            </BlockControls>

            <InspectorControls>
                <PanelBody title="Background Color" initialOpen={open}>
                    <PanelRow>
                        <ColorPicker color={props.attributes.bgColor} onChangeComplete={x => props.setAttributes({ bgColor: x.hex })} />
                    </PanelRow>
                </PanelBody>
            </InspectorControls >

            <TextControl label="Question" value={props.attributes.question} onChange={updateQuestion} style={{ fontSize: "20px" }} />
            <p style={{ fontSize: "13px", margin: "20 0 8px 0" }}>Answers:</p>
            {props.attributes.answers.map(function (answer, index) {
                return (
                    <Flex>
                        <FlexBlock>
                            <TextControl value={answer} autoFocus={answer == undefined} onChange={newValue => {
                                // copy the array of answers since we can't mutate the original one
                                const newAnswers = props.attributes.answers.concat([])
                                // update the value of the answer at the current index
                                newAnswers[index] = newValue
                                // update the answers attribute with the new array
                                props.setAttributes({ answers: newAnswers })
                            }} />
                        </FlexBlock>
                        <FlexItem>
                            <Icon className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"} onClick={() => markAsCorrect(index)} />
                        </FlexItem>
                        <FlexItem>
                            <Button variant="link" className="attention-delete" onClick={() => deleteAnswer(index)}>Delete</Button>
                        </FlexItem>
                    </Flex>
                )
            })}
            <Button variant="primary" onClick={() => {
                // add a new empty answer to the end of the array
                props.setAttributes({ answers: props.attributes.answers.concat([undefined]) })
            }}>Add Another Answer</Button>
        </div >
    )
}
