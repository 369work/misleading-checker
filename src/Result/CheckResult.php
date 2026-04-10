<?php

declare(strict_types=1);

namespace MisleadingChecker\Result;

/**
 * チェック結果全体を保持するクラス
 */
class CheckResult
{
    /** @var Violation[] */
    private array $violations = [];

    private string $originalText;

    public function __construct(string $originalText)
    {
        $this->originalText = $originalText;
    }

    public function addViolation(Violation $violation): void
    {
        $this->violations[] = $violation;
    }

    /**
     * @return Violation[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * NG表現が見つかったかどうか
     */
    public function hasViolations(): bool
    {
        return count($this->violations) > 0;
    }

    /**
     * 検出件数
     */
    public function count(): int
    {
        return count($this->violations);
    }

    /**
     * カテゴリ別に集計
     *
     * @return array<string, Violation[]>
     */
    public function groupByCategory(): array
    {
        $grouped = [];
        foreach ($this->violations as $violation) {
            $grouped[$violation->category][] = $violation;
        }
        return $grouped;
    }

    /**
     * 元テキストを取得
     */
    public function getOriginalText(): string
    {
        return $this->originalText;
    }

    /**
     * 配列にエクスポート
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'has_violations' => $this->hasViolations(),
            'count'          => $this->count(),
            'violations'     => array_map(
                fn(Violation $v) => $v->toArray(),
                $this->violations
            ),
        ];
    }

    /**
     * JSONにエクスポート
     */
    public function toJSON(int $flags = 0): string
    {
        return json_encode(
            $this->toArray(),
            $flags | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        );
    }
}
