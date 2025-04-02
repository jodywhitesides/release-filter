import { registerBlockType } from '@wordpress/blocks';
import { useState } from 'react';
import { useSelect, useDispatch } from '@wordpress/data';
import metadata from './block.json';

const Edit = ({ attributes, setAttributes, clientId }) => {
    const [selectedFilter, setSelectedFilter] = useState(attributes.selectedFilter || '');

    const handleFilterChange = (event) => {
        const newFilter = event.target.value;
        setSelectedFilter(newFilter);
        setAttributes({ selectedFilter: newFilter });
        updateBlock(newFilter);
    };

    const updateBlock = (newFilter) => {
        const { updateBlockAttributes } = useDispatch('core/editor');
        updateBlockAttributes(clientId, { selectedFilter: newFilter });
    };

    return (
        <div>
            <select onChange={handleFilterChange} value={selectedFilter}>
                <option value="">Select Filter</option>
                <option value="album">Album</option>
                <option value="ep">EP</option>
                <option value="single">Single</option>
            </select>
        </div>
    );
};

registerBlockType(metadata.name, {
    edit: Edit,
    save: () => null // Uses server-side rendering
});