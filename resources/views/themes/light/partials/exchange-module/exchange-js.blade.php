 <?php
    $accountLevel = null;
    if (auth()->check()) {
        $user = auth()->user();
        $accountLevel = $user->account_level;

        $pending = \App\Models\ExchangeRequest::where('status', 7)->where('user_id', $user->id)->get();
        $active = \App\Models\ExchangeRequest::where('status', 8)->where('user_id', $user->id)->get();
        $pendings = count($pending);
        $actives = count($active);
    }

    if (auth()->guest()) {
        $accountLevel = "Guest";
        $actives = 0;
        $pendings = 0;
    }
    ?>

 <script>
     Notiflix.Block.dots('#showLoader', {
         backgroundColor: loaderColor,
     });
     getExchangeCurrency();
     var activeTab = "exchange";
     var activeSendCurrency = "";
     var activeGetCurrency = "";

     $(document).on("keyup", "input[name='exchangeSendAmount']", function() {
         let sendAmount = $("input[name='exchangeSendAmount']").val();
         getCalculation(sendAmount);
     });

     $(document).on("change", "select[name='exchangeSendCurrency']", function() {
         let sendAmount = $("input[name='exchangeSendAmount']").val();
         getCalculation(sendAmount);
     });

     $(document).on("keyup", "input[name='exchangeGetAmount']", function() {
         let getAmount = $("input[name='exchangeGetAmount']").val();
         sendCalculation(getAmount);
     });

     $(document).on("change", "select[name='exchangeGetCurrency']", function() {
         let sendAmount = $("input[name='exchangeSendAmount']").val();
         getCalculation(sendAmount);
     });

     $(document).on("click", "#swapBtn", function() {
         let sendAmount = $("input[name='exchangeGetAmount']").val();
         $("input[name='exchangeSendAmount']").val(sendAmount);
         getCalculation(sendAmount);
     });

     $(document).on("click", ".sendModal", function() {
         activeSendCurrency = $(this).data('res');
         setSendCurrency(activeSendCurrency);
         let sendAmount = $("input[name='exchangeSendAmount']").val();
         getCalculation(sendAmount);
         $('#calculator-modal').modal('hide');

         $('.sendModal .right-side').empty();
         $(this).find('.right-side').html('');
         $(this).find('.right-side').html('<i class="fa-sharp fa-solid fa-circle-check"></i>');
     });

     $(document).on("click", ".getModal", function() {
         activeGetCurrency = $(this).data('res');
         setGetCurrency(activeGetCurrency);
         let sendAmount = $("input[name='exchangeSendAmount']").val();
         getCalculation(sendAmount);
         $('#calculator-modal2').modal('hide');

         $('.getModal .right-side').empty();
         $(this).find('.right-side').html('');
         $(this).find('.right-side').html('<i class="fa-sharp fa-solid fa-circle-check"></i>');
     });

     function getCalculation(sendAmount) {
         $("#exchangeMessage").text('');
         $("#submitBtn").attr('disabled', false);

         let accountLevel = <?php echo json_encode($accountLevel); ?>;
         if (!accountLevel) {
             console.error("Account level is not set.");
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text("Contact support.");
             return;
         }

         const levels = {
             "Guest": {
                 minSend: 2,
                 maxSend: 500,
                 ratex: 10,
                 maxActive: 100,
                 maxPending: 2
             },
             "Starter": {
                 minSend: 2,
                 maxSend: 10,
                 ratex: 10,
                 maxActive: 1,
                 maxPending: 2
             },
             "Basic": {
                 minSend: 5,
                 maxSend: 25,
                 ratex: 10.5,
                 maxActive: 2,
                 maxPending: 2
             },
             "Advanced": {
                 minSend: 10,
                 maxSend: 200,
                 ratex: 11,
                 maxActive: 3,
                 maxPending: 2
             },
             "Pro": {
                 minSend: 10,
                 maxSend: 500,
                 ratex: 12,
                 maxActive: 5,
                 maxPending: 2
             }
         };
         const levelData = levels[accountLevel];
         if (!levelData) {
             console.error("Unknown account level: " + accountLevel);
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text("Invalid account level.");
             return;
         }

         const {
             minSend,
             maxSend,
             ratex,
             maxActive,
             maxPending
         } = levelData;
         let sendMinLimit = minSend;
         let sendMaxLimit = maxSend;
         let sendMaxActive = maxActive;
         let sendMaxPending = maxPending;
         let sendCode = activeSendCurrency.code;
         let sendUsdRate = ratex;
         let getUsdRate = activeGetCurrency.usd_rate;
         let getAmount = getAmountCal(sendAmount, sendUsdRate, getUsdRate);
         $("input[name='exchangeGetAmount']").val(getAmount);

         if (parseFloat(sendAmount) < parseFloat(sendMinLimit)) {
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text(`Min. is ${sendMinLimit} ${sendCode}`);
         }

         if (parseFloat(sendAmount) > parseFloat(sendMaxLimit)) {
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text(`Max. is ${sendMaxLimit} ${sendCode}`);
         }

         let actives = <?php echo json_encode($actives); ?>;
         let pendings = <?php echo json_encode($pendings); ?>;

         if (actives >= sendMaxActive) {
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text(`Max. active exchange is ${sendMaxActive}`);
         }

         if (pendings >= sendMaxPending) {
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text(`Max. pending exchange is ${sendMaxPending}`);
         }
     }

     function getAmountCal(sendAmount, sendUsdRate, getUsdRate) {

         if (activeTab == 'exchange') {
             return (sendAmount * sendUsdRate / getUsdRate).toFixed(8);
         } else if (activeTab == 'buy') {
             return (sendAmount * sendUsdRate / getUsdRate).toFixed(8);
         } else if (activeTab == 'sell') {
             return (sendAmount * sendUsdRate / getUsdRate).toFixed(2);
         }
     }

     function sendCalculation(getAmount) {
         $("#exchangeMessage").text('');
         $("#submitBtn").attr('disabled', false);

         let accountLevel = <?php echo json_encode($accountLevel); ?>;
         if (!accountLevel) {
             console.error("Account level is not set.");
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text("Contact support.");
             return;
         }

         const levels = {
             "Guest": {
                 minSend: 2,
                 maxSend: 500,
                 ratex: 10,
                 maxActive: 100,
                 maxPending: 2
             },
             "Starter": {
                 minSend: 2,
                 maxSend: 10,
                 ratex: 10,
                 maxActive: 1,
                 maxPending: 2
             },
             "Basic": {
                 minSend: 5,
                 maxSend: 25,
                 ratex: 10.5,
                 maxActive: 2,
                 maxPending: 2
             },
             "Advanced": {
                 minSend: 10,
                 maxSend: 200,
                 ratex: 11,
                 maxActive: 3,
                 maxPending: 2
             },
             "Pro": {
                 minSend: 10,
                 maxSend: 500,
                 ratex: 12,
                 maxActive: 5,
                 maxPending: 2
             }
         };
         const levelData = levels[accountLevel];
         if (!levelData) {
             console.error("Unknown account level: " + accountLevel);
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text("Invalid account level.");
             return;
         }

         const {
             minSend,
             maxSend,
             ratex,
             maxActive,
             maxPending
         } = levelData;
         let sendMinLimit = minSend;
         let sendMaxLimit = maxSend;
         let sendMaxActive = maxActive;
         let sendMaxPending = maxPending;
         let sendCode = activeSendCurrency.code;
         let sendUsdRate = ratex;
         let getUsdRate = activeGetCurrency.usd_rate;
         let sendAmount = sendAmountCal(getAmount, sendUsdRate, getUsdRate);
         $("input[name='exchangeSendAmount']").val(sendAmount);

         if (parseFloat(sendAmount) < parseFloat(sendMinLimit)) {
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text(`Min. is ${sendMinLimit} ${sendCode}`);
         }

         if (parseFloat(sendAmount) > parseFloat(sendMaxLimit)) {
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text(`Max. is ${sendMaxLimit} ${sendCode}`);
         }

         let actives = <?php echo json_encode($actives); ?>;
         let pendings = <?php echo json_encode($pendings); ?>;

         if (actives >= sendMaxActive) {
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text(`Max. active exchange is ${sendMaxActive}`);
         }

         if (pendings >= sendMaxPending) {
             $("#submitBtn").attr('disabled', true);
             $("#exchangeMessage").text(`Max. pending exchange is ${sendMaxPending}`);
         }
     }

     function sendAmountCal(getAmount, sendUsdRate, getUsdRate) {
         if (activeTab == 'exchange') {
             return (getAmount * getUsdRate / sendUsdRate).toFixed(8);
         } else if (activeTab == 'buy') {
             return (getAmount * getUsdRate / sendUsdRate).toFixed(2);
         } else if (activeTab == 'sell') {
             return (getAmount * getUsdRate / sendUsdRate).toFixed(8);
         }
     }


     function getExchangeCurrency(route = "{{route("getExchangeCurrency")}}") {
        axios.get(route)
            .then(function (response) {
                Notiflix.Block.remove('#showLoader');
                activeSendCurrency = response.data.selectedSendCurrency;
                activeGetCurrency = response.data.selectedGetCurrency;
                setSendCurrency(activeSendCurrency);
                setGetCurrency(activeGetCurrency);
                showSend(response.data.sendCurrencies);
                showGet(response.data.getCurrencies);
                $("input[name='exchangeSendAmount']").val((response.data.initialSendAmount).toFixed(2));
                getCalculation(response.data.initialSendAmount);
            })
            .catch(function (error) {

            });
    }

     function showSend(currencies) {
         $('#show-send').html(``);
         let options = "";
         for (let i = 0; i < currencies.length; i++) {
             let isChecked = (i === 0) ? '<i class="fa-sharp fa-solid fa-circle-check"></i>' : '';
             options += `<div class="item sendModal" data-res='${JSON.stringify(currencies[i])}'>
                        <div class="left-side">
                            <div class="img-area">
                                <img class="img-flag" src="${currencies[i].image_path}" alt="...">
                            </div>
                            <div class="text-area">
                                <div class="title">${currencies[i].code}</div>
                                <div class="sub-title">${currencies[i].name}</div>
                            </div>
                        </div>
                        <div class="right-side">${isChecked}</div>
                    </div>`;
         }
         $('#show-send').append(options);
     }


     function setSendCurrency(currency) {
         $('#showSendImage').attr('src', currency.image_path);
         $('#showSendCode').text(currency.code);
         $('#showSendName').text(currency.name);

         $('input[name="exchangeSendCurrency"]').val(currency.id);
     }

     function setGetCurrency(currency) {
         $('#showGetImage').attr('src', currency.image_path);
         $('#showGetCode').text(currency.code);
         $('#showGetName').text(currency.name);

         $('input[name="exchangeGetCurrency"]').val(currency.id);
     }

     function showGet(currencies) {
         $('#show-get').html(``);
         let options = "";
         for (let i = 0; i < currencies.length; i++) {
             let isChecked = (i === 0) ? '<i class="fa-sharp fa-solid fa-circle-check"></i>' : '';
             options += `<div class="item getModal" data-res='${JSON.stringify(currencies[i])}'>
                        <div class="left-side">
                            <div class="img-area">
                                <img class="img-flag" src="${currencies[i].image_path}" alt="...">
                            </div>
                            <div class="text-area">
                                <div class="title">${currencies[i].code}</div>
                                <div class="sub-title">${currencies[i].name}</div>
                            </div>
                        </div>
                        <div class="right-side">${isChecked}</div>
                    </div>`;
         }
         $('#show-get').append(options);
     }


     $(document).on("click", "#pills-exchange-tab", function() {
         Notiflix.Block.dots('#showLoader', {
             backgroundColor: loaderColor,
         });

         let formSubmitRoute = "{{route('exchangeRequest')}}";
         $("#submitFormId").attr("action", formSubmitRoute);

         activeTab = 'exchange';
         let route = "{{route("
         getExchangeCurrency ")}}";
         getExchangeCurrency(route);
         $("#submitBtn").text("Exchange Now");
     });

     $(document).on("click", "#pills-Buy-tab", function() {
         Notiflix.Block.dots('#showLoader', {
             backgroundColor: loaderColor,
         });

         let formSubmitRoute = "{{route('buyRequest')}}";
         $("#submitFormId").attr("action", formSubmitRoute);

         activeTab = 'buy';
         let route = "{{route("
         getBuyCurrency ")}}";
         getExchangeCurrency(route);
         $("#submitBtn").text("Buy Now");
     });

     $(document).on("click", "#pills-Sell-tab", function() {
         Notiflix.Block.dots('#showLoader', {
             backgroundColor: loaderColor,
         });

         let formSubmitRoute = "{{route('sellRequest')}}";
         $("#submitFormId").attr("action", formSubmitRoute);

         activeTab = 'sell';
         let route = "{{route("
         getSellCurrency ")}}";
         getExchangeCurrency(route);
         $("#submitBtn").text("Sell Now");
     });

     $(document).ready(function() {

         $('.autoplay').slick({
             slidesToShow: 1,
             slidesToScroll: 1,
             fade: true,
             autoplay: true,
             arrows: false,
             autoplaySpeed: 2000,
         });
     });

     $(document).on("click", ".announceClass", function() {
         let announceBodyShow = $('#announceBodyShow');
         announceBodyShow.html('');
         let heading = $(this).data('heading');
         let des = $(this).data('des');
         announceBodyShow.html(`<h4>${heading}</h4> ${des}`)
     });
 </script>