<?php

namespace Sweetchuck\Robo\Stringy\Task;

use Robo\Result;
use Robo\Task\BaseTask as RoboBaseTask;
use Robo\TaskInfo;
use Stringy\Stringy;

/**
 * @method $this callAppend(string $stringAppend)
 * @method $this callAt(int $index)
 * @method $this callBetween(string $start, string $end, int $offset = 0)
 * @method $this callCamelize()
 * @method $this callChars()
 * @method $this callCollapseWhitespace()
 * @method $this callContains(string $needle, bool $caseSensitive = true)
 * @method $this callContainsAll(string[] $needle, bool $caseSensitive = true)
 * @method $this callContainsAny(string[] $needle, bool $caseSensitive = true)
 * @method $this callCount()
 * @method $this callCountSubstr(string $substring, bool $caseSensitive = true)
 * @method $this callDasherize()
 * @method $this callDelimit(string $delimiter)
 * @method $this callEndsWith(string $substring, bool $caseSensitive = true)
 * @method $this callEndsWithAny(string[] $substrings, bool $caseSensitive = true)
 * @method $this callEnsureLeft(string $substring)
 * @method $this callEnsureRight(string $substring)
 * @method $this callFirst(int $n)
 * @method $this callHasLowerCase()
 * @method $this callHasUpperCase()
 * @method $this callHtmlDecode(int $flags = ENT_COMPAT)
 * @method $this callHtmlEncode(int $flags = ENT_COMPAT)
 * @method $this callHumanize()
 * @method $this callIndexOf(string $needle, int $offset = 0)
 * @method $this callIndexOfLast(string $needle, int $offset = 0)
 * @method $this callInsert(string $substring, int $index = 0)
 * @method $this callIsAlpha()
 * @method $this callIsAlphanumeric()
 * @method $this callIsBase64()
 * @method $this callIsBlank()
 * @method $this callIsHexadecimal()
 * @method $this callIsJson()
 * @method $this callIsLowerCase()
 * @method $this callIsSerialized()
 * @method $this callIsUpperCase()
 * @method $this callLast()
 * @method $this callLength()
 * @method $this callLines()
 * @method $this callLongestCommonPrefix(string $otherStr)
 * @method $this callLongestCommonSuffix(string $otherStr)
 * @method $this callLongestCommonSubstring(string $otherStr)
 * @method $this callLowerCaseFirst()
 * @method $this callPad(int $length, string $padStr = ' ', string $padType = 'right')
 * @method $this callPadBoth(int $length, string $padStr = ' ')
 * @method $this callPadLeft(int $length, string $padStr = ' ')
 * @method $this callPadRight(int $length, string $padStr = ' ')
 * @method $this callPrepend(string $string)
 * @method $this callRegexReplace(string $pattern, string $replacement, string $options = 'msr')
 * @method $this callRemoveLeft(string $substring)
 * @method $this callRemoveRight(string $substring)
 * @method $this callRepeat(int $multiplier)
 * @method $this callReplace(string $search, string $replacement)
 * @method $this callReverse()
 * @method $this callSafeTruncate(int $length, string $substring = '')
 * @method $this callShuffle()
 * @method $this callSlugify(string $replacement = '-')
 * @method $this callSlice(int $start, int $end = null)
 * @method $this callSplit(string $pattern, int $limit = null)
 * @method $this callStartsWith(string $substring, bool $caseSensitive = true)
 * @method $this callStartsWithAny(string[] $substrings, bool $caseSensitive = true)
 * @method $this callStripWhitespace()
 * @method $this callSubstr(int $start, int $length = null)
 * @method $this callSurround(string $substring)
 * @method $this callSwapCase()
 * @method $this callTidy()
 * @method $this callTitleize()
 * @method $this callToAscii(string $language = 'en', bool $removeUnsupported = true)
 * @method $this callToBoolean()
 * @method $this callToLowerCase()
 * @method $this callToSpaces(int $tabLength = 4)
 * @method $this callToTabs(int $tabLength = 4)
 * @method $this callToTitleCase()
 * @method $this callToUpperCase()
 * @method $this callTrim(string $chars = null)
 * @method $this callTrimLeft(string $chars = null)
 * @method $this callTrimRight(string $chars = null)
 * @method $this callTruncate(int $length, string $substring = '')
 * @method $this callUnderscored()
 * @method $this callUpperCamelize()
 * @method $this callUpperCaseFirst()
 */
