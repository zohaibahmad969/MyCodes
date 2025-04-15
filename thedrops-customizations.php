<style>
            body.woocommerce-checkout {
                background-color: #f7ece3;
            }
            #brx-content.wordpress {
                width: 100%;
                max-width: 1220px;
                margin-top: 60px;
                margin-bottom: 140px;
                padding: 0 48px;
            }
            @media (max-width: 1280px) {
                #brx-content.wordpress {
                    max-width: 1204px;
                    padding: 0 40px;
                }
            }
            @media (max-width: 1024px) {
                #brx-content.wordpress {
                    max-width: calc(100% - 64px);
                    padding: 0;
                }
            }
            @media (max-width: 809px) {
                #brx-content.wordpress {
                    width: calc(100% - 64px);
                    max-width: 476px;
                    margin-top: 72px;
                }
            }
            @media (max-width: 430px) {
                #brx-content.wordpress {
                    width: 100%;
                    max-width: calc(100% - 40px);
                }
            }
            @media (max-width: 390px) {
                #brx-content.wordpress {
                    max-width: calc(100% - 36px);
                }
            }
            @media (max-width: 320px) {
                #brx-content.wordpress {
                    max-width: calc(100% - 32px);
                }
            }
            h1 {
                text-align: center;
                font-family: "Dynapuff";
                font-size: 63px;
                line-height: 46px;
                font-weight: 500;
                color: #db6c36;
                text-shadow: 2px 4px 0 #132e5d;
                -webkit-text-stroke: 1px #132e5d;
                position: relative;
                text-transform: uppercase;
                word-spacing: -1px;
            }
            h1::before {
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                color: inherit;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            @media (max-width: 1024px) {
                h1 {
                    font-size: 59px;
                    line-height: 43px;
                    word-spacing: -0.94px;
                }
            }
            @media (max-width: 430px) {
                h1 {
                    font-size: 46px;
                    line-height: 34px;
                    word-spacing: -0.92px;
                }
            }
            .wc-block-checkout.wp-block-woocommerce-checkout {
                padding-top: 120px;
                max-width: 100%;
                width: 100%;
            }
            @media (max-width: 809px) {
                .wc-block-checkout.wp-block-woocommerce-checkout {
                    padding-top: 72px;
                }
            }
            .wc-block-checkout.wc-block-components-sidebar-layout {
                margin-bottom: 0;
                justify-content: space-between;
            }
            @media (max-width: 809px) {
                .wc-block-checkout.wc-block-components-sidebar-layout {
                    flex-direction: column;
                    gap: 60px;
                }
            }
            @media (max-width: 430px) {
                .wc-block-checkout.wc-block-components-sidebar-layout {
                    gap: 41px;
                }
            }
            .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-main {
                width: 476px;
                padding-right: 0;
            }
            .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-sidebar {
                width: 455px;
                padding-left: 0;
                margin-top: 0;
                margin-bottom: 0;
            }
            @media (max-width: 1024px) {
                .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-main {
                    width: calc(50% - 24px);
                }
                .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-sidebar {
                    width: calc(50% - 24px);
                }
            }
            @media (max-width: 809px) {
                .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-main {
                    width: 100%;
                }
                .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-sidebar {
                    width: 100%;
                }
            }
            .wc-block-components-form .wc-block-components-checkout-step#contact-fields,
            .wc-block-components-form .wc-block-components-checkout-step#shipping-fields,
            .wc-block-components-form .wc-block-components-checkout-step#billing-fields {
                margin-bottom: 36px;
            }
            .wc-block-components-checkout-step__heading {
                margin: 0 !important;
                margin-bottom: 8px !important;
            }
            #shipping-option .wc-block-components-checkout-step__heading {
                margin-bottom: 20px !important;
            }
            #payment-method .wc-block-components-checkout-step__heading {
                margin-bottom: 16px !important;
            }
            .wc-block-components-title.wc-block-components-title {
                font-family: "Satoshi-Variable" !important;
                font-size: 21px !important;
                line-height: 22px !important;
                font-weight: 700 !important;
                color: #132e5d !important;
            }
            .wc-block-components-checkout-step__description {
                font-family: "Satoshi-Variable";
                font-size: 16px;
                line-height: 22px;
                color: rgba(19, 46, 93, 0.8);
                margin-bottom: 20px;
            }
            .wc-blocks-components-select .wc-blocks-components-select__container {
                box-shadow: none !important;
                border: none !important;
                background: transparent !important;
                border-radius: 0 !important;
                height: unset !important;
            }
            .wc-block-components-address-form__country .wc-blocks-components-select .wc-blocks-components-select__container {
                margin-top: 0 !important;
            }
            .wc-block-components-form input,
            .wc-block-components-form textarea,
            .wc-block-components-form select {
                padding: 12px 16px !important;
                border: 0.6px solid rgba(19, 46, 93, 0.4) !important;
                box-shadow: 2px 2px 1.3px rgba(19, 46, 93, 0.3) !important;
                background-color: #FFFCFA !important;
                outline: none;
                border-radius: 2px !important;
                font-size: 16px !important;
                line-height: 22px !important;
                font-family: "Satoshi-Variable" !important;
                color: #132e5d !important;
                height: auto !important;
            }
            .wc-block-components-form input::placeholder,
            .wc-block-components-form textarea::placeholder {
                color: rgba(19, 46, 93, 0.4) !important;
            }
            .wc-block-components-form .wc-block-components-text-input label,
            .wc-block-components-form .wc-blocks-components-select label {
                display: none;
            }
            .wc-block-components-address-form__address_2-toggle {
                display: block !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 18px !important;
                font-weight: 500 !important;
                line-height: 22px !important;
                color: #132e5d !important;
                margin-top: 20px !important;
            }
            .wc-block-components-checkbox .wc-block-components-checkbox__label {
                font-size: 16px !important;
                line-height: 22px !important;
                color: #132e5d !important;
                font-family: "Satoshi-Variable" !important;
                font-weight: 500 !important;
            }
            .wc-block-checkout__use-address-for-billing .wc-block-components-checkbox__label {
                font-size: 18px !important;
            }
            .wc-block-components-checkbox label {
                align-items: center !important;
            }
            .wc-block-components-checkbox .wc-block-components-checkbox__input {
                width: 16px !important;
                height: 16px !important;
                padding: 0 !important;
                margin-right: 8px !important;
                border: 1.5px solid rgba(19, 46, 93, 0.4) !important;
                border-radius: 2px !important;
                box-shadow: none !important;
                min-width: 19px !important;
                min-height: 19px !important;
                outline: none !important;
                background-color: transparent !important;
            }
            .wc-block-components-checkbox .wc-block-components-checkbox__mark {
                min-width: 15px !important;
                min-height: 15px !important;
                width: 15px !important;
                height: 15px !important;
                margin: 0 !important;
                left: 2px;
                top: 2px;
            }
            .wc-block-components-radio-control {
                background-color: #EDE7E5;
                border-radius: 2px;
                box-shadow: 2px 2px 1.3px rgba(19, 46, 93, 0.3);
            }
            .wc-block-components-radio-control__option {
                background-color: transparent !important;
                box-shadow: none !important;
                border: none !important;
                padding: 16px !important;
                padding-left: 54px !important;
            }
            .wc-block-components-radio-control__label,
            .wc-block-components-radio-control__secondary-label {
                font-family: "Satoshi-Variable";
                font-size: 16px !important;
                font-weight: 500 !important;
                line-height: 24px !important;
                color: #132e5d !important;
            }
            input.wc-block-components-radio-control__input {
                padding: 0 !important;
                width: 22px !important;
                height: 22px !important;
                margin: 0 !important;
                min-width: 22px !important;
                min-height: 22px !important;
                border: 1px solid #6184c2 !important;
                border-radius: 50% !important;
                box-shadow: none !important;
                background-color: transparent !important;
            }
            input.wc-block-components-radio-control__input::before {
                background-color: #6183c2 !important;
                width: 14px !important;
                height: 14px !important;
            }
            input.wc-block-components-radio-control__input:focus,
            input.wc-block-components-radio-control__input:focus-visible {
                outline: none !important;
            }
            .wc-block-components-form .wc-block-checkout__order-notes.wc-block-components-checkout-step {
                margin-bottom: 0 !important;
                margin-top: 20px !important;
            }
            .wc-block-checkout__payment-method .wc-block-components-checkout-step__content {
                padding-top: 0 !important;
            }
            .wc-block-components-radio-control-accordion-option {
                box-shadow: none !important;
            }
            .wc-block-components-radio-control-accordion-content {
                padding: 16px !important;
                padding-top: 0 !important;font-family: "Satoshi-Variable" !important;
                font-size: 14px !important;
                line-height: 22px !important;
                color: rgba(19, 46, 93, 0.8);
            }
            .wc-block-components-form .wc-block-components-checkout-step#payment-method {
                margin-bottom: 56px !important;
            }
            .wc-block-checkout__payment-method .wc-block-components-radio-control.disable-radio-control .wc-block-components-radio-control__input {
                display: block !important;
            }
            .wc-block-checkout__terms.wc-block-checkout__terms--with-separator {
                border: none !important;
                margin-bottom: 23px !important;
                padding-top: 0 !important;
            }
            .wc-block-checkout__actions {
                padding: 0 !important;
            }
            .wc-block-components-checkout-place-order-button {
                background: #6184c2 !important;
                color: #F7ECE3 !important;
                font-family: "DynaPuff";
                font-size: 24px !important;
                line-height: 20px !important;
                font-weight: 600 !important;
                padding: 20px 26px !important;
                min-height: unset !important;
                text-shadow: 1.09px 2.18px #272525;
                -webkit-text-stroke: 1.09px #272525 !important;
                box-shadow: 1px 1px 0 #132e5d;
                position: relative;
            }
            .wc-block-components-checkout-place-order-button::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            .wp-block-woocommerce-checkout-order-summary-block {
                background: #FFFCFA;
                border: 1.8px solid rgba(19, 46, 93, 0.8) !important;
                border-radius: 2px !important;
                padding: 24px !important;
            }
            @media (max-width: 430px) {
                .wp-block-woocommerce-checkout-order-summary-block {
                    padding: 20px !important;
                }
            }
            @media (max-width: 360px) {
                .wp-block-woocommerce-checkout-order-summary-block {
                    padding: 16px !important;
                }
            }
            .wp-block-woocommerce-checkout-order-summary-block .wc-block-components-totals-wrapper,
            .wp-block-woocommerce-checkout-order-summary-totals-block {
                border-top: none !important;
                padding: 0 !important;
                margin-bottom: 12px !important;
            }
            .wc-block-components-sidebar .wc-block-components-panel,
            .wc-block-components-sidebar .wc-block-components-totals-coupon,
            .wc-block-components-sidebar .wc-block-components-totals-item {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .wc-block-components-panel__button {
                margin-top: 10px !important;
                margin-bottom: 10px !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 19px !important;
                font-weight: 700 !important;
                line-height: 23.4px !important;
                color: #132e5d !important;
            }
            .wc-block-components-order-summary .wc-block-components-order-summary__button-text {
                font-weight: 700 !important;
            }
            .wc-block-components-totals-coupon .wc-block-components-panel__button {
                margin-bottom: 12px !important;
            }
            .wc-block-components-panel__button svg path {
                fill: #132e5d;
            }
            .wc-block-components-totals-item {
                position: relative !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 19px !important;
                font-weight: 700 !important;
                line-height: 23.4px !important;
                color: #132e5d !important;
                padding: 10px 0 !important;
            }
            .wc-block-components-totals-footer-item {
                margin-top: 46px !important;
            }
            .wc-block-components-totals-item__description {
                color: rgba(19, 46, 93, 0.4) !important;
                font-size: 16px !important;
                font-weight: 500 !important;
            }
            .wp-block-woocommerce-checkout-order-summary-cart-items-block .wc-block-components-order-summary__content {
                display: flex !important;
                flex-direction: column !important;
                gap: 12px !important;
            }
            .wp-block-woocommerce-checkout-order-summary-cart-items-block .wc-block-components-order-summary-item {
                gap: 36px;
                padding: 0 !important;
            }
            .woocommerce-mini-cart__buttons.buttons a:first-child {
                display: none !important;
            }
            @media (max-width: 360px) {
                .wp-block-woocommerce-checkout-order-summary-cart-items-block .wc-block-components-order-summary-item {
                    gap: 28px;
                }
            }
            @media (max-width: 320px) {
                .wp-block-woocommerce-checkout-order-summary-cart-items-block .wc-block-components-order-summary-item {
                    gap: 14px;
                }
            }
            .wc-block-components-order-summary-item__image {
                display: flex !important;
                flex-shrink: 0;
                width: 92px !important;
                height: 92px !important;
                padding: 0 !important;
                margin: 0 !important;
                border: 0.81px solid #000 !important;
                align-items: center !important;
                justify-content: center !important;
                background-color: #f7ece3 !important;
                box-shadow: 0.81px 0.81px 0 #132e5d !important;
            }
            .wc-block-components-order-summary-item__image > img {
                max-width: 56px !important;
                width: 56px !important;
                height: 56px !important;
            }
            .wc-block-components-product-name {
                order: 1;
            }
            .wc-block-components-order-summary-item__individual-prices {
                padding-top: 0 !important;
                order: 3;
            }
            .wc-block-components-order-summary-item__description {
                display: flex !important;
                padding: 4px 0 !important;
                flex-direction: column !important;
            }
            .wc-block-components-product-name, .wc-block-components-product-metadata__description {
                font-family: "Satoshi-Variable" !important;
                font-size: 16px !important;
                font-weight: 700 !important;
                line-height: 110% !important;
                color: #132e5d !important;
                padding-top: 0 !important;
            }
            .wc-block-components-product-metadata {
                margin-top: 0 !important;
                order: 2;
            }
            .wc-block-components-product-metadata__description {
                line-height: 24px;
            }
            .wc-block-components-product-metadata__description p {
                margin: 0 !important;
            }
            .wc-block-components-product-details {
                margin-top: 0 !important;
            }
            .wc-block-components-product-details li,
            .wc-block-components-order-summary-item__individual-prices {
                color: rgba(19, 46, 93, 0.4) !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 16px !important;
                font-weight: 500 !important;
                line-height: 24px !important;
            }
            .wc-block-components-product-price__value.is-discounted {
                margin-left: 12px !important;
                color: #132e5d !important;
            }
            .wc-block-components-order-summary-item__total-price {
                display: none !important;
            }
            .wc-block-components-totals-shipping .wc-block-components-shipping-address {
                margin-top: 0 !important;
            }
            .wc-block-components-totals-item__value {
                font-size: inherit !important;
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
            }
            .wc-block-components-totals-coupon__form {
                position: relative !important;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input {
                width: 100% !important;
                flex: none !important;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input input,
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input input:focus,
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input input:focus-visible {
                border: 1px solid rgba(19, 46, 93, 0.4) !important;
                padding: 8px !important;
                border-radius: 4px !important;
                outline: none !important;
                box-shadow: none !important;
                min-height: auto !important;
                height: 46px !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 18px !important;
                font-weight: 500 !important;
                line-height: 23.4px !important;
                color: rgba(19, 46, 93, 0.4) !important;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input input::placeholder {
                color: rgba(19, 46, 93, 0.4) !important;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input label {
                display: none;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__button {
                padding: 10px 18px !important;
                min-height: unset !important;
                background-color: #6184c2 !important;
                color: #f7ece3 !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 14px !important;
                border-radius: 2px !important;
                font-weight: 700 !important;
                line-height: 10px;
                display: inline-flex !important;
                width: auto !important;
                flex: none !important;
                border: none !important;
                position: absolute !important;
                top: 8px !important;
                right: 8px !important;
                box-shadow: none !important;
            }
        </style>
