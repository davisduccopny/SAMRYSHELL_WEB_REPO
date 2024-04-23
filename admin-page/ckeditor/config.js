/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.extraPlugins = 'youtube,imageresizerowandcolumn,sourcedialog,pastefromgdocs,gallery,toc,widget';
    config.skin = 'office2013';
};


extraPlugins: [
    function ( editor ) {
        // Allow <iframe> elements in the model.               
        editor.model.schema.register( 'iframe', {
            allowWhere: '$text',
            allowContentOf: '$block'
        } );
        // Allow <iframe> elements in the model to have all attributes.
        editor.model.schema.addAttributeCheck( context => {
            if ( context.endsWith( 'iframe' ) ) {
                return true;
            }
        } );						
                           // View-to-model converter converting a view <iframe> with all its attributes to the model.
        editor.conversion.for( 'upcast' ).elementToElement( {
            view: 'iframe',
            model: ( viewElement, modelWriter ) => {
                return modelWriter.createElement( 'iframe', viewElement.getAttributes() );
            }
        } );
    
        // Model-to-view converter for the <iframe> element (attributes are converted separately).
        editor.conversion.for( 'downcast' ).elementToElement( {
            model: 'iframe',
            view: 'iframe'
        } );
    
        // Model-to-view converter for <iframe> attributes.
        // Note that a lower-level, event-based API is used here.
        editor.conversion.for( 'downcast' ).add( dispatcher => {
            dispatcher.on( 'attribute', ( evt, data, conversionApi ) => {
                // Convert <iframe> attributes only.
                if ( data.item.name != 'iframe' ) {
                    return;
                }
    
                const viewWriter = conversionApi.writer;
                const viewIframe = conversionApi.mapper.toViewElement( data.item );
    
                // In the model-to-view conversion we convert changes.
                // An attribute can be added or removed or changed.
                // The below code handles all 3 cases.
                if ( data.attributeNewValue ) {
                    viewWriter.setAttribute( data.attributeKey, data.attributeNewValue, viewIframe );
                } else {
                    viewWriter.removeAttribute( data.attributeKey, viewIframe );
                }
            } );
        } );
        },
]