class StringyTask extends RoboBaseTask
{
    /**
     * @var string
     */
    protected $taskName = 'Stringy';

    /**
     * @var array
     */
    protected $queue = [];

    /**
     * @var array
     */
    protected $assets = [];

    // region Options

    // region assetNamePrefix.
    /**
     * @var string
     */
    protected $assetNamePrefix = '';

    public function getAssetNamePrefix(): string
    {
        return $this->assetNamePrefix;
    }

    /**
     * @return $this
     */
    public function setAssetNamePrefix(string $value)
    {
        $this->assetNamePrefix = $value;

        return $this;
    }
    // endregion

    // region string
    /**
     * @var string
     */
    protected $string = '';

    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @return $this
     */
    public function setString(string $value)
    {
        $this->string = $value;

        return $this;
    }
    // endregion

    // endregion

    public function __call($name, $arguments)
    {
        if (preg_match('/^call[A-Z]/', $name)) {
            $method = (new Stringy($name))
                ->substr(4)
                ->lowerCaseFirst();

            return $this->call((string) $method, $arguments);
        }

        throw new \BadMethodCallException('@todo');
    }

    /**
     * @return $this
     */
    public function setOptions(array $options)
    {
        if (array_key_exists('string', $options)) {
            $this->setString($options['string']);
        }

        if (array_key_exists('queue', $options)) {
            foreach ($options['queue'] as $item) {
                $this->addToQueue($item);
            }
        }

        if (array_key_exists('assetNamePrefix', $options)) {
            $this->setAssetNamePrefix($options['assetNamePrefix']);
        }

        return $this;
    }

    /**
     * @param string|array $item
     */
    public function addToQueue($item)
    {
        if (is_string($item)) {
            $item = ['method' => $item];
        }

        $item['index'] = count($this->queue);
        $this->queue[] = $item + [
            'args' => [],
            'weight' => 0,
            'assetName' => null,
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function call(string $method, array $args = [], ?string $assetName = null)
    {
        $item = [
            'method' => $method,
            'args' => $args,
            'assetName' => $assetName,
        ];

        return $this->addToQueue($item);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this
            ->runHeader()
            ->runDoIt()
            ->runReturn();
    }

    /**
     * @return $this
     */
    protected function runHeader()
    {
        $this->printTaskDebug('{stringyMethodsComma}');

        return $this;
    }

    /**
     * @return $this
     */
    protected function runDoIt()
    {
        $stringy = new Stringy($this->getString());
        foreach ($this->queue as $item) {
            if (!($stringy instanceof Stringy)) {
                throw new \Exception('@todo');
            }

            if (!is_callable([$stringy, $item['method']])) {
                throw  new \Exception("Method '{$item['method']}' is not callable");
            }

            $stringy = $stringy->{$item['method']}(...$item['args']);

            if (isset($item['assetName'])) {
                $this->addToAssets($item['assetName'], $stringy);
            }
        }

        $this->addToAssets('stringy.result', $stringy);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addToAssets(string $name, $stringy)
    {
        $this->assets[$name] = $stringy instanceof Stringy ? (string) $stringy : $stringy;

        return $this;
    }

    protected function runReturn(): Result
    {
        return new Result(
            $this,
            0,
            '',
            $this->getAssetsWithPrefixedNames()
        );
    }

    protected function getAssetsWithPrefixedNames(): array
    {
        $prefix = $this->getAssetNamePrefix();
        if (!$prefix) {
            return $this->assets;
        }

        $assets = [];
        foreach ($this->assets as $key => $value) {
            $assets["{$prefix}{$key}"] = $value;
        }

        return $assets;
    }

    public function getTaskName(): string
    {
        return $this->taskName ?: TaskInfo::formatTaskName($this);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTaskContext($context = null)
    {
        if (!$context) {
            $context = [];
        }

        if (empty($context['name'])) {
            $context['name'] = $this->getTaskName();
        }

        if (empty($context['stringyMethodsComma'])) {
            $context['stringyMethodsComma'] = implode(', ', $this->getMethods());
        }

        return parent::getTaskContext($context);
    }

    /**
     * @return string[]
     */
    protected function getMethods(): array
    {
        $methods = [];
        foreach ($this->queue as $item) {
            $methods[] = $item['method'];
        }

        return $methods;
    }
}
