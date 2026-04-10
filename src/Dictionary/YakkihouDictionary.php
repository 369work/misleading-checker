<?php

declare(strict_types=1);

namespace MisleadingChecker\Dictionary;

/**
 * 薬機法NGワード辞書
 *
 * 医薬品医療機器等法（薬機法）に基づく広告規制で
 * 使用が制限される表現を収録。
 */
class YakkihouDictionary implements DictionaryInterface
{
    public function getCategory(): string
    {
        return 'yakki';
    }

    public function getEntries(): array
    {
        return [
            // === 効果・効能の断定表現 ===
            [
                'pattern'    => '必ず治る',
                'reason'     => '効果を断定する表現は薬機法で禁止されています',
                'suggestion' => '個人差があります / 効果には個人差があります',
            ],
            [
                'pattern'    => '絶対に治る',
                'reason'     => '効果を断定する表現は薬機法で禁止されています',
                'suggestion' => '個人差があります',
            ],
            [
                'pattern'    => '100%効く',
                'reason'     => '効果の確率を保証する表現は禁止されています',
                'suggestion' => '多くの方にご満足いただいています',
            ],
            [
                'pattern'    => '確実に効果',
                'reason'     => '効果を断定する表現は薬機法で禁止されています',
                'suggestion' => '効果が期待できます',
            ],
            [
                'pattern'    => '万能',
                'reason'     => '誇大表現にあたります',
                'suggestion' => '幅広くお使いいただけます',
            ],
            [
                'pattern'    => '奇跡',
                'reason'     => '誇大表現にあたります',
                'suggestion' => '嬉しい変化 / 実感',
            ],

            // === 医薬品的な効能表現 ===
            [
                'pattern'    => '治療',
                'reason'     => '医薬品でない製品に「治療」は使用できません',
                'suggestion' => 'ケア / お手入れ / サポート',
            ],
            [
                'pattern'    => '治す',
                'reason'     => '医薬品でない製品に「治す」は使用できません',
                'suggestion' => '整える / サポートする',
            ],
            [
                'pattern'    => '治り',
                'reason'     => '医薬品でない製品に使用できません',
                'suggestion' => '改善が期待 / すっきり',
            ],
            [
                'pattern'    => '完治',
                'reason'     => '医薬品でない製品に使用できません',
                'suggestion' => '健やかな状態へ',
            ],
            [
                'pattern'    => '処方',
                'reason'     => '医薬品以外で「処方」は使用できません',
                'suggestion' => '配合 / ブレンド',
            ],
            [
                'pattern'    => '診断',
                'reason'     => '医療行為を示唆する表現は使用できません',
                'suggestion' => 'チェック / 確認',
            ],
            [
                'pattern'    => '投薬',
                'reason'     => '医療行為を示唆する表現は使用できません',
                'suggestion' => '摂取 / 使用',
            ],

            // === 身体変化の断定 ===
            [
                'pattern'    => '痩せる',
                'reason'     => '身体変化を断定する表現は禁止されています',
                'suggestion' => 'スッキリ / 健康的なスタイルを目指す',
            ],
            [
                'pattern'    => '痩せます',
                'reason'     => '身体変化を断定する表現は禁止されています',
                'suggestion' => 'スッキリを目指す方へ',
            ],
            [
                'pattern'    => 'シミが消える',
                'reason'     => '身体変化を断定する表現は禁止されています',
                'suggestion' => 'シミにアプローチ / シミ対策に',
            ],
            [
                'pattern'    => 'シワが消える',
                'reason'     => '身体変化を断定する表現は禁止されています',
                'suggestion' => 'ハリを与える / エイジングケア',
            ],
            [
                'pattern'    => 'アンチエイジング',
                'reason'     => '薬機法改正により「アンチエイジング」は使用が制限されています',
                'suggestion' => 'エイジングケア / 年齢に応じたケア',
            ],
            [
                'pattern'    => '若返り',
                'reason'     => '身体変化を断定する表現は禁止されています',
                'suggestion' => '年齢に応じたケア / ハリ・ツヤ',
            ],
            [
                'pattern'    => '若返る',
                'reason'     => '身体変化を断定する表現は禁止されています',
                'suggestion' => 'いきいきとした印象へ',
            ],

            // === 最大級・比較表現 ===
            [
                'pattern'    => '日本一',
                'reason'     => '最大級表現は根拠がなければ使用できません',
                'suggestion' => '（客観的データがある場合のみ使用可）',
            ],
            [
                'pattern'    => '世界初',
                'reason'     => '最大級表現は客観的根拠が必要です',
                'suggestion' => '（客観的データがある場合のみ使用可）',
            ],
            [
                'pattern'    => '業界No.1',
                'reason'     => '最大級表現は客観的根拠が必要です',
                'suggestion' => '（調査データの出典を明記してください）',
            ],
            [
                'pattern'    => 'ナンバーワン',
                'reason'     => '最大級表現は客観的根拠が必要です',
                'suggestion' => '（調査データの出典を明記してください）',
            ],
            [
                'pattern'    => '最高級',
                'reason'     => '最大級表現は根拠がなければ使用できません',
                'suggestion' => '高品質 / こだわりの',
            ],

            // === 医師・専門家の権威利用 ===
            [
                'pattern'    => '医師が推薦',
                'reason'     => '医師の推薦を暗示する表現は制限されています',
                'suggestion' => '専門家の意見を参考に開発',
            ],
            [
                'pattern'    => '医師推奨',
                'reason'     => '医師の推薦を暗示する表現は制限されています',
                'suggestion' => '専門家監修',
            ],
            [
                'pattern'    => '医学的に証明',
                'reason'     => '医学的根拠を示唆する表現は制限されています',
                'suggestion' => '研究データあり（出典明記が必要）',
            ],
            [
                'pattern'    => '臨床試験済み',
                'reason'     => '医薬品以外での使用は誤解を招きます',
                'suggestion' => 'テスト済み / 品質検査済み',
            ],
        ];
    }
}
