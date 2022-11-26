<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace AppBundle\Serializer;

use ApiPlatform\Core\Api\OperationType;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Metadata\Property\PropertyMetadata;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;

/**
 * Generic item normalizer.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
final class ItemNormalizer extends AbstractItemNormalizer
{
    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        // Avoid issues with proxies if we populated the object
        if (isset($data['id']) && !isset($context[self::OBJECT_TO_POPULATE])) {
            if (isset($context['api_allow_update']) && true !== $context['api_allow_update']) {
                throw new InvalidArgumentException('Update is not allowed for this operation.');
            }

            $this->updateObjectToPopulate($data, $context);
        }

        return parent::denormalize($data, $class, $format, $context);
    }

    private function updateObjectToPopulate(array $data, array &$context)
    {
        try {
            $context[self::OBJECT_TO_POPULATE] = $this->iriConverter->getItemFromIri((string) $data['id'], $context + ['fetch_data' => true]);
        } catch (InvalidArgumentException $e) {
            $identifier = null;
            foreach ($this->propertyNameCollectionFactory->create($context['resource_class'], $context) as $propertyName) {
                if (true === $this->propertyMetadataFactory->create($context['resource_class'], $propertyName)->isIdentifier()) {
                    $identifier = $propertyName;
                    break;
                }
            }

            if (null === $identifier) {
                throw $e;
            }

            $context[self::OBJECT_TO_POPULATE] = $this->iriConverter->getItemFromIri(sprintf('%s/%s', $this->iriConverter->getIriFromResourceClass($context['resource_class']), $data[$identifier]), $context + ['fetch_data' => true]);
        }
    }

    /**
     * Normalizes a relation as an object if is a Link or as an URI.
     *
     * @param mixed       $relatedObject
     * @param string|null $format
     *
     * @return string|array
     */
    protected function normalizeRelation(PropertyMetadata $propertyMetadata, $relatedObject, string $resourceClass, string $format = null, array $context)
    {
        // On a subresource, we know the value of the identifiers.
        // If attributeValue is null, meaning that it hasn't been returned by the DataProvider, get the item Iri
        if (null === $relatedObject && isset($context['operation_type']) && OperationType::SUBRESOURCE === $context['operation_type'] && isset($context['subresource_resources'][$resourceClass])) {
            return $this->iriConverter->getItemIriFromResourceClass($resourceClass, $context['subresource_resources'][$resourceClass]);
        }

        if ((null === $relatedObject || $propertyMetadata->isReadableLink() || !empty($context['attributes']))
            && $propertyMetadata->getAttribute('isReadableLink') !== false
        ) {
            if (null === $relatedObject) {
                unset($context['resource_class']);
            } else {
                $context['resource_class'] = $resourceClass;
            }

            return $this->serializer->normalize($relatedObject, $format, $context);
        }

        $iri = $this->iriConverter->getIriFromItem($relatedObject);
        if (isset($context['resources'])) {
            $context['resources'][$iri] = $iri;
        }

        return $iri;
    }
}
