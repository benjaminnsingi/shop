<?php


namespace App\Twig;


use App\Data\Cart;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartTwigExtension extends AbstractExtension
{
    private Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cart_count', [$this->cart, 'getFull']),
        ];
    }
}
