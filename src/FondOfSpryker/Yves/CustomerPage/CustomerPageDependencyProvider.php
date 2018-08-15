<?php

namespace FondOfSpryker\Yves\CustomerPage;

use SprykerShop\Yves\CartNoteWidget\Plugin\CustomerPage\CartNoteOrderItemNoteWidgetPlugin;
use SprykerShop\Yves\CartNoteWidget\Plugin\CustomerPage\CartNoteOrderNoteWidgetPlugin;
use SprykerShop\Yves\CustomerPage\CustomerPageDependencyProvider as SprykerShopCustomerPageDependencyProvider;
use SprykerShop\Yves\CustomerReorderWidget\Plugin\CustomerPage\CustomerReorderWidgetPlugin;
use SprykerShop\Yves\NewsletterWidget\Plugin\CustomerPage\NewsletterSubscriptionSummaryWidgetPlugin;
use SprykerShop\Yves\WishlistWidget\Plugin\CustomerPage\WishlistMenuItemWidgetPlugin;

class CustomerPageDependencyProvider extends SprykerShopCustomerPageDependencyProvider
{
    /**
     * @return string[]
     */
    protected function getCustomerOverviewWidgetPlugins(): array
    {
        return [
            NewsletterSubscriptionSummaryWidgetPlugin::class,
            CustomerReorderWidgetPlugin::class,
        ];
    }

    /**
     * @return string[]
     */
    protected function getCustomerOrderListWidgetPlugins(): array
    {
        return [
            CustomerReorderWidgetPlugin::class,
        ];
    }

    /**
     * @return string[]
     */
    protected function getCustomerOrderViewWidgetPlugins(): array
    {
        return [
            CustomerReorderWidgetPlugin::class,
            CartNoteOrderItemNoteWidgetPlugin::class, #CartNoteFeature
            CartNoteOrderNoteWidgetPlugin::class, #CartNoteFeature
        ];
    }

    /**
     * @return string[]
     */
    protected function getCustomerMenuItemWidgetPlugins(): array
    {
        return [
            WishlistMenuItemWidgetPlugin::class,
        ];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreRegistrationCustomerTransferExpanderPluginInterface[]
     */
    protected function getPreRegistrationCustomerTransferExpanderPlugins(): array
    {
        return [
        ];
    }
}
