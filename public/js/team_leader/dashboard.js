
let URL = $('meta[name="base-path"]').attr('content');

$(function (){
    initCounts();
});

function initCounts() {
    $.ajax({
        url: URL + '/team-leader/dashboard/get-counts',
        dataType: 'JSON',
        success: function(response) {
            $(".lead-counts").text(0);
            $.each(response, function(key, value) {
                $('#count-' + key).text(value);
            });
        }
    });
}
