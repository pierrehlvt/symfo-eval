<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{
    private array $encoders;

    public function __construct()
    {
        $this->encoders = [new XmlEncoder(), new JsonEncoder()];
    }

    public function SimpleSerializer($inputData, $outFormatData): string
    {
        $encoders = $this->encoders;

        $normalizers = [new ObjectNormalizer()];

        $serializer= new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($inputData, $outFormatData);

        return $jsonContent;
    }

}