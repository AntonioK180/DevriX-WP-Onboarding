/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import { __experimentalNumberControl as NumberControl, PanelBody, PanelRow, SelectControl } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export function Edit(props) {

    return (
        <>
            <InspectorControls>
                <PanelBody
                    title="Most awesome settings ever"
                    initialOpen={true}
                >

                    <PanelRow>
                        <SelectControl
                            label="Students to show?"
                            value={props.attributes.studentStatus}
                            options={[
                                { label: "Active", value: 'active' },
                                { label: "Inactive", value: 'inactive' },
                            ]}
                            onChange={(newval) => props.setAttributes({ studentStatus: newval })}
                        />
                    </PanelRow>

                    <PanelRow>
                        <NumberControl
                            onChange={(newval) => props.setAttributes({ studentsPerPage: newval })}
                            value={props.attributes.studentsPerPage}
                        />
                    </PanelRow>

                </PanelBody>
            </InspectorControls>

            <div {...useBlockProps()}>
                <ServerSideRender
                    block="create-block/show-students"
                    attributes={ props.attributes }
                />
            </div>
        </>
    );


}
