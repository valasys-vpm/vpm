
function trigger_pnofify(type = 'default', title = '', message = '')
{
    new PNotify({
        title: (title === '') ? false : title,
        text: (message === '') ? false : message,
        type: type,
        icon: (title === '') ? 'none' : true,
        buttons: {
            sticker: false
        },
        delay: 5000
    });

}
