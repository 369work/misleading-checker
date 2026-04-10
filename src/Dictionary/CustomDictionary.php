<?php

declare(strict_types=1);

namespace MisleadingChecker\Dictionary;

use MisleadingChecker\Exception\InvalidArgumentException;

/**
 * カスタムNG辞書
 *
 * ユーザーが独自のNGワードを追加できる辞書。
 */
class CustomDictionary implements DictionaryInterface
{
    private string $category;

    /** @var array<int, array{pattern: string, reason: string, suggestion: string}> */
    private array $entries = [];

    public function __construct(string $category = 'custom')
    {
        $this->category = $category;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * NGワードを追加
     */
    public function addEntry(string $pattern, string $reason = '', string $suggestion = ''): self
    {
        if ($pattern === '') {
            throw new InvalidArgumentException('パターンは空にできません');
        }

        $this->entries[] = [
            'pattern'    => $pattern,
            'reason'     => $reason,
            'suggestion' => $suggestion,
        ];

        return $this;
    }

    /**
     * 複数のNGワードを一括追加
     *
     * @param array<int, array{pattern: string, reason?: string, suggestion?: string}> $entries
     */
    public function addEntries(array $entries): self
    {
        foreach ($entries as $entry) {
            $this->addEntry(
                $entry['pattern'],
                $entry['reason'] ?? '',
                $entry['suggestion'] ?? '',
            );
        }

        return $this;
    }

    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * JSON文字列から辞書を読み込む
     */
    public static function fromJSON(string $json, string $category = 'custom'): self
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new InvalidArgumentException('JSONは配列形式である必要があります');
        }

        $dict = new self($category);
        $dict->addEntries($data);
        return $dict;
    }
}
