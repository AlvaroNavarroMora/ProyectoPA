<div id="paypal-button-container"></div>
<div id="paypal-button"></div>

<script src="https://www.paypalobjects.com/api/checkout.js"></script> < !-- Para paypal -- >
<script>


    // Configure environment
                                
            paypal.Button.render({
        env: 'sandbox',
        client: {
        sandbox: 'Aag_BV9saCzCn3jZU7nRT-_qMd-sJuXnc9VKSeM5li-IXLAGDi2zUsiRtPpTu3Tvr46fIq9Ce6KSjkug',
                },
                // Customize button (optional)
                style: {
                size: 'small',
                color: 'gold',
                shape: 'pill',
                },
                // Set up a payment
                payment: function (data, actions) {
                return actions.payment.create({
                transactions: [{
                amount: {
                total: '<?php echo $total; ?>',
                        currency: 'EUR',
                        custom: 'IDPedido'
                                },
                                description: 'Transacción de UPOMarket'
                            }]
                    });
                },

                // Execute the payment
                onAuthorize: function (data, actions) {
                        return actions.payment.execute().then(function () {
                window.location = "verificadorTransaccion.php?paymentToken=" + data.paymentToken;
                    });
                }
            }
            , '#paypal-button');

</script>