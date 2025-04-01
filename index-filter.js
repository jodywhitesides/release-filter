import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit-filter.js';
import metadata from './block.json';

registerBlockType(metadata.name, {
    edit: Edit,
    save: () => null // Uses server-side rendering
});