<?php

namespace Till\Domain;

use Illuminate\Support\Collection;
use Money\Currency;

/**
 * Container for lines on a receipt.
 */
class Receipt
{
    /** @var Collection */
    private $purchases;

    /** @var Collection */
    private $discounts;

    /**
     * @param Purchase $purchase
     *
     * @throws \InvalidArgumentException
     */
    public function addPurchase(Purchase $purchase)
    {
        $this->checkLineCurrency($purchase);
        $this->purchases()->push($purchase);
    }

    /**
     * @param Discount $discount
     *
     * @throws \InvalidArgumentException
     */
    public function addDiscount(Discount $discount)
    {
        $this->checkLineCurrency($discount);
        $this->discounts()->push($discount);
    }

    /**
     * @return Collection|Purchase[]
     */
    public function purchases(): Collection
    {
        if ($this->purchases === null) {
            $this->purchases = new Collection();
        }

        return $this->purchases;
    }

    /**
     * @return Collection|Discount[]
     */
    public function discounts(): Collection
    {
        if ($this->discounts === null) {
            $this->discounts = new Collection();
        }

        return $this->discounts;
    }

    /**
     * @return Collection|Line[]
     */
    public function lines(): Collection
    {
        return $this->purchases()->merge($this->discounts());
    }

    /**
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function grandTotal(): Money
    {
        return $this->subTotal()->add($this->totalDiscounts());
    }

    /**
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function subTotal(): Money
    {
        return $this->purchases()->reduce(
            function (Money $total, Purchase $purchase) {
                return $total->add($purchase->amount());
            },
            Money::fromAmount(0, $this->currency())
        );
    }

    /**
     * Total discounts as a negative amount.
     *
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function totalDiscounts(): Money
    {
        return $this->discounts()->reduce(
            function (Money $total, Discount $discount) {
                return $total->add($discount->amount());
            },
            Money::fromAmount(0, $this->currency())
        );
    }

    /**
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->lines()->first()->currency();
    }

    /**
     * @param Line $line
     *
     * @throws \InvalidArgumentException
     */
    private function checkLineCurrency(Line $line)
    {
        if ($this->lines()->isEmpty()) {
            return;
        }

        if (!$line->currency()->equals($this->currency())) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Can\'t add line of currency %s to receipt of currency %s.',
                    $line->currency()->getCode(),
                    $this->currency()->getCode()
                )
            );
        }
    }
}
