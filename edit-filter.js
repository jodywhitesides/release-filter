import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { SelectControl, PanelBody } from '@wordpress/components';

const Edit = ({ attributes, setAttributes }) => {
    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <PanelBody title="Filter Settings">
                    <SelectControl
                        label="Filter Releases"
                        value={attributes.filter}
                        options={[
                            { label: 'All', value: 'all' },
                            { label: 'Album', value: 'album' },
                            { label: 'EP', value: 'ep' },
                            { label: 'Single', value: 'single' },
                        ]}
                        onChange={(value) => setAttributes({ filter: value })}
                    />
                </PanelBody>
            </InspectorControls>
            <p>Selected filter: {attributes.filter}</p>
        </div>
    );
};

export default Edit;