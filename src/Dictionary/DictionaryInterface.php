<?php

declare(strict_types=1);

namespace Yakki\Dictionary;

/**
 * NG表現辞書のインターフェース
 */
interface DictionaryInterface
{
    /**
     * 辞書のカテゴリ名を返す
     */
    public function getCategory(): string;

    /**
     * NGワードの一覧を返す
     *
     * @return array<int, array{
     *     pattern: string,
     *     reason: string,
     *     suggestion: string
     * }>
     */
    public function getEntries(): array;
}
