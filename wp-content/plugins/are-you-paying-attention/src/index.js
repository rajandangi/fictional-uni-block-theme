import "./index.scss"
import { TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker } from "@wordpress/components"
import { InspectorControls, BlockControls, AlignmentToolbar, useBlockProps } from "@wordpress/block-editor" //InspectorControls Register Side bar controls options for the block

(function () {
    let locked = false
    wp.data.subscribe(function () {
        /**
         * Get all of the ourblocks in the editor
         * - Filter out any that don't have a correct answer set
         * 
         * 
         * Note: you can also try wp.data.select("core/block-editor").getBlocks() in the block editor console,
         * and then expand the results to see what attributes are available for each block
         */
        const results = wp.data.select("core/block-editor").getBlocks().filter(function (block) {
            return block.name == "ourplugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined
        })

        if (results.length && locked == false) {
            locked = true
            /**
             * If there is at least one correct answer set for each of the ourblocks,
             *  - Disable Post Saving (lock the save button)
             */
            wp.data.dispatch("core/editor").lockPostSaving("noanswer")
        }

        if (!results.length && locked) {
            locked = false
            /**
             * If there is at least one correct answer set for each of the ourblocks,
             *   - Enable Post Saving (unlock the save button)
             */
            wp.data.dispatch("core/editor").unlockPostSaving("noanswer")
        }

    })

})() // it's an IIFE (Immediately Invoked Function Expression) that runs as soon as the file is loaded

wp.blocks.registerBlockType("ourplugin/are-you-paying-attention", {
    attributes: {
        question: { type: "string" },
        answers: { type: "array", default: [""] },
        correctAnswer: { type: "number", default: undefined },
        bgColor: { type: "string", default: "#EBEBEB" },
        theAlignment: { type: "string", default: "left" }
    },
    example: {
        attributes: {
            question: "What is the capital of France?",
            answers: ["Paris", "London", "Berlin"],
            correctAnswer: 0,
            bgColor: "#CFE8F3",
            theAlignment: "left"
        }
    },
    edit: EditComponent,
    save: function (props) {
        return null
    }
})


function EditComponent(props) {
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