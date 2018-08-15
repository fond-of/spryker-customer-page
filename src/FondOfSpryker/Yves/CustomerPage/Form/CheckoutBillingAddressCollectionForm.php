<?php

namespace FondOfSpryker\Yves\CustomerPage\Form;

use Generated\Shared\Transfer\AddressTransfer;
use FondOfSpryker\Yves\CheckoutPage\Form\CheckoutAddressForm;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CheckoutBillingAddressCollectionForm extends AbstractType
{
    const FIELD_SHIPPING_ADDRESS = 'shippingAddress';
    const FIELD_BILLING_ADDRESS = 'billingAddress';
    const FIELD_BILLING_SAME_AS_SHIPPING = 'billingSameAsShipping';

    const OPTION_ADDRESS_CHOICES = 'address_choices';
    const OPTION_COUNTRY_CHOICES = 'country_choices';

    const GROUP_SHIPPING_ADDRESS = self::FIELD_SHIPPING_ADDRESS;
    const GROUP_BILLING_ADDRESS = self::FIELD_BILLING_ADDRESS;

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'billingAddressForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /** @var \Symfony\Component\OptionsResolver\OptionsResolver $resolver */
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $validationGroups = [Constraint::DEFAULT_GROUP, self::GROUP_BILLING_ADDRESS];

                if (!$form->get(self::FIELD_BILLING_SAME_AS_SHIPPING)->getData()) {
                    $validationGroups[] = self::GROUP_SHIPPING_ADDRESS;
                }

                return $validationGroups;
            },
            self::OPTION_ADDRESS_CHOICES => [],
        ]);

        $resolver->setDefined(self::OPTION_ADDRESS_CHOICES);
        $resolver->setRequired(self::OPTION_COUNTRY_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSameAsShipmentCheckbox($builder)
            ->addBillingAddressSubForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Pyz\Yves\CustomerPage\Form\CheckoutBillingAddressCollectionForm
     */
    protected function addSameAsShipmentCheckbox(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_BILLING_SAME_AS_SHIPPING,
            CheckboxType::class,
            [
                'required' => false,
                'data' => true
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Pyz\Yves\CustomerPage\Form\CheckoutBillingAddressCollectionForm
     */
    protected function addBillingAddressSubForm(FormBuilderInterface $builder, array $options)
    {
        $options = [
            'data_class' => AddressTransfer::class,
            'validation_groups' => function (FormInterface $form) {
                if (!$form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS) || !$form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData()) {
                    return [self::GROUP_BILLING_ADDRESS];
                }

                return false;
            },
            CheckoutAddressForm::OPTION_VALIDATION_GROUP => self::GROUP_BILLING_ADDRESS,
            CheckoutAddressForm::OPTION_ADDRESS_CHOICES => $options[self::OPTION_ADDRESS_CHOICES],
            CheckoutAddressForm::OPTION_COUNTRY_CHOICES => $options[self::OPTION_COUNTRY_CHOICES],
        ];

        $builder->add(self::FIELD_BILLING_ADDRESS, CheckoutBillingAddressForm::class, $options);

        return $this;
    }
}
