<?php

declare(strict_types=1);

namespace Yakki\Preset;

use Yakki\Dictionary\CustomDictionary;
use Yakki\Dictionary\DictionaryInterface;

/**
 * 化粧品業界向けプリセット
 */
class CosmeticsPreset implements PresetInterface
{
    public function getName(): string
    {
        return '化粧品';
    }

    public function getDictionaries(): array
    {
        $dict = new CustomDictionary('cosmetics');

        $dict->addEntries([
            [
                'pattern'    => '美白',
                'reason'     => '「美白」は「メラニンの生成を抑え、シミ・そばかすを防ぐ」の範囲でのみ使用可能です',
                'suggestion' => 'メラニンの生成を抑え、シミ・そばかすを防ぐ',
            ],
            [
                'pattern'    => '肌が白くなる',
                'reason'     => '肌の色を変える効果の訴求は薬機法違反です',
                'suggestion' => '透明感のある肌へ / 明るい印象の肌へ',
            ],
            [
                'pattern'    => 'ターンオーバー',
                'reason'     => '化粧品の効能として肌のターンオーバー促進は認められていません',
                'suggestion' => '肌を健やかに保つ / キメを整える',
            ],
            [
                'pattern'    => 'コラーゲン生成',
                'reason'     => '化粧品でコラーゲン生成を訴求することは認められていません',
                'suggestion' => 'ハリを与える / うるおいで満たす',
            ],
            [
                'pattern'    => 'セルライト',
                'reason'     => '化粧品でセルライトへの効果を訴求することは認められていません',
                'suggestion' => '肌を引き締める / ボディケア',
            ],
            [
                'pattern'    => 'デトックス',
                'reason'     => '化粧品でデトックス効果を訴求することは認められていません',
                'suggestion' => 'すっきり / リフレッシュ',
            ],
            [
                'pattern'    => 'シミが薄くなる',
                'reason'     => '化粧品でシミを薄くする効果は訴求できません',
                'suggestion' => 'シミ・そばかすを防ぐ',
            ],
            [
                'pattern'    => '毛穴が消える',
                'reason'     => '肌構造の変化を示す表現は使用できません',
                'suggestion' => '毛穴を目立たなくする / キメを整える',
            ],
            [
                'pattern'    => 'ニキビが治る',
                'reason'     => '化粧品でニキビの治療効果は訴求できません',
                'suggestion' => '肌を清潔に保つ / 肌荒れを防ぐ',
            ],
            [
                'pattern'    => '肌年齢',
                'reason'     => '年齢に関する効果の訴求は制限されています',
                'suggestion' => '年齢に応じたスキンケア',
            ],

            // === 効果の断定表現 ===
            [
                'pattern'    => '効果があります',
                'reason'     => '化粧品で効果を断定する表現は薬機法で認められていません。化粧品の効能は56項目に限定されています',
                'suggestion' => '〇〇が期待できます / 〇〇をサポート',
            ],
            [
                'pattern'    => '効果的です',
                'reason'     => '化粧品で効果を断定する表現は認められていません',
                'suggestion' => 'おすすめです / お役立ていただけます',
            ],
            [
                'pattern'    => '効果抜群',
                'reason'     => '化粧品で効果を断定・誇張する表現は認められていません',
                'suggestion' => '多くの方にご好評いただいています',
            ],
            [
                'pattern'    => '効果を実感',
                'reason'     => '化粧品で効果の実感を保証する表現は認められていません',
                'suggestion' => '使い心地をお試しください / うるおいを感じる',
            ],
            [
                'pattern'    => '効能',
                'reason'     => '「効能」は医薬品・医薬部外品に使われる用語です。化粧品には使用できません',
                'suggestion' => '特長 / おすすめポイント',
            ],
            [
                'pattern'    => '薬用',
                'reason'     => '「薬用」は医薬部外品の承認を受けた製品のみ使用できます',
                'suggestion' => '（医薬部外品の承認がある場合のみ使用可）',
            ],
        ]);

        return [$dict];
    }
}
