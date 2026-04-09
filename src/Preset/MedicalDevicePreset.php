<?php

declare(strict_types=1);

namespace Yakki\Preset;

use Yakki\Dictionary\CustomDictionary;
use Yakki\Dictionary\DictionaryInterface;

/**
 * 医療機器・美容機器業界向けプリセット
 */
class MedicalDevicePreset implements PresetInterface
{
    public function getName(): string
    {
        return '医療機器・美容機器';
    }

    public function getDictionaries(): array
    {
        $dict = new CustomDictionary('medical_device');

        $dict->addEntries([
            [
                'pattern'    => '医療レベル',
                'reason'     => '未承認機器で医療レベルを訴求することは薬機法違反です',
                'suggestion' => '本格的なケア / プロ仕様',
            ],
            [
                'pattern'    => '医療用と同等',
                'reason'     => '未承認機器で医療機器との同等性を訴求できません',
                'suggestion' => '高性能 / 本格派',
            ],
            [
                'pattern'    => '手術不要',
                'reason'     => '医療行為との比較は誤解を招きます',
                'suggestion' => '自宅で手軽にケア',
            ],
            [
                'pattern'    => '即効性',
                'reason'     => '即時の効果を保証する表現は禁止されています',
                'suggestion' => '使用後すぐに実感 / スピーディー',
            ],
            [
                'pattern'    => '副作用なし',
                'reason'     => '安全性を絶対的に保証する表現は使用できません',
                'suggestion' => '肌にやさしい設計',
            ],
            [
                'pattern'    => '安全性100%',
                'reason'     => '安全性を完全に保証する表現は使用できません',
                'suggestion' => '安全性に配慮した設計',
            ],
            [
                'pattern'    => 'FDA認証',
                'reason'     => '日本国内で未承認の場合、海外の認証を誇大に表現できません',
                'suggestion' => '（日本国内の承認状況を正確に記載してください）',
            ],
        ]);

        return [$dict];
    }
}
