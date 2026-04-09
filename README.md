# yakki-checker

薬機法（医薬品医療機器等法）・景品表示法のNG表現を検出するPHPライブラリ。

記事やLPの入稿前チェック、制作会社やライターへの納品前品質管理に使えます。

## 特徴

- 薬機法・景品表示法のNG表現を自動検出
- 検出位置・理由・代替表現の提案付き
- 業種別プリセット（化粧品・サプリ・医療機器）
- カスタム辞書の追加対応
- JSON / 配列でのエクスポート
- フレームワーク非依存（WordPress / Laravel / CakePHP 等どこでも使える）

## 要件

- PHP 8.1 以上
- ext-mbstring

## インストール

```bash
composer require 369work/yakki-checker
```

## 基本的な使い方

```php
use Yakki\YakkiChecker;

$checker = new YakkiChecker();
$result = $checker->check('この商品は必ず治る！今だけ半額');

if ($result->hasViolations()) {
    foreach ($result->getViolations() as $v) {
        echo "❌ {$v->word}: {$v->reason}\n";
        echo "   → 提案: {$v->suggestion}\n";
    }
}
```

## 業種別プリセット

```php
use Yakki\Preset\CosmeticsPreset;
use Yakki\Preset\SupplementPreset;
use Yakki\Preset\MedicalDevicePreset;

// 化粧品向けチェック
$checker->applyPreset(new CosmeticsPreset());

// サプリメント向けチェック
$checker->applyPreset(new SupplementPreset());

// 医療機器向けチェック
$checker->applyPreset(new MedicalDevicePreset());
```

## カスタム辞書

```php
use Yakki\Dictionary\CustomDictionary;

$custom = new CustomDictionary('自社ルール');
$custom->addEntry('当社比', '比較対象を明確にしてください', '具体的な数値で比較');

$checker->addDictionary($custom);
```

### JSONから辞書を読み込む

```php
$json = file_get_contents('my-dictionary.json');
$dict = CustomDictionary::fromJSON($json, 'カスタム');
$checker->addDictionary($dict);
```

## 出力

```php
// 配列
$result->toArray();

// JSON
$result->toJSON(JSON_PRETTY_PRINT);

// カテゴリ別に集計
$result->groupByCategory();
```

## テスト

```bash
composer test
```

## ⚠️ 免責事項

このライブラリは薬機法・景品表示法に関するNG表現の検出を**補助**するツールであり、法的なアドバイスを提供するものではありません。

- 検出結果はすべてのNG表現を網羅するものではなく、**検出されなかった表現が合法であることを保証するものではありません**
- 検出された表現が、文脈によっては問題なく使用できるケースもあります
- 法令・ガイドラインは改正されることがあり、本ライブラリの辞書が常に最新の法令を反映しているとは限りません
- 最終的な判断は、最新の法令・各媒体のポリシーをご自身でご確認のうえ、必要に応じて専門家（弁護士・薬事法務の専門家等）にご相談ください

本ライブラリの使用によって生じたいかなる損害・不利益・行政処分等についても、作者は一切の責任を負いません。

## ライセンス

GPL-3.0-or-later

Copyright (c) 2025 miroku (369work)
