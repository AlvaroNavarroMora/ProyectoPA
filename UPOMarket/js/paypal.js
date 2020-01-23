//<div id="paypal-button"></div>
//<script src="https://www.paypalobjects.com/api/checkout.js"></script>

paypal.Button.render({
// Configure environment
    env: 'sandbox',
    client: {
        sandbox: 'demo_sandbox_client_id',
        production: 'demo_production_client_id'
    },
    // Customize button (optional)
    locale: 'en_US',
    style: {
        size: 'small',
        color: 'gold',
        shape: 'pill',
    },
    // Enable Pay Now checkout flow (optional)
    commit: true,
    // Set up a payment
    payment: function (data, actions) {
        return actions.payment.create({
            transactions: [{
                    amount: {
                        total: '0.01',
                        currency: 'EUR'
                    },
                    description: 'Transacción de prueba de UPOMarket',
                    item_list: {
                        items: [
                            {
                                name: 'hat',
                                description: 'Brown hat.',
                                quantity: '5',
                                price: '3',
                                tax: '0.01',
                                sku: '1',
                                currency: 'USD'
                            },
                            {
                                name: 'handbag',
                                description: 'Black handbag.',
                                quantity: '1',
                                price: '15',
                                tax: '0.02',
                                sku: 'product34',
                                currency: 'USD'
                            }],
                        shipping_address: {
                            recipient_name: 'Brian Robinson',
                            line1: '4th Floor',
                            line2: 'Unit #34',
                            city: 'San Jose',
                            country_code: 'US',
                            postal_code: '95131',
                            phone: '011862212345678',
                            state: 'CA'
                        }
                    }
                }]
        });
    },

    // Execute the payment
    onAuthorize: function (data, actions) {
        return actions.payment.execute().then(function () {
            // Show a confirmation message to the buyer
            window.alert('¡Gracias por su compra!');
        });
    }
}
, '#paypal-button');

//</script>