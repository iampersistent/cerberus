<?php
declare(strict_types = 1);

use Cerberus\CerberusService;
use Cerberus\PDP\Policy\Content;
use Cerberus\PDP\Utility\ArrayProperties;
use Cerberus\PEP\Action\Action;
use Cerberus\PEP\ObjectMapper;
use Cerberus\PEP\PepRequest;
use Cerberus\PEP\Subject;
use Cerberus\Core\Enums\ResourceIdentifier;

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
        $properties = require __DIR__ . '/../../_data/fixtures/Examples/GalleryImage/properties.php';
        $this->service = new CerberusService(new ArrayProperties($properties));
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
        $pepRequestAttributes = $pepRequest->getPepRequestAttributes(\Cerberus\Core\Enums\AttributeCategoryIdentifier::RESOURCE);

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
//            ->addAttribute(ResourceIdentifier::RESOURCE('gallery-ids'), $galleryIds);
    }

}
