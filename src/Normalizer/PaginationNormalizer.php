<?php 
    namespace App\Normalizer;
    use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
    use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
    use Symfony\Component\Serializer\Exception\LogicException;
    use Symfony\Component\Serializer\SerializerAwareInterface;
    use Symfony\Component\Serializer\SerializerInterface;
    use Knp\Component\Pager\Pagination\PaginationInterface;

    class PaginationNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
    {
        private const ALREADY_CALLED = 'PAGINATION_NORMALIZER_ALREADY_CALLED';

        public function normalize($object, string $format = null, array $context = []): array
        {
            if (!$object instanceof PaginationInterface) {
                throw new LogicException('The PaginationNormalizer can only normalize PaginationInterface objects.');
            }

            if (isset($context[self::ALREADY_CALLED])) {
                return [];
            }

            $context[self::ALREADY_CALLED] = true;

            return [
                'current_page' => $object->getCurrentPage(),
                'items_per_page' => $object->getItemNumberPerPage(),
                'total_items' => $object->getTotalItemCount(),
                'total_pages' => $object->getPaginationData()['pageCount'],
                'items' => $object->getItems(),
            ];
        }

        public function supportsNormalization($data, string $format = null): bool
        {
            return $data instanceof PaginationInterface;
        }

        public function setSerializer(SerializerInterface $serializer): void
        {
            $this->serializer = $serializer;
        }
    }