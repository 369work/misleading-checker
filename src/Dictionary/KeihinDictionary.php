<?php

declare(strict_types=1);

namespace MisleadingChecker\Dictionary;

/**
 * 景品表示法NGワード辞書
 *
 * 不当景品類及び不当表示防止法に基づく
 * 優良誤認・有利誤認につながる表現を収録。
 */
class KeihinDictionary implements DictionaryInterface
{
    public function getCategory(): string
    {
        return 'keihin';
    }

    public function getEntries(): array
    {
        return [
            // === 優良誤認（品質・効果の誇大表示） ===
            [
                'pattern'    => '絶対',
                'reason'     => '効果を断定する表現は優良誤認のおそれがあります',
                'suggestion' => '自信を持っておすすめ / 期待できる',
            ],
            [
                'pattern'    => '必ず',
                'reason'     => '効果を保証する表現は優良誤認のおそれがあります',
                'suggestion' => '多くの方に / 実感される方が多い',
            ],
            [
                'pattern'    => '永久',
                'reason'     => '永続的な効果を暗示する表現は優良誤認のおそれがあります',
                'suggestion' => '長期間 / 長くお使いいただける',
            ],
            [
                'pattern'    => '完全',
                'reason'     => '誇大表現にあたるおそれがあります',
                'suggestion' => '徹底した / しっかり',
            ],
            [
                'pattern'    => '最強',
                'reason'     => '根拠のない最上級表現は優良誤認のおそれがあります',
                'suggestion' => '自信作 / おすすめ',
            ],
            [
                'pattern'    => '唯一',
                'reason'     => '根拠がない場合、優良誤認のおそれがあります',
                'suggestion' => '（事実に基づく場合のみ使用可）',
            ],
            [
                'pattern'    => '他にはない',
                'reason'     => '独自性の過度な主張は優良誤認のおそれがあります',
                'suggestion' => 'こだわりの / オリジナル',
            ],

            // === 有利誤認（価格・取引条件の誤解） ===
            [
                'pattern'    => '今だけ',
                'reason'     => '常時実施しているセールに使用すると有利誤認のおそれがあります',
                'suggestion' => '（本当に期間限定の場合のみ使用可。期間を明記）',
            ],
            [
                'pattern'    => '期間限定',
                'reason'     => '実質常時実施の場合、有利誤認のおそれがあります',
                'suggestion' => '（終了日を明記してください）',
            ],
            [
                'pattern'    => '数量限定',
                'reason'     => '実質無制限の場合、有利誤認のおそれがあります',
                'suggestion' => '（具体的な数量を明記してください）',
            ],
            [
                'pattern'    => '半額',
                'reason'     => '通常価格が不明確な場合、有利誤認のおそれがあります',
                'suggestion' => '（通常販売価格と比較期間を明記）',
            ],
            [
                'pattern'    => '激安',
                'reason'     => '価格の優位性が不明確な表現は有利誤認のおそれがあります',
                'suggestion' => 'お求めやすい価格 / お手頃',
            ],
            [
                'pattern'    => '破格',
                'reason'     => '価格の根拠が不明確な表現は有利誤認のおそれがあります',
                'suggestion' => '特別価格（根拠を明記）',
            ],
            [
                'pattern'    => '無料',
                'reason'     => '条件付き無料の場合は条件を明記しないと有利誤認のおそれがあります',
                'suggestion' => '（条件・対象範囲を明記してください）',
            ],
            [
                'pattern'    => 'タダ',
                'reason'     => '条件付き無料の場合は条件を明記しないと有利誤認のおそれがあります',
                'suggestion' => '（条件・対象範囲を明記してください）',
            ],
            [
                'pattern'    => '定価の',
                'reason'     => '比較対照価格が不当な場合、有利誤認のおそれがあります',
                'suggestion' => '（通常販売価格を正確に明記）',
            ],

            // === 体験談・口コミの誇張 ===
            [
                'pattern'    => '※個人の感想です',
                'reason'     => 'この免責表示だけでは優良誤認を回避できません',
                'suggestion' => '体験談の内容自体が誇大でないか確認してください',
            ],
            [
                'pattern'    => '満足度99%',
                'reason'     => '調査方法・対象が不明確な場合、優良誤認のおそれがあります',
                'suggestion' => '（調査概要・出典を明記してください）',
            ],
            [
                'pattern'    => '売上No.1',
                'reason'     => '調査データの出典が必要です',
                'suggestion' => '（調査機関名・調査期間・対象範囲を明記）',
            ],
            [
                'pattern'    => '顧客満足度No.1',
                'reason'     => '調査データの出典が必要です',
                'suggestion' => '（調査機関名・調査期間・対象範囲を明記）',
            ],
        ];
    }
}
