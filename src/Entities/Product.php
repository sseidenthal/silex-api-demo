<?php

namespace App\Entities;

use App\Traits\PopulateTrait;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Product
{
    use PopulateTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @JMS\Expose()
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var Price
     *
     * @ORM\OneToOne(targetEntity="App\Entities\Price", cascade={"persist"})
     *
     * @JMS\Expose()
     */
    protected $price;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="product", cascade={"all"})
     *
     * @JMS\Expose()
     */
    protected $categories;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @param Price $price
     */
    public function setPrice(Price $price)
    {
        $this->price = $price;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories(): ArrayCollection
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     */
    public function setCategories(ArrayCollection $categories)
    {
        foreach ($categories as $category_) {
            $this->addCategory($category_);
        }
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category)
    {
        $category->setProduct($this);
        $this->categories[] = $category;
    }

    /**
     * @return ArrayCollection
     */
    public function getCarts(): ArrayCollection
    {
        return $this->carts;
    }

    /**
     * @param ArrayCollection $carts
     */
    public function setCarts(ArrayCollection $carts)
    {
        foreach ($carts as $cart) {
            $this->addCart($cart);
        }
    }

    public function addCart(Cart $cart)
    {
        $cart->addProduct($this);
        $this->carts[] = $cart;
    }

}