    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 
        <script src="script.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="body-wrapper">
                <div>
                    <div class="text-center">
                        <div class="d-inline-block p-2 bg-primary b-radius" id="header-text"> Payment Details
                        </div>
                    </div>

                    <div class="container-sm pt-5">
                        <div class="payment-card">
                            
                            <div class="">
                        

                                <h5>Total Amount</h5>
                      <img src="payment.png" alt="Paris" style="border-radius: 4px;padding: 15px;width: 350px; align-items:center;justify-content:center">
                                <!-- <div class="row">
                                    <div class="col">
                                        <div class="card mt-4 p-2">Card Details</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="card mt-4 p-2">Upi</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="card mt-4 p-2">BHIM</div>
                                    </div>
                                </div> -->
                            </div>

                            <div class="mt-5">
                                <div class="row">
                                    <div class="col my-auto">
                                        <div id="amount-id" class="bill-amt">$0</div>
                                        <script>getBillingAmount()</script>
                                    </div>
                                    
                                    <div class="col">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary float-right buy_now"
                                     data-img="//www.tutsmake.com/wp-content/uploads/2019/03/c05917807.png" data-amount="1222" data-id="1">Pay now</a>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
document.addEventListener('DOMContentLoaded', function () {
    // Get the content of the 'amount-id' div
    var amountDiv = document.getElementById('amount-id');
    var amountValue = amountDiv.textContent || amountDiv.innerText;

    // Clean the content to remove leading/trailing whitespace and any non-numeric characters
    var cleanedAmount = amountValue.trim().replace(/[^0-9.]/g, '');

    // Convert the cleaned amount to a floating-point number
    var dynamicAmount = parseFloat(cleanedAmount);

    // Check if dynamicAmount is a valid number
    if (!isNaN(dynamicAmount)) {
        // Add the rupee symbol (₹) to the dynamic amount
        var formattedAmount = '₹' + dynamicAmount.toFixed(2); // Assuming 2 decimal places

        // Set the content of the 'amount-id' div
        amountDiv.textContent = formattedAmount;

        // Get all elements with the class 'buy_now' and set their data-amount attribute
        var buyNowButtons = document.querySelectorAll('.buy_now');
        buyNowButtons.forEach(function (button) {
            button.setAttribute('data-amount', dynamicAmount.toFixed(2));
        });
    } else {
        // Handle the case where the content couldn't be converted to a valid number
        console.error('Invalid amount:', amountValue);
    }
});
</script>



        
        <script>
        // Assuming you have an array of amounts fetched from the database
        var amounts = <?php echo json_encode($amountsArray); ?>;

        // Get all elements with the class 'buy_now' and update their data-amount attribute
        document.addEventListener('DOMContentLoaded', function() {
            var buyNowButtons = document.querySelectorAll('.buy_now');
            buyNowButtons.forEach(function(button, index) {
                button.setAttribute('data-amount', amounts[index]);
            });
        });
        </script>

        <script>
        function deleteItem(id) {
            $.ajax({
                type: "POST",
                url: "deleteItemFromCart.php",
                data: {
                    id: id
                },
                success: function (response) {
                    if (response == 'Deleted') {
                        window.location.href = "cart.php";
                    } else {
                        document.getElementById('cartError').innerHTML = "Some problem occurred. Please try again!";
                    }
                },
                error: function (xhr, status, error) {
                    // Handle errors here
                }
            });
        }

        function pay() {
            $('#confirmModal').modal('show');
        }

        function confirm(id) {
            $.ajax({
                type: "POST",
                url: "book.php",
                data: {
                    id: id
                },
                success: function (response) {
                    if (response == 'Success') {
                        document.getElementById('modal-content').innerHTML = "<div class='mx-3 my-5'><h4>Payment Succesful!</h4><i style='transform: scale(2)' class='fa fa-check text text-success'></i></div>";
                        setTimeout(function () {
                            window.location.href = "homepage.php";
                        }, 2000);
                    } else {
                        document.getElementById('cartError').innerHTML = "Some problem occurred. Please try again!";
                    }
                },
                error: function (xhr, status, error) {
                    // Handle errors here
                }
            });

        }
    </script>


    <!-- razorpay script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>

    $('body').on('click', '.buy_now', function(e){
        var prodimg = $(this).attr("data-img");
        var totalAmount = $(this).attr("data-amount");
        var product_id =  $(this).attr("data-id");
        var options = {
        "key": "rzp_test_yHjySVTtihEPSM",
        "amount": (totalAmount*100), // 2000 paise = INR 20
        "name": "Tutsmake",
        "description": "Payment",
    
        "handler": function (response){
            $.ajax({
                url: 'payment-proccess.php',
                type: 'post',
                dataType: 'json',
                data: {
                    razorpay_payment_id: response.razorpay_payment_id , totalAmount : totalAmount ,product_id : product_id,
                }, 
                success: function (msg) {

                window.location.href = 'https://www.tutsmake.com/Demos/php/razorpay/success.php';
                }
            });
        
        },

        "theme": {
            "color": "#528FF0"
        }
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.`preventDefault`();
    });

    </script>

    <script src=""></script>
    <script>
    
    $('body').on('click', '.buy_now', function(e){
        var prodimg = $(this).attr("data-img");
        var totalAmount = $(this).attr("data-amount");
        var product_id =  $(this).attr("data-id");
        var options = {
        "key": "rzp_test_yHjySVTtihEPSM", // secret key id
        "amount": (totalAmount*100), // 2000 paise = INR 20
        "name": "Tutsmake",
        "description": "Payment",
    
        "handler": function (response){
            $.ajax({
                url: 'payment-proccess.php',
                type: 'post',
                dataType: 'json',
                data: {
                    razorpay_payment_id: response.razorpay_payment_id , totalAmount : totalAmount ,product_id : product_id,
                }, 
                success: function (msg) {
    
                window.location.href = 'payment-success.php';
                }
            });
        
        },
    
        "theme": {
            "color": "#528FF0"
        }
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.preventDefault();
    });
    
    </script>

    </body>  
    </html>
