<?php

namespace opentech\PhpCsFixer\Config;

/**
 * Copyright (c) 2019-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/php-cs-fixer-config
 */

namespace opentech\PhpCsFixer\Config;

use PhpCsFixer\Config;

final class Factory
{
    /**
     * Creates a configuration based on a rule set.
     *
     * @param RuleSet                   $ruleSet
     * @param array<string, array|bool> $overrideRules
     *
     * @return Config
     */
    public static function fromRuleSet(RuleSet $ruleSet, array $overrideRules = []): Config
    {
        if (\PHP_VERSION_ID < $ruleSet->targetPhpVersion()) {
            throw new \RuntimeException(
                \sprintf(
                    'Current PHP version "%s" is less than targeted PHP version "%s".',
                    \PHP_VERSION_ID,
                    $ruleSet->targetPhpVersion(),
                )
            );
        }

        $config = new Config($ruleSet->name());

        $config->setRiskyAllowed(true);
        $config->setRules(
            \array_merge(
                $ruleSet->rules(),
                $overrideRules,
            )
        );

        return $config;
    }
}