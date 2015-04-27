$(document).ready(function() {

    function color_element(element, status) {
        var parent_class = '';
        var parent_element = jQuery('#' + element).parents('.form-group', 0);

        switch(status) {
            case 'success':
                parent_class = 'form-group has-success';
                break;
            case 'error':
                parent_class = 'form-group has-error';
                break;
            default:
                parent_class = 'form-group';
                break;
        }

        parent_element.attr('class', parent_class);
    }
    /**
     * Validates time is in correct format.
     *
     * @param time
     *
     * @returns {boolean}
     */
    function validate_time(time) {
        var time_pattern = /^(0?[1-9]|1[0-2]):([0-5]?[0-59])$/;

        return time_pattern.test(time);
    }


    jQuery('#GenerateClock').submit(function(event) {
        event.preventDefault();

        var time = jQuery('#ClockTime').val();

        var correct = 1;
        if(!validate_time(time)) {
            correct = 0;
            color_element('ClockTime', 'error');
        } else {
            color_element('ClockTime', 'success');
        }

        if(correct) {
            var image_code = '<img src="./clock.php?time=' + time + '" alt="' + time + '" />';
            jQuery('#ClockImage').html(image_code);
        }
    })
})