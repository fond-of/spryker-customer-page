<?php

namespace FondOfSpryker\Yves\CustomerPage\Form;

use FondOfSpryker\Yves\CheckoutPage\Form\CheckoutAddressForm;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\Form\FormBuilderInterface;

class CheckoutShippingAddressForm extends CheckoutAddressForm
{
    const FIELD_ADDITIONAL_ADDRESS = 'additional_address';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove(self::FIELD_EMAIL);
        $this->removeCompany($builder, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddress(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getShippingAddress();
    }

    protected function removeCompany(FormBuilderInterface $builder, array $options)
    {
        $builder->remove(self::FIELD_COMPANY);

        return $this;
    }
}
