/*
 * Use to Block Editor
 * 
 * @since 1.1.3
 * 
 * Add Style in edit Block Gallery
 * 
 */

if( wp && wp.blocks ) {

    wp.blocks.registerBlockStyle( 'core/gallery', {
        name: 'photobox',
        label: 'Photo Box'
    } );
    
}