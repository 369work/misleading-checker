<?php

declare(strict_types=1);

namespace Yakki;

use Yakki\Dictionary\DictionaryInterface;
use Yakki\Dictionary\KeihinDictionary;
use Yakki\Dictionary\YakkihouDictionary;
use Yakki\Exception\InvalidArgumentException;
use Yakki\Preset\PresetInterface;
use Yakki\Result\CheckResult;
use Yakki\Result\Violation;

/**
 * 薬機法・景品表示法チェッカー
 *
 * テキストに含まれるNG表現を検出し、代替表現を提案します。
 *
 * @example
 * ```php
 * $checker = new YakkiChecker();
 * $result = $checker->check('この商品は必ず治ります！今だけ半額！');
 *
 * if ($result->hasViolations()) {
 *     foreach ($result->getViolations() as $v) {
 *         echo "{$v->word}: {$v->reason}\n";
 *     }
 * }
 * ```
 */
class YakkiChecker
{
    /** @var DictionaryInterface[] */
    private array $dictionaries = [];

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        // デフォルトで薬機法・景品表示法の辞書を読み込む
        $loadDefaults = $options['load_defaults'] ?? true;

        if ($loadDefaults) {
            $this->addDictionary(new YakkihouDictionary());
            $this->addDictionary(new KeihinDictionary());
        }
    }

    /**
     * 辞書を追加
     */
    public function addDictionary(DictionaryInterface $dictionary): self
    {
        $this->dictionaries[] = $dictionary;
        return $this;
    }

    /**
     * プリセットを適用
     */
    public function applyPreset(PresetInterface $preset): self
    {
        foreach ($preset->getDictionaries() as $dictionary) {
            $this->addDictionary($dictionary);
        }
        return $this;
    }

    /**
     * テキストをチェック
     */
    public function check(string $text): CheckResult
    {
        if ($text === '') {
            throw new InvalidArgumentException('チェック対象のテキストが空です');
        }

        $result = new CheckResult($text);

        foreach ($this->dictionaries as $dictionary) {
            $this->scanWithDictionary($text, $dictionary, $result);
        }

        return $result;
    }

    /**
     * 1つの辞書でテキストをスキャン
     */
    private function scanWithDictionary(
        string $text,
        DictionaryInterface $dictionary,
        CheckResult $result
    ): void {
        $category = $dictionary->getCategory();

        foreach ($dictionary->getEntries() as $entry) {
            $pattern = $entry['pattern'];
            $offset  = 0;

            // テキスト内の全出現箇所を検出
            while (($pos = mb_strpos($text, $pattern, $offset)) !== false) {
                $byteOffset = strlen(mb_substr($text, 0, $pos));
                $byteLength = strlen($pattern);

                $result->addViolation(new Violation(
                    word: $pattern,
                    category: $category,
                    reason: $entry['reason'],
                    suggestion: $entry['suggestion'],
                    offset: $byteOffset,
                    length: $byteLength,
                ));

                $offset = $pos + mb_strlen($pattern);
            }
        }
    }

    /**
     * 登録済み辞書の一覧を取得
     *
     * @return DictionaryInterface[]
     */
    public function getDictionaries(): array
    {
        return $this->dictionaries;
    }

    /**
     * 登録済みのNG表現の総数を取得
     */
    public function getTotalEntries(): int
    {
        $count = 0;
        foreach ($this->dictionaries as $dictionary) {
            $count += count($dictionary->getEntries());
        }
        return $count;
    }
}
