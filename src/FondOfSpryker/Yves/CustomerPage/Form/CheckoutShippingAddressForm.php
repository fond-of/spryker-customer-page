<?php

namespace FondOfSpryker\Yves\CustomerPage\Form;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $this->addAdditionalAddress($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAdditionalAddress(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ADDITIONAL_ADDRESS,
            TextType::class,
            [
                'label' => 'customer.address.additional_address',
                'required' => false,
            ]
        );

        return $this;
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
}
