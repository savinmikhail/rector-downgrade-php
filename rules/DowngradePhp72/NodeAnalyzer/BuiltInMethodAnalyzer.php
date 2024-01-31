<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\NodeAnalyzer;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeNameResolver\NodeNameResolver;

final class BuiltInMethodAnalyzer
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    public function isImplementsBuiltInInterface(ClassReflection $classReflection, ClassMethod $classMethod): bool
    {
        if (! $classReflection->isClass()) {
            return false;
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);

        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            if (! $interfaceReflection->isBuiltin()) {
                continue;
            }

            if (! $interfaceReflection->hasMethod($methodName)) {
                continue;
            }

            return true;
        }

        return false;
    }
}
