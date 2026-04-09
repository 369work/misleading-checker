<?php

declare(strict_types=1);

namespace Yakki\Preset;

use Yakki\Dictionary\DictionaryInterface;

/**
 * 業種別プリセットのインターフェース
 */
interface PresetInterface
{
    /**
     * プリセット名
     */
    public function getName(): string;

    /**
     * このプリセットに含まれる追加辞書を返す
     *
     * @return DictionaryInterface[]
     */
    public function getDictionaries(): array;
}
