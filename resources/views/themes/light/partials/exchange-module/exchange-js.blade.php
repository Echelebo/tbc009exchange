<script>
    Notiflix.Block.dots('#showLoader', {
        backgroundColor: loaderColor,
    });
    getExchangeCurrency();
    var activeTab = "exchange";
    var activeSendCurrency = "";
    var activeGetCurrency = "";

    $(document).on("keyup", "input[name='exchangeSendAmount']", function () {
        let sendAmount = $("input[name='exchangeSendAmount']").val();
        getCalculation(sendAmount);
    });

    $(document).on("change", "select[name='exchangeSendCurrency']", function () {
        let sendAmount = $("input[name='exchangeSendAmount']").val();
        getCalculation(sendAmount);
    });

    $(document).on("keyup", "input[name='exchangeGetAmount']", function () {
        let getAmount = $("input[name='exchangeGetAmount']").val();
        sendCalculation(getAmount);
    });

    $(document).on("change", "select[name='exchangeGetCurrency']", function () {
        let sendAmount = $("input[name='exchangeSendAmount']").val();
        getCalculation(sendAmount);
    });

    $(document).on("click", "#swapBtn", function () {
        let sendAmount = $("input[name='exchangeGetAmount']").val();
        $("input[name='exchangeSendAmount']").val(sendAmount);
        getCalculation(sendAmount);
    });

    $(document).on("click", ".sendModal", function () {
        activeSendCurrency = $(this).data('res');
        setSendCurrency(activeSendCurrency);
        let sendAmount = $("input[name='exchangeSendAmount']").val();
        getCalculation(sendAmount);
        $('#calculator-modal').modal('hide');

        $('.sendModal .right-side').empty();
        $(this).find('.right-side').html('');
        $(this).find('.right-side').html('<i class="fa-sharp fa-solid fa-circle-check"></i>');
    });

    $(document).on("click", ".getModal", function () {
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

        let sendMinLimit = activeSendCurrency.min_send;
        let sendMaxLimit = activeSendCurrency.max_send;
        let sendCode = activeSendCurrency.code;
        let sendUsdRate = activeSendCurrency.usd_rate;
        let getUsdRate = activeGetCurrency.usd_rate;
        let getAmount = getAmountCal(sendAmount, sendUsdRate, getUsdRate);
        $("input[name='exchangeGetAmount']").val(getAmount);

        if (parseFloat(sendAmount) < parseFloat(sendMinLimit)) {
            $("#submitBtn").attr('disabled', true);
            $("#exchangeMessage").text(`Min is ${sendMinLimit} ${sendCode}`);
        }

        if (parseFloat(sendAmount) > parseFloat(sendMaxLimit)) {
            $("#submitBtn").attr('disabled', true);
            $("#exchangeMessage").text(`Max is ${sendMaxLimit} ${sendCode}`);
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

        let sendMinLimit = activeSendCurrency.min_send;
        let sendMaxLimit = activeSendCurrency.max_send;
        let sendCode = activeSendCurrency.code;
        let sendUsdRate = activeSendCurrency.usd_rate;
        let getUsdRate = activeGetCurrency.usd_rate;
        let sendAmount = sendAmountCal(getAmount, sendUsdRate, getUsdRate);
        $("input[name='exchangeSendAmount']").val(sendAmount);

        if (parseFloat(sendAmount) < parseFloat(sendMinLimit)) {
            $("#submitBtn").attr('disabled', true);
            $("#exchangeMessage").text(`Min is ${sendMinLimit} ${sendCode}`);
        }

        if (parseFloat(sendAmount) > parseFloat(sendMaxLimit)) {
            $("#submitBtn").attr('disabled', true);
            $("#exchangeMessage").text(`Max is ${sendMaxLimit} ${sendCode}`);
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


    $(document).on("click", "#pills-exchange-tab", function () {
        Notiflix.Block.dots('#showLoader', {
            backgroundColor: loaderColor,
        });

        let formSubmitRoute = "{{route('exchangeRequest')}}";
        $("#submitFormId").attr("action", formSubmitRoute);

        activeTab = 'exchange';
        let route = "{{route("getExchangeCurrency")}}";
        getExchangeCurrency(route);
        $("#submitBtn").text("Exchange Now");
    });

    $(document).on("click", "#pills-Buy-tab", function () {
        Notiflix.Block.dots('#showLoader', {
            backgroundColor: loaderColor,
        });

        let formSubmitRoute = "{{route('buyRequest')}}";
        $("#submitFormId").attr("action", formSubmitRoute);

        activeTab = 'buy';
        let route = "{{route("getBuyCurrency")}}";
        getExchangeCurrency(route);
        $("#submitBtn").text("Buy Now");
    });

    $(document).on("click", "#pills-Sell-tab", function () {
        Notiflix.Block.dots('#showLoader', {
            backgroundColor: loaderColor,
        });

        let formSubmitRoute = "{{route('sellRequest')}}";
        $("#submitFormId").attr("action", formSubmitRoute);

        activeTab = 'sell';
        let route = "{{route("getSellCurrency")}}";
        getExchangeCurrency(route);
        $("#submitBtn").text("Sell Now");
    });

    $(document).ready(function () {

        $('.autoplay').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            autoplay: true,
            arrows: false,
            autoplaySpeed: 2000,
        });
    });

    $(document).on("click", ".announceClass", function () {
        let announceBodyShow = $('#announceBodyShow');
        announceBodyShow.html('');
        let heading = $(this).data('heading');
        let des = $(this).data('des');
        announceBodyShow.html(`<h4>${heading}</h4> ${des}`)
    });

</script>
