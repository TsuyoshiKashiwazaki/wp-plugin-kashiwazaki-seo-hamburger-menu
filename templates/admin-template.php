<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>Kashiwazaki SEO Hamburger Menu 設定</h1>

    <form method="post" action="options.php">
        <?php
        settings_fields('kshm_options');
        do_settings_sections('kshm-settings');
        submit_button();
        ?>
    </form>

    <form method="post" style="margin-top: 20px;">
        <?php wp_nonce_field('kshm_reset_defaults', 'kshm_reset_nonce'); ?>
        <input type="submit" name="kshm_reset_defaults" class="button button-secondary" value="デフォルトに戻す" onclick="return confirm('本当にデフォルト設定に戻しますか？現在の設定は失われます。');" />
    </form>

    <div class="kshm-help">
        <h3>プラグインの特徴</h3>
        <ul>
            <li><strong>レスポンシブ対応</strong>: 指定したブレークポイント以下でハンバーガーメニューが自動表示</li>
            <li><strong>柔軟なメニュー設定</strong>: 既存のWordPressメニューを反映するか、カスタムメニューを作成可能</li>
            <li><strong>階層メニュー対応</strong>: サブメニューも適切に表示され、親子関係が視覚的に分かりやすい</li>
            <li><strong>カスタマイズ可能</strong>: メニュー位置、色、背景透明度を自由に調整</li>
            <li><strong>SEO対策</strong>: SiteNavigationElementの構造化マークアップで検索エンジン最適化</li>
            <li><strong>軽量設計</strong>: 必要最小限のコードで高速動作</li>
        </ul>

        <h3>設定項目の説明</h3>
        <ul>
            <li><strong>ブレークポイント</strong>: ハンバーガーメニューが表示される画面幅（px）</li>
            <li><strong>メニュー位置</strong>: 左上または右上の表示位置を選択</li>
            <li><strong>メニュータイプ</strong>: 「既存のメニューを反映」でWordPressメニュー、「カスタムメニュー」で独自設定</li>
            <li><strong>既存メニュー選択</strong>: 複数メニューがある場合の選択（メニュータイプが「既存」の場合のみ表示）</li>
            <li><strong>カスタムメニュー項目</strong>: メニュー名|URLの形式で1行に1つずつ記述（メニュータイプが「カスタム」の場合のみ表示）</li>
            <li><strong>メニュー文字色</strong>: メインメニュー項目の文字色</li>
            <li><strong>サブメニュー文字色</strong>: サブメニュー項目の文字色</li>
            <li><strong>メニュー背景色</strong>: メニューコンテンツの背景色</li>
            <li><strong>背景透明度</strong>: メニュー背景の透明度（0%: 完全透明、100%: 完全不透明）</li>
            <li><strong>ハンバーガーボタン色</strong>: ハンバーガーメニューボタンとバッテンボタンの色</li>
            <li><strong>ハンバーガー3本線色</strong>: ハンバーガーボタン内の3本線の色</li>
            <li><strong>ホバー時の色</strong>: メニューリンクにマウスを乗せた時の色</li>
            <li><strong>構造化マークアップ</strong>: SiteNavigationElementのJSON-LD出力でSEO対策</li>
        </ul>
    </div>
</div>



<style>
.kshm-help {
    margin-top: 30px;
    padding: 20px;
    background: #f0f8ff;
    border: 1px solid #b0d4f1;
    border-radius: 5px;
}

.kshm-help ul {
    margin-left: 20px;
}

.kshm-help li {
    margin: 8px 0;
}

.kshm-help h3 {
    margin-top: 25px;
    margin-bottom: 15px;
    color: #23282d;
}

.kshm-help h3:first-child {
    margin-top: 0;
}
</style>
