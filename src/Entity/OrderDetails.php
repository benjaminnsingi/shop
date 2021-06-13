<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=OrderDetailsRepository::class)
 */
class OrderDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $product = null;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private ?int $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $price = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $total = null;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderDetails")
     */
    private ?Order $myorder;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Pure] public function __toString()
    {
        return $this->getProduct();
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getMyorder(): ?Order
    {
        return $this->myorder;
    }

    public function setMyorder(?Order $myorder): self
    {
        $this->myorder = $myorder;

        return $this;
    }
}
