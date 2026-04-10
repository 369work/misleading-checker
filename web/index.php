<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use MisleadingChecker\Preset\CosmeticsPreset;
use MisleadingChecker\Preset\MedicalDevicePreset;
use MisleadingChecker\Preset\GeneralSupplementPreset;
use MisleadingChecker\Preset\FunctionalFoodPreset;
use MisleadingChecker\Preset\TokuhoPreset;
use MisleadingChecker\MisleadingChecker;

$inputText      = '';
$result         = null;
$presets        = [];
$supplementType = '';
$errorMsg       = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputText      = $_POST['text'] ?? '';
    $presets        = $_POST['presets'] ?? [];
    $supplementType = $_POST['supplement_type'] ?? '';

    if ($inputText !== '') {
        $checker = new MisleadingChecker();

        if (in_array('cosmetics', $presets, true)) {
            $checker->applyPreset(new CosmeticsPreset());
        }

        switch ($supplementType) {
            case 'general':
                $checker->applyPreset(new GeneralSupplementPreset());
                break;
            case 'functional':
                $checker->applyPreset(new FunctionalFoodPreset());
                break;
            case 'tokuho':
                $checker->applyPreset(new TokuhoPreset());
                break;
        }

        if (in_array('medical_device', $presets, true)) {
            $checker->applyPreset(new MedicalDevicePreset());
        }

        try {
            $result = $checker->check($inputText);
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
        }
    }
}

/**
 * テキスト内のNG表現をハイライトしたHTMLを生成
 */
