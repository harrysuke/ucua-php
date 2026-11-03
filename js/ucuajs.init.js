$(document).ready(function() {
    $('.js-select').select2();

    const preview = (file) => {
    const fr = new FileReader();
    fr.onload = (ev) => {
        $('#preview').append($("<img src='"+ fr.result +"' alt='"+ file.name +"' class='img-thumbnail evidence'>"));
    };
    fr.readAsDataURL(file);
    };

    const preview2 = (file) => {
    const fr = new FileReader();
    fr.onload = (ev) => {
        $('#preview2').append($("<img src='"+ fr.result +"' alt='"+ file.name +"' class='img-thumbnail evidence'>"));
    };
    fr.readAsDataURL(file);
    };

    $("#files").on("change", (ev) => {
        if (!ev.target.files) return; // Do nothing.
        [...ev.target.files].forEach(preview);
    });

    $("#files2").on("change", (ev) => {
        if (!ev.target.files) return; // Do nothing.
        [...ev.target.files].forEach(preview2);
    });

    //Checking image file extension
    $('#files').bind('change', function() {
        var ext = $('#files').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif','png','jpg','jpeg']) == -1){
            alert('Please choose an image');
            $('#files').val('');
            return false;
        }else{
            console.log(ext);
        }
    });
    $('#files2').bind('change', function() {
        var ext = $('#files2').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif','png','jpg','jpeg']) == -1){
            alert('Please choose an image');
            $('#files2').val('');
            return false;
        }else{
            console.log(ext);
        }
    });

});