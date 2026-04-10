<?php

declare(strict_types=1);

namespace MisleadingChecker\Result;

/**
 * NG表現の検出結果（1件分）
 */
class Violation
{
    /**
     * @param string $word       検出されたNG表現
     * @param string $category   カテゴリ（yakki / keihin / custom）
     * @param string $reason     NG理由の説明
     * @param string $suggestion 代替表現の提案
     * @param int    $offset     テキスト内の開始位置（バイト）
     * @param int    $length     マッチした文字列の長さ（バイト）
     */
    public function __construct(
        public readonly string $word,
        public readonly string $category,
        public readonly string $reason,
        public readonly string $suggestion,
        public readonly int $offset,
        public readonly int $length,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'word'       => $this->word,
            'category'   => $this->category,
            'reason'     => $this->reason,
            'suggestion' => $this->suggestion,
            'offset'     => $this->offset,
            'length'     => $this->length,
        ];
    }
}