function highlightViolations(string $text, array $violations): string
{
    if (empty($violations)) {
        return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
    }

    // オフセット順にソート（後ろから置換するため降順）
    usort($violations, fn($a, $b) => $b->offset - $a->offset);

    $html = $text;
    foreach ($violations as $v) {
        $before  = substr($html, 0, $v->offset);
        $match   = substr($html, $v->offset, $v->length);
        $after   = substr($html, $v->offset + $v->length);
        $escaped = htmlspecialchars($match, ENT_QUOTES, 'UTF-8');
        $tooltip = htmlspecialchars($v->reason, ENT_QUOTES, 'UTF-8');
        $html    = $before
            . '<mark class="yakki-highlight" data-category="' . htmlspecialchars($v->category, ENT_QUOTES, 'UTF-8')
            . '" title="' . $tooltip . '">' . $escaped . '</mark>'
            . $after;
    }

    $parts = preg_split('/(<mark[^>]*>.*?<\/mark>)/us', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    $output = '';
    foreach ($parts as $part) {
        if (str_starts_with($part, '<mark')) {
            $output .= $part;
        } else {
            $output .= nl2br(htmlspecialchars($part, ENT_QUOTES, 'UTF-8'));
        }
    }

    return $output;
}

$categoryLabels = [
    'yakki'                => '薬機法',
    'keihin'               => '景品表示法',
    'cosmetics'            => '化粧品',
    'supplement_general'   => '一般健康食品',
    'supplement_functional'=> '機能性表示食品',
    'supplement_tokuho'    => 'トクホ',
    'medical_device'       => '医療機器',
    'custom'               => 'カスタム',
];

$categoryColors = [
    'yakki'                => 'bg-red-100 text-red-800 border-red-300',
    'keihin'               => 'bg-amber-100 text-amber-800 border-amber-300',
    'cosmetics'            => 'bg-pink-100 text-pink-800 border-pink-300',
    'supplement_general'   => 'bg-green-100 text-green-800 border-green-300',
    'supplement_functional'=> 'bg-teal-100 text-teal-800 border-teal-300',
    'supplement_tokuho'    => 'bg-cyan-100 text-cyan-800 border-cyan-300',
    'medical_device'       => 'bg-purple-100 text-purple-800 border-purple-300',
    'custom'               => 'bg-gray-100 text-gray-800 border-gray-300',
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>misleading-checker - 薬機法・景品表示法チェッカー</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans JP', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .yakki-highlight {
            background-color: #FEE2E2;
            border-bottom: 2px solid #EF4444;
            padding: 1px 2px;
            border-radius: 2px;
            cursor: help;
            position: relative;
        }
        .yakki-highlight[data-category="keihin"] {
            background-color: #FEF3C7;
            border-bottom-color: #F59E0B;
        }
        .yakki-highlight[data-category="cosmetics"] {
            background-color: #FCE7F3;
            border-bottom-color: #EC4899;
        }
        .yakki-highlight[data-category="supplement_general"] {
            background-color: #D1FAE5;
            border-bottom-color: #10B981;
        }
        .yakki-highlight[data-category="supplement_functional"] {
            background-color: #CCFBF1;
            border-bottom-color: #14B8A6;
        }
        .yakki-highlight[data-category="supplement_tokuho"] {
            background-color: #CFFAFE;
            border-bottom-color: #06B6D4;
        }
        .yakki-highlight[data-category="medical_device"] {
            background-color: #EDE9FE;
            border-bottom-color: #8B5CF6;
        }
        /* サプリラジオボタンエリアのアニメーション */
        #supplement-options {
            transition: opacity 0.2s ease;
        }
        #supplement-options.hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">
                <span class="font-mono text-indigo-600">misleading-checker</span>
            </h1>
            <p class="mt-1 text-sm text-gray-500">薬機法・景品表示法のNG表現を検出するチェッカー</p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8" role="main">
        <form method="POST" action="" class="space-y-6">
            <!-- テキスト入力 -->
            <div>
                <label for="check-text" class="block text-sm font-medium text-gray-700 mb-2">
                    チェックするテキスト
                </label>
                <textarea
                    id="check-text"
                    name="text"
                    rows="8"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors"
                    placeholder="記事やLPのテキストをここに貼り付けてください..."
                    aria-describedby="text-hint"
                ><?= htmlspecialchars($inputText, ENT_QUOTES, 'UTF-8') ?></textarea>
                <p id="text-hint" class="mt-1 text-xs text-gray-400">広告文、記事本文、LP原稿などをチェックできます</p>
            </div>

            <!-- プリセット選択 -->
            <fieldset>
                <legend class="block text-sm font-medium text-gray-700 mb-3">業種別プリセット（任意）</legend>
                <div class="space-y-4">

                    <!-- 化粧品 -->
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="presets[]" value="cosmetics"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            <?= in_array('cosmetics', $presets) ? 'checked' : '' ?>>
                        <span class="text-sm text-gray-700">化粧品</span>
                    </label>

                    <!-- サプリメント・健康食品（チェックボックス＋ラジオボタン） -->
                    <div>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="supplement-toggle"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                <?= $supplementType !== '' ? 'checked' : '' ?>>
                            <span class="text-sm text-gray-700">サプリメント・健康食品</span>
                        </label>

                        <!-- サプリ区分ラジオボタン -->
                        <div id="supplement-options"
                            class="mt-2 ml-6 p-3 bg-white border border-gray-200 rounded-lg space-y-2 <?= $supplementType === '' ? 'hidden' : '' ?>"
                            role="group"
                            aria-label="健康食品の区分">
                            <p class="text-xs text-gray-500 mb-2">区分を選択してください（区分によってNGパターンが異なります）</p>

                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="radio" name="supplement_type" value="general"
                                    class="mt-0.5 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    <?= $supplementType === 'general' ? 'checked' : '' ?>>
                                <span class="text-sm">
                                    <span class="font-medium text-gray-800">一般健康食品・サプリメント</span>
                                    <span class="block text-xs text-gray-500">機能性表示・トクホの届出なし。効果・効能の訴求は原則すべてNG</span>
                                </span>
                            </label>

                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="radio" name="supplement_type" value="functional"
                                    class="mt-0.5 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    <?= $supplementType === 'functional' ? 'checked' : '' ?>>
                                <span class="text-sm">
                                    <span class="font-medium text-gray-800">機能性表示食品</span>
                                    <span class="block text-xs text-gray-500">消費者庁への届出済み。届出した機能性の範囲内はOK、疾病治療予防はNG</span>
                                </span>
                            </label>

                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="radio" name="supplement_type" value="tokuho"
                                    class="mt-0.5 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    <?= $supplementType === 'tokuho' ? 'checked' : '' ?>>
                                <span class="text-sm">
                                    <span class="font-medium text-gray-800">特定保健用食品（トクホ）</span>
                                    <span class="block text-xs text-gray-500">消費者庁の個別許可済み。許可を受けた保健用途の表示はOK、疾病治療予防はNG</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- 医療機器 -->
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="presets[]" value="medical_device"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            <?= in_array('medical_device', $presets) ? 'checked' : '' ?>>
                        <span class="text-sm text-gray-700">医療機器</span>
                    </label>

                </div>
            </fieldset>

            <!-- 送信ボタン -->
            <div>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    チェックする
                </button>
            </div>
        </form>

        <?php if ($errorMsg): ?>
            <div class="mt-8 p-4 bg-red-50 border border-red-200 rounded-lg" role="alert">
                <p class="text-red-800"><?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <!-- チェック結果 -->
            <section class="mt-8 space-y-6" aria-label="チェック結果">
                <!-- サマリー -->
                <div class="p-4 rounded-lg border <?= $result->hasViolations() ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200' ?>">
                    <?php if ($result->hasViolations()): ?>
                        <p class="text-red-800 font-medium">
                            ⚠ <?= $result->count() ?>件のNG表現が見つかりました
                        </p>
                    <?php else: ?>
                        <p class="text-green-800 font-medium">
                            ✅ NG表現は検出されませんでした
                        </p>
                    <?php endif; ?>
                </div>

                <?php if ($result->hasViolations()): ?>
                    <!-- ハイライト表示 -->
                    <div>
                        <h2 class="text-sm font-medium text-gray-700 mb-2">テキストプレビュー</h2>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg leading-relaxed text-gray-800">
                            <?= highlightViolations($inputText, $result->getViolations()) ?>
                        </div>
                    </div>

                    <!-- 詳細一覧 -->
                    <div>
                        <h2 class="text-sm font-medium text-gray-700 mb-2">検出一覧</h2>
                        <div class="space-y-3">
                            <?php foreach ($result->getViolations() as $v): ?>
                                <?php
                                    $catLabel = $categoryLabels[$v->category] ?? $v->category;
                                    $catColor = $categoryColors[$v->category] ?? $categoryColors['custom'];
                                ?>
                                <div class="p-4 bg-white border border-gray-200 rounded-lg">
                                    <div class="flex items-start gap-3">
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded border <?= $catColor ?> whitespace-nowrap mt-0.5">
                                            <?= htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900">
                                                「<?= htmlspecialchars($v->word, ENT_QUOTES, 'UTF-8') ?>」
                                            </p>
                                            <p class="mt-1 text-sm text-gray-600">
                                                <?= htmlspecialchars($v->reason, ENT_QUOTES, 'UTF-8') ?>
                                            </p>
                                            <?php if ($v->suggestion): ?>
                                                <p class="mt-1 text-sm text-indigo-600">
                                                    💡 <?= htmlspecialchars($v->suggestion, ENT_QUOTES, 'UTF-8') ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <footer class="border-t border-gray-200 mt-16">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <p class="text-xs text-gray-400 text-center font-mono">
                Copyright &copy; 2025 miroku (369work)
            </p>
        </div>
    </footer>

    <script>
        // サプリチェックボックスでラジオボタンエリアの表示切替
        const toggle  = document.getElementById('supplement-toggle');
        const options = document.getElementById('supplement-options');
        const radios  = options.querySelectorAll('input[type="radio"]');

        toggle.addEventListener('change', function () {
            if (this.checked) {
                options.classList.remove('hidden');
                // 未選択なら最初のラジオをデフォルト選択
                const anyChecked = Array.from(radios).some(r => r.checked);
                if (!anyChecked) radios[0].checked = true;
            } else {
                options.classList.add('hidden');
                // チェックを外したらラジオもリセット
                radios.forEach(r => r.checked = false);
            }
        });
    </script>
</body>
</html>
