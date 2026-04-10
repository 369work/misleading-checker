<?php

declare(strict_types=1);

namespace MisleadingChecker\Tests;

use PHPUnit\Framework\TestCase;
use MisleadingChecker\Dictionary\CustomDictionary;
use MisleadingChecker\Preset\CosmeticsPreset;
use MisleadingChecker\Preset\SupplementPreset;
use MisleadingChecker\MisleadingChecker;

class MisleadingCheckerTest extends TestCase
{
    private MisleadingChecker $checker;

    protected function setUp(): void
    {
        $this->checker = new MisleadingChecker();
    }

    public function testDetectsYakkihouViolation(): void
    {
        $result = $this->checker->check('この商品は必ず治る');
        $this->assertTrue($result->hasViolations());

        $words = array_map(fn($v) => $v->word, $result->getViolations());
        $this->assertContains('必ず治る', $words);
    }

    public function testDetectsKeihinViolation(): void
    {
        $result = $this->checker->check('今だけ半額キャンペーン！絶対おすすめ');
        $this->assertTrue($result->hasViolations());

        $words = array_map(fn($v) => $v->word, $result->getViolations());
        $this->assertContains('今だけ', $words);
        $this->assertContains('半額', $words);
        $this->assertContains('絶対', $words);
    }

    public function testCleanTextPassesCheck(): void
    {
        $result = $this->checker->check('お肌にうるおいを与え、健やかに保ちます。');
        $this->assertFalse($result->hasViolations());
        $this->assertSame(0, $result->count());
    }

    public function testGroupByCategory(): void
    {
        $result = $this->checker->check('必ず治る！今だけ限定');
        $grouped = $result->groupByCategory();

        $this->assertArrayHasKey('yakki', $grouped);
        $this->assertArrayHasKey('keihin', $grouped);
    }

    public function testCustomDictionary(): void
    {
        $custom = new CustomDictionary('social');
        $custom->addEntry('バズる', 'SNS上の誇大表現', '話題になる');

        $checker = new MisleadingChecker(['load_defaults' => false]);
        $checker->addDictionary($custom);

        $result = $checker->check('この投稿はバズること間違いなし');
        $this->assertTrue($result->hasViolations());
        $this->assertSame('social', $result->getViolations()[0]->category);
    }

    public function testCosmeticsPreset(): void
    {
        $this->checker->applyPreset(new CosmeticsPreset());
        $result = $this->checker->check('美白効果でシミが薄くなる化粧水');
        $this->assertTrue($result->hasViolations());

        $words = array_map(fn($v) => $v->word, $result->getViolations());
        $this->assertContains('美白', $words);
        $this->assertContains('シミが薄くなる', $words);
    }

    public function testSupplementPreset(): void
    {
        $this->checker->applyPreset(new SupplementPreset());
        $result = $this->checker->check('飲むだけで免疫力アップ');
        $this->assertTrue($result->hasViolations());

        $words = array_map(fn($v) => $v->word, $result->getViolations());
        $this->assertContains('飲むだけで', $words);
        $this->assertContains('免疫力アップ', $words);
    }

    public function testToArrayExport(): void
    {
        $result = $this->checker->check('絶対に効きます');
        $array = $result->toArray();

        $this->assertTrue($array['has_violations']);
        $this->assertIsArray($array['violations']);
        $this->assertArrayHasKey('word', $array['violations'][0]);
        $this->assertArrayHasKey('reason', $array['violations'][0]);
        $this->assertArrayHasKey('suggestion', $array['violations'][0]);
    }

    public function testToJSONExport(): void
    {
        $result = $this->checker->check('絶対に効きます');
        $json = $result->toJSON();

        $decoded = json_decode($json, true);
        $this->assertNotNull($decoded);
        $this->assertTrue($decoded['has_violations']);
    }

    public function testEmptyTextThrowsException(): void
    {
        $this->expectException(\MisleadingChecker\Exception\InvalidArgumentException::class);
        $this->checker->check('');
    }

    public function testMultipleOccurrences(): void
    {
        $result = $this->checker->check('絶対に良い、絶対におすすめ');
        $count = 0;
        foreach ($result->getViolations() as $v) {
            if ($v->word === '絶対') {
                $count++;
            }
        }
        $this->assertSame(2, $count);
    }

    public function testGetTotalEntries(): void
    {
        $this->assertGreaterThan(0, $this->checker->getTotalEntries());
    }

    public function testCustomDictionaryFromJSON(): void
    {
        $json = json_encode([
            ['pattern' => 'テスト表現', 'reason' => 'テスト理由', 'suggestion' => 'テスト提案'],
        ], JSON_UNESCAPED_UNICODE);

        $dict = CustomDictionary::fromJSON($json, 'test');
        $this->assertSame('test', $dict->getCategory());
        $this->assertCount(1, $dict->getEntries());
    }
}
