jQuery(document).ready(function ($) {
    // カラーピッカーの初期化
    $('.kshm-color-picker').wpColorPicker();

    // カスタムメニュー項目の条件表示
    function toggleCustomMenuField() {
        var menuType = $('input[name="kshm_options[menu_type]"]:checked').val();
        var customMenuField = $('tr:has([name="kshm_options[custom_menu_items]"])');

        if (menuType === 'custom') {
            customMenuField.show();
        } else {
            customMenuField.hide();
        }
    }

    // 既存メニュー選択の条件表示
    function toggleExistingMenuField() {
        var menuType = $('input[name="kshm_options[menu_type]"]:checked').val();
        var existingMenuField = $('tr:has([name="kshm_options[existing_menu]"])');

        if (menuType === 'default') {
            existingMenuField.show();
        } else {
            existingMenuField.hide();
        }
    }
    
    // カスタム位置設定の表示/非表示
    function toggleCustomPosition() {
        if ($('input[name="kshm_options[menu_position]"]:checked').val() === 'custom') {
            $('#kshm-custom-position').show();
        } else {
            $('#kshm-custom-position').hide();
        }
    }

    // メニュータイプ変更時の処理
    $('input[name="kshm_options[menu_type]"]').on('change', function () {
        toggleCustomMenuField();
        toggleExistingMenuField();
    });
    
    // メニュー位置変更時の処理
    $('input[name="kshm_options[menu_position]"]').on('change', toggleCustomPosition);
    

    // 初期表示時の処理
    toggleCustomMenuField();
    toggleExistingMenuField();
    toggleCustomPosition();
});
