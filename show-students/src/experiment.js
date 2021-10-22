const { Component } = wp.element;
const { Spinner, PanelBody, PanelRow, SelectControl } = wp.components;

import { __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { useBlockProps, RichText, InspectorControls, BlockEdit } from '@wordpress/block-editor';

/**
 * Separate class stopped working.
 * 
 * UPDATE 19.10.2021 20:23
 * It started working again, fixed it in a very simple way.
 * 
 * UPDATE UPDATE ABORT 20.10.2021 15:07
 * REST API returns Post data only (excluding the custom meta) which makes the task significantly harder. And this way,
 * I won't be able to use the student shortcode, which violates the task's requirement.
 * 
 * Will stick to <ServerSideRender>.
 */
class BlockEditStudents extends Component {
    constructor(props, decodedProps) {
        super(props);
        this.state = {
            students: [],
            loading: true
        }
        this.decodedProps = decodedProps;

        this.studentStatus = 'active';
        this.studentCount = 3;

        console.log("Objects PROPS:" + JSON.stringify(props));
    }

    componentDidMount() {
        this.runApiFetch();
    }

    runApiFetch() {
        wp.apiFetch({
            path: 'onboarding-plugin/v1/students',
        }).then(data => {
            this.setState({
                students: data,
                loading: false
            });

            console.log("THIS WAS TAKEN: " + data);

        });
    }

    displayContent() {
        if (this.state.loading) {
            return <Spinner />
        } else {
            return (
                <>
                    <div {...this.decodedProps}> 
                        Data is loaded

                        {this.state.students.map((student) => (
                            <div class="student" style={{border: '2px solid black'}}>
                                <h2>{JSON.stringify(student.post_title)}</h2>
                                <p>ID: {JSON.stringify(student.ID)}</p>
                                <p>Grade: {JSON.stringify(student)}</p>
                            </div>
                        ))}

                    </div>

                    <div>
                        <InspectorControls>
                            <PanelBody
                                title="Most awesome settings ever"
                                initialOpen={true}
                            >

                                <PanelRow>
                                    <SelectControl
                                        label="Students to show?"
                                        // value={props.attributes.studentStatus}
                                        options={[
                                            { label: "Active", value: 'active' },
                                            { label: "Inactive", value: 'inactive' },
                                        ]}
                                    // onChange={(newval) => props.setAttributes({ studentStatus: newval })}
                                    />
                                </PanelRow>

                                <PanelRow>
                                    <NumberControl
                                    // onChange={(newval) => props.setAttributes({ studentsPerPage: newval })}
                                    // value={props.attributes.studentsPerPage}
                                    />
                                </PanelRow>

                            </PanelBody>
                        </InspectorControls>
                    </div>

                </>

            );
        }

    }

    render() {
        return (
            <div>

                {this.displayContent()}

            </div>
        );

    }
}
export default BlockEditStudents;