
jQuery(document).ready(function () {
    $('.mydatepicker, #datepicker').datepicker({
        format: 'dd/mm/yyyy'
    });
    // For select 2
    $(".select2").select2();
    $('.selectpicker').selectpicker();
    // For multiselect
    $('#pre-selected-options').multiSelect();
    $('#optgroup').multiSelect({selectableOptgroup: true});

    $('#public-methods').multiSelect();
    $('#select-all').click(function () {
        $('#public-methods').multiSelect('select_all');
        return false;
    });
    $('#deselect-all').click(function () {
        $('#public-methods').multiSelect('deselect_all');
        return false;
    });
    $('#refresh').on('click', function () {
        $('#public-methods').multiSelect('refresh');
        return false;
    });
    $('#add-option').on('click', function () {
        $('#public-methods').multiSelect('addOption', {value: 42, text: 'test 42', index: 0});
        return false;
    });

    // CK Editor
    if ($('.cke-editor').length && $.fn.ckeditor) {
        $('.cke-editor').each(function () {
            $(this).ckeditor();
        });
    }

    // Upload image
    $(document).on('click', '.image-browser-btn', function() {
        var $item = $(this);
        window.KCFinder = {
            callBack: function (url) {
                //alert("vinh");
                window.KCFinder = null;
                $item.attr('src', url);
                $($item.data('hidden-class')).val(amz_domain + url);
                $.fancybox.close();
            }
        };
        $.fancybox.open({
            href: amz_root_link + '/web/backend/plugins/bower_components/cke-editor/kcfinder/browse.php?type=images&dir=images',
            type: 'iframe',
            padding: 5,
            minHeight: 600
        });
    });
    $(document).on('click', '.value-field', function() {
        var $item = $(this);
        window.KCFinder = {
            callBack: function (url) {
                window.KCFinder = null;
                $item.val(amz_domain + url);
                $.fancybox.close();
            }
        };
        $.fancybox.open({
            href: amz_root_link + '/web/backend/plugins/bower_components/cke-editor/kcfinder/browse.php?type=file&dir=file',
            type: 'iframe',
            padding: 5,
            minHeight: 600
        });
    });
});