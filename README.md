# Kashiwazaki SEO Hamburger Menu

[![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-hamburger-menu)
[![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/php-7.4%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-GPL%20v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

指定した幅になったらハンバーガーメニューを表示するWordPressプラグインです。

## 概要

Kashiwazaki SEO Hamburger Menuは、レスポンシブデザインに対応したハンバーガーメニュープラグインです。指定したブレークポイント以下でハンバーガーメニューを表示し、モバイルフレンドリーなナビゲーションを実現します。

## 主な機能

- 指定したブレークポイント以下でハンバーガーメニューを表示
- メニュー位置のカスタマイズ（左上、右上、カスタム位置）
- 既存のWordPressメニューを反映またはカスタムメニューを作成
- 色設定の完全カスタマイズ
- サブメニュー対応
- 構造化データ（SiteNavigationElement）出力対応
- モバイルフレンドリーなUI/UX

## 設定可能項目

### 基本設定
- **ブレークポイント（px）**: ハンバーガーメニューを表示する画面幅
- **メニュー位置**: 左上、右上、カスタム（自由な位置指定）
- **メニュータイプ**: 既存メニュー反映 / カスタムメニュー

### デザイン設定
- **メニュー文字色**: メニュー項目の文字色
- **サブメニュー文字色**: サブメニュー項目の文字色
- **ホバー時の色**: マウスオーバー時の色
- **メニュー背景色**: メニューの背景色
- **ハンバーガーボタン色**: ハンバーガーアイコンの背景色
- **ハンバーガー3本線色**: ハンバーガーアイコンの線の色

### SEO設定
- **構造化マークアップ**: SiteNavigationElementの構造化データ出力

## インストール

### 手動インストール

1. プラグインファイルを `/wp-content/plugins/kashiwazaki-seo-hamburger-menu/` ディレクトリにアップロード
2. WordPress管理画面の「プラグイン」メニューでプラグインを有効化
3. 「Kashiwazaki SEO Hamburger Menu」メニューから設定を行う

### GitHub からのインストール

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-hamburger-menu.git kashiwazaki-seo-hamburger-menu
```

## 使い方

### 既存メニューを使用する場合

1. WordPress管理画面で「Kashiwazaki SEO Hamburger Menu」を開く
2. 「メニュータイプ」で「既存のメニューを反映」を選択
3. 「既存メニュー選択」から使用するメニューを選択
4. ブレークポイントや色などを設定
5. 「変更を保存」をクリック

### カスタムメニューを作成する場合

1. WordPress管理画面で「Kashiwazaki SEO Hamburger Menu」を開く
2. 「メニュータイプ」で「カスタムメニュー」を選択
3. 「カスタムメニュー項目」に以下の形式でメニューを記述：

```
メニュー名|URL|
メニュー名|URL|サブメニュー1:URL1,サブメニュー2:URL2
```

例：
```
ホーム|/|
会社概要|/about/|
サービス|/services/|Web制作:/services/web/,SEO対策:/services/seo/
お問い合わせ|/contact/
```

4. ブレークポイントや色などを設定
5. 「変更を保存」をクリック

## 動作環境

- **WordPress**: 5.0 以上
- **PHP**: 7.4 以上
- **対応ブラウザ**: Chrome, Firefox, Safari, Edge（最新版）

## よくある質問

### Q. どのようなメニューが表示されますか？

既存のWordPressメニューを反映するか、カスタムメニューを作成するかを選択できます。カスタムメニューの場合、メニュー名|URLの形式で設定できます。

### Q. ブレークポイントはどのように設定すればよいですか？

一般的なモバイルデバイスの幅に合わせて設定することをお勧めします。
- 768px（タブレット）
- 480px（スマートフォン）

### Q. メニューの位置は変更できますか？

はい、左上、右上、カスタム位置から選択できます。カスタム位置では、上からの距離と左右からの距離を自由に設定できます。

### Q. 色のカスタマイズは可能ですか？

はい、メニュー文字色、背景色、ホバー色、ハンバーガーボタンの色など、すべての色をカスタマイズできます。

### Q. 構造化データとは何ですか？

SiteNavigationElementの構造化データを出力することで、Googleなどの検索エンジンがサイトのナビゲーション構造を理解しやすくなり、SEO効果が期待できます。

## 開発者向け情報

### ファイル構成

```
kashiwazaki-seo-hamburger-menu/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       ├── admin.js
│       └── script.js
├── includes/
│   └── plugin-links.php
├── templates/
│   ├── admin-template.php
│   └── menu-template.php
├── kashiwazaki-seo-hamburger-menu.php
├── readme.txt
├── README.md
├── CHANGELOG.md
└── uninstall.php
```

### フィルターフック

現在、カスタムフィルターフックはありません。今後のバージョンで追加予定です。

### アクションフック

現在、カスタムアクションフックはありません。今後のバージョンで追加予定です。

## 変更履歴

変更履歴の詳細は [CHANGELOG.md](CHANGELOG.md) をご覧ください。

## ライセンス

このプラグインはGPLv2以降のライセンスで公開されています。詳細は [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) をご覧ください。

## 作者

**柏崎剛 (Tsuyoshi Kashiwazaki)**

- Website: [https://www.tsuyoshikashiwazaki.jp](https://www.tsuyoshikashiwazaki.jp)
- Profile: [https://www.tsuyoshikashiwazaki.jp/profile/](https://www.tsuyoshikashiwazaki.jp/profile/)
- GitHub: [https://github.com/TsuyoshiKashiwazaki](https://github.com/TsuyoshiKashiwazaki)

## サポート

プラグインに関するお問い合わせやバグ報告は、[GitHub Issues](https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-hamburger-menu/issues) までお願いします。

## 貢献

プルリクエストを歓迎します。大きな変更の場合は、まずissueを開いて変更内容を議論してください。

---

Created by [Tsuyoshi Kashiwazaki](https://www.tsuyoshikashiwazaki.jp)
