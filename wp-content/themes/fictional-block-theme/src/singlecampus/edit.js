import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
    const blockProps = useBlockProps();
    return (
        <div {...blockProps}>
            <div className="our-placeholder-block">Single Campus Block Placeholder</div>
        </div>
    );
}