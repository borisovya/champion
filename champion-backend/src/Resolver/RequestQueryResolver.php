<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Attribute\RequestQuery;
use App\Exception\RequestBodyConvertException;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class RequestQueryResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getAttributesOfType(RequestQuery::class, ArgumentMetadata::IS_INSTANCEOF)) {
            return [];
        }

        try {
            $serialized = $this->serializer->serialize(
                $request->query->all(),
                JsonEncoder::FORMAT
            );

            $model = '[]' !== $serialized ? $this->serializer->deserialize(
                $serialized,
                $argument->getType(),
                JsonEncoder::FORMAT
            ) : new ($argument->getType());
        } catch (\Throwable $throwable) {
            throw new RequestBodyConvertException($throwable);
        }

        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return [$model];
    }
}
