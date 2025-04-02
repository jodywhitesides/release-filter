import { useState, useEffect } from 'react';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { SelectControl, PanelBody } from '@wordpress/components';
import { useDispatch } from '@wordpress/data';

const Edit = ({ attributes, setAttributes, clientId }) => {
    const [selectedFilter, setSelectedFilter] = useState(attributes.filter || 'all');
    const { updateBlockAttributes } = useDispatch('core/editor');

    useEffect(() => {
        setAttributes({ filter: selectedFilter });
        updateBlockAttributes(clientId, { filter: selectedFilter });
    }, [selectedFilter]);

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <PanelBody title="Filter Settings">
                    <SelectControl
                        label="Filter Releases"
                        value={selectedFilter}
                        options={[
                            { label: 'All', value: 'all' },
                            { label: 'Album', value: 'album' },
                            { label: 'EP', value: 'ep' },
                            { label: 'Single', value: 'single' },
                        ]}
                        onChange={(value) => setSelectedFilter(value)}
                    />
                </PanelBody>
            </InspectorControls>
            <p>Selected filter: {selectedFilter}</p>
        </div>
    );
};

export default Edit;