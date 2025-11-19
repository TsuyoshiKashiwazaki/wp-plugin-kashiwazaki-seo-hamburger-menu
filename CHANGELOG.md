# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2024-11-19

### Added
- リセット機能にnonce検証を追加

### Changed
- なし

### Deprecated
- なし

### Removed
- なし

### Fixed
- チェックボックスの設定（ホームリンク表示、構造化データ）が正しく保存されない問題を修正
- 設定値のサニタイズ処理を追加しセキュリティを強化
- URL比較ロジックを改善し、現在のページ判定の精度を向上

### Security
- 設定のサニタイズコールバック関数を追加
- リセット機能のnonce検証を強化

## [1.0.0] - 2024-11-07

### Added
- 初回リリース
- 指定したブレークポイント以下でハンバーガーメニューを表示
- メニュー位置のカスタマイズ機能（左上、右上、カスタム位置）
- 既存WordPressメニューの反映機能
- カスタムメニュー作成機能
- サブメニュー対応
- 色設定の完全カスタマイズ機能
  - メニュー文字色
  - サブメニュー文字色
  - ホバー時の色
  - メニュー背景色
  - ハンバーガーボタン色
  - ハンバーガー3本線色
- 構造化データ（SiteNavigationElement）出力機能
- ホームリンク表示/非表示切り替え機能
- 設定のデフォルトリセット機能
- WordPress カラーピッカー対応
- 管理画面での設定UI
- レスポンシブ対応
- モバイルフレンドリーなUI/UX

### Changed
- なし

### Deprecated
- なし

### Removed
- なし

### Fixed
- なし

### Security
- なし

---

## リリースノート形式

リリースノートは以下のカテゴリーで分類されます：

- **Added**: 新機能
- **Changed**: 既存機能の変更
- **Deprecated**: 非推奨となった機能（近い将来削除予定）
- **Removed**: 削除された機能
- **Fixed**: バグ修正
- **Security**: セキュリティ関連の修正
