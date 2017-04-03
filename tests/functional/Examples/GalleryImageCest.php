<?php
declare(strict_types = 1);

use Cerberus\CerberusService;
use Cerberus\PDP\{
    Policy\Content, Utility\ArrayProperties
};
use Cerberus\PEP\{
    Action\Action, ObjectMapper, PepRequest, Subject
};
use Cerberus\Core\Enums\{
    AttributeIdentifier, ResourceIdentifier
};

class GalleryImageCest
{
    protected $gallery1;
    protected $gallery2;
    protected $gallery3;
    protected $image1;
    protected $image2;
    protected $image3;
    /** @var  CerberusService */
    protected $service;

    public function _before(FunctionalTester $I)
    {
        $this->service = new CerberusService($this->getDefaultProperties());
        // create gallery
        $this->gallery1 = new Gallery(1);
        $this->gallery2 = new Gallery(2);
        $this->gallery3 = new Gallery(3);
        $this->image1 = new Image(1);
        $this->gallery1->addImage($this->image1);
        $this->gallery2->addImage($this->image1);
        $this->image2 = new Image(2);
        $this->gallery1->addImage($this->image2);
        $this->image3 = new Image(3);
        $this->gallery3->addImage($this->image3);
    }

    /**
     * @skip
     */
    public function testAccessImageThroughGallery(FunctionalTester $I)
    {
        $I->assertFalse($this->service->can(new Subject('1'), new Action('view'), $this->image1));

        $this->service->grant(new Subject('1'), new Action('view'), $this->gallery1);

        $I->assertTrue($this->service->can(new Subject('1'), new Action('view'), $this->image1));
    }


    protected function getDefaultProperties()
    {
        return new ArrayProperties([
            'rootPolicies'    => [
                __DIR__ . '/galleryPolicy.php',
            ],
            'pep'             => [
                'issuer'  => 'test',
                'mappers' => [
                    'classes'        => [
                        ImageMapper::class,
                    ],
                    'configurations' => [
                        __DIR__ . '/galleryMapper.php',
                    ],
                ],
            ],
        ]);
    }
}

class Gallery
{
    protected $id;
    protected $images = [];

    public function __construct($id)
    {
        $this->id = (string) $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function addImage(Image $image)
    {
        $image->addGallery($this);
        $this->images[] = $image;
    }

    public function getImages()
    {
        return $this->images;
    }
}

class Image
{
    protected $galleries;
    protected $id;

    public function __construct($id)
    {
        $this->id = (string) $id;
    }

    public function addGallery(Gallery $gallery)
    {
        $this->galleries[] = $gallery;
    }

    public function getGalleries()
    {
        return $this->galleries;
    }

    public function getId()
    {
        return $this->id;
    }
}

class ImageMapper extends ObjectMapper
{
    protected $className = Image::class;

    public function map($object, PepRequest $pepRequest)
    {
        $pepRequestAttributes = $pepRequest->getPepRequestAttributes(AttributeIdentifier::RESOURCE_CATEGORY);

        $galleryIds = [];
        foreach ($object->getGalleries() as $gallery) {
            $galleryIds[] = (string) $gallery->getId();
        }
        $imageData = [
            'resource' => [
                'galleryIds' => $galleryIds,
            ],
        ];
        $pepRequestAttributes
            ->addContent('image', new Content($imageData))
            ->addAttribute(ResourceIdentifier::RESOURCE_ID, $object->getId())
            ->addAttribute(ResourceIdentifier::RESOURCE_TYPE, Image::class);
//            ->addAttribute('resource:gallery-ids', $galleryIds);
    }

}
