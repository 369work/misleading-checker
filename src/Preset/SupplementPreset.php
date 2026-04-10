<?php

declare(strict_types=1);

namespace MisleadingChecker\Preset;

use MisleadingChecker\Dictionary\CustomDictionary;
use MisleadingChecker\Dictionary\DictionaryInterface;

/**
 * サプリメント・健康食品業界向けプリセット
 */
class SupplementPreset implements PresetInterface
{
    public function getName(): string
    {
        return 'サプリメント・健康食品';
    }

    public function getDictionaries(): array
    {
        $dict = new CustomDictionary('supplement');

        $dict->addEntries([
            [
                'pattern'    => '病気が治る',
                'reason'     => '食品で疾病の治療効果を訴求することは薬機法違反です',
                'suggestion' => '健康をサポート / 毎日の健康習慣に',
            ],
            [
                'pattern'    => '血圧が下がる',
                'reason'     => '食品で具体的な身体変化を断定することは禁止されています',
                'suggestion' => '健康的な生活をサポート（機能性表示食品の届出がある場合を除く）',
            ],
            [
                'pattern'    => '血糖値が下がる',
                'reason'     => '食品で具体的な身体変化を断定することは禁止されています',
                'suggestion' => '食生活のサポートに（機能性表示食品の届出がある場合を除く）',
            ],
            [
                'pattern'    => '免疫力アップ',
                'reason'     => '食品で免疫機能への効果を訴求することは認められていません',
                'suggestion' => '毎日の健康維持に / 体調管理をサポート',
            ],
            [
                'pattern'    => '免疫力を高める',
                'reason'     => '食品で免疫機能への効果を訴求することは認められていません',
                'suggestion' => '健やかな毎日をサポート',
            ],
            [
                'pattern'    => 'がんに効く',
                'reason'     => '食品で疾病の治療効果を訴求することは薬機法違反です',
                'suggestion' => '（この表現は使用できません）',
            ],
            [
                'pattern'    => 'がん予防',
                'reason'     => '食品で疾病の予防効果を訴求することは薬機法違反です',
                'suggestion' => '（この表現は使用できません）',
            ],
            [
                'pattern'    => '脂肪燃焼',
                'reason'     => '食品で脂肪燃焼効果を断定的に訴求することは禁止されています',
                'suggestion' => 'ダイエットのサポートに（機能性表示食品の届出がある場合を除く）',
            ],
            [
                'pattern'    => '体重が減る',
                'reason'     => '食品で体重減少を保証する表現は禁止されています',
                'suggestion' => '健康的な食生活をサポート',
            ],
            [
                'pattern'    => '飲むだけで',
                'reason'     => '手軽さを過度に強調し効果を暗示する表現は問題があります',
                'suggestion' => '毎日の習慣として / 食事と一緒に',
            ],
            [
                'pattern'    => '食べるだけで',
                'reason'     => '手軽さを過度に強調し効果を暗示する表現は問題があります',
                'suggestion' => '食生活に取り入れて',
            ],
        ]);

        return [$dict];
    }
}
