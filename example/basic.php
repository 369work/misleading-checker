<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Yakki\Preset\CosmeticsPreset;
use Yakki\YakkiChecker;

// ============================================================
// 基本的な使い方
// ============================================================

$checker = new YakkiChecker();

$text = <<<TEXT
【期間限定】奇跡の美容液が今だけ半額！
必ず治る肌トラブル、医師が推薦する確実な効果。
絶対に後悔しない、業界No.1の実績！
TEXT;

echo "=== チェック対象 ===\n";
echo $text . "\n\n";

$result = $checker->check($text);

echo "=== チェック結果 ===\n";
echo "検出数: {$result->count()}\n\n";

foreach ($result->getViolations() as $v) {
    echo "❌ [{$v->category}] {$v->word}\n";
    echo "   理由: {$v->reason}\n";
    echo "   提案: {$v->suggestion}\n\n";
}

// ============================================================
// 化粧品プリセットを追加
// ============================================================

echo "=== 化粧品プリセット適用後 ===\n";
$checker->applyPreset(new CosmeticsPreset());

$cosmeticsText = '美白効果でシミが薄くなる！コラーゲン生成を促進';
$result2 = $checker->check($cosmeticsText);

foreach ($result2->getViolations() as $v) {
    echo "❌ [{$v->category}] {$v->word}\n";
    echo "   提案: {$v->suggestion}\n\n";
}

// ============================================================
// JSON出力
// ============================================================

echo "=== JSON出力 ===\n";
echo $result->toJSON(JSON_PRETTY_PRINT) . "\n";
