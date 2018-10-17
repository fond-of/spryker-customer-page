<?php

namespace FondOfSpryker\Yves\CustomerPage\Form;

use FondOfSpryker\Shared\Customer\CustomerConstants;
use FondOfSpryker\Yves\CheckoutPage\Form\CheckoutAddressForm;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CheckoutBillingAddressForm extends CheckoutAddressForm
{
    const FIELD_EMAIL_ADDRESS = 'email';
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

        $this->addEmailField($builder, $options);
        $this->removeCompany($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_EMAIL_ADDRESS, EmailType::class, [
            'label' => 'customer.email',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
                $this->createMinLengthConstraint($options),
            ],
        ]);

        return $this;
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPhoneField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_PHONE, TextType::class, [
            'label' => 'customer.address.phone',
            'required' => false,
            'constraints' => $this->createCallbackConstraint($options),
        ]);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddress(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function removeCompany(FormBuilderInterface $builder, array $options)
    {
        $builder->remove(self::FIELD_COMPANY);

        return $this;
    }

    protected function createCallbackConstraint(array $options)
    {
        return new Callback([
            'callback' => function($object, ExecutionContextInterface $context)
            {
                /** @var QuoteTransfer $quoteTransfer */
                $quoteTransfer = $context->getRoot()->getData();

                /** @var AddressTransfer $address */
                $address = $this->getAddress($quoteTransfer);

                if (!$this->isPhoneNumberValid($address)) {
                    $context->buildViolation('checkout.error.field.notEU')
                        ->addViolation();
                }
            },
            'payload' => $this,
            'groups' => $options[self::OPTION_VALIDATION_GROUP],
        ]);
    }

    protected function isPhoneNumberValid(AddressTransfer $addressTransfer)
    {
        $countriesInEU = CustomerConstants::COUNTRIES_IN_EU;
        $phoneNumber = $addressTransfer->getPhone();
        $countryIsoCode = $addressTransfer->getIso2Code();

        if (empty($phoneNumber) && !in_array($countryIsoCode, $countriesInEU)) {
            return false;
        }

        return true;
    }
}
