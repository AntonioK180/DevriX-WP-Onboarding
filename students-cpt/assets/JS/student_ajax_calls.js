var student_activated = document.getElementsByClassName("active_student_checkbox");

for (let i = 0; i < student_activated.length; i++) {
    student_activated[i].addEventListener("change", function($) {
        update_activated_box(student_activated[i].id);
    });

}

function update_activated_box($student_index) {
    var data = {
        'action': 'toggle_student_activated',
        'student_activated': student_activated.checked,
        'student_id': $student_index,
    };

    jQuery.post(ajaxurl, data, function(response) {});
}

document.onload = function() {
    alert('JS Included');
}