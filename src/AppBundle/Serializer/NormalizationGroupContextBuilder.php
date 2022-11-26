<?php

declare(strict_types=1);

namespace AppBundle\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use AppBundle\Entity\Recommendation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Tests\Fixtures\CustomArrayObject;

/**
 * Class NormalizationGroupContextBuilder
 *
 * @package AppBundle\Serializer
 */
class NormalizationGroupContextBuilder implements SerializerContextBuilderInterface
{
    const SUPPORTED_CLASSES = [
        Recommendation::class,
    ];
    /**
     * @var \ApiPlatform\Core\Serializer\SerializerContextBuilderInterface
     */
    private $builder;

    /**
     * NormalizationGroupContextBuilder constructor.
     *
     * @param \ApiPlatform\Core\Serializer\SerializerContextBuilderInterface $builder
     */
    public function __construct(SerializerContextBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->builder->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        $groups = $request->get('groups');

        if (!$groups) {
            return $context;
        }

        if (in_array($resourceClass, static::SUPPORTED_CLASSES) && isset($context['groups']) && count($groups)) {
            $context['groups'] = array_merge($context['groups'], $groups);
        }

        return $context;
    }
}