<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @OA\Info(title="GrudlannApp",
 *     version="1")
 */
abstract class AbstractBaseController extends AbstractController
{
    protected $_validatorService;

    protected $_serializer;

    public function __construct(ValidatorService $validatorService)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $this->_serializer = new Serializer([$normalizer]);
        $this->_validatorService = $validatorService;
    }
}