<!-- sidebar wallet starts -->

<div class="custom-sidebar">
    <div class="wallet-box">
        <h4 class="mb-20">Assets</h4>
        <div class="d-flex justify-content-between gap-4 mb-30">
            <div>
                <h5 class="mb-0" id="accountLevel"></h5>
                <small>Account Level</small>
            </div>
            <div class="text-end">
                $<h5 class="mb-0" id="userBalance"></h5>
                <small>Total Balance</small>
            </div>
        </div>
        <div class="wallet-item-container" id="showAssetsBalance">
            <div class="wallet-item">
                <div class="left-side">
                    <a href="javascript:void(0)" class="deposit-btn" title="Return"><i class="fa-regular fa-arrow-up"></i></a>
                </div>
                <div class="middle-side">
                    <div class="icon-wrapper">
                    <i class="fa-regular fa-coins"></i>
                </div>

                    <div>
                        <h5 class="mb-0">Return</h5>
                        <small>Total Returned</small>
                    </div>
                </div>
                <div class="right-side">
                    <h5 class="mb-0">0</h5>
                    <small>$0</small>
                </div>
            </div>
            <div class="wallet-item">
                <div class="left-side">
                    <a href="javascript:void(0)" class="deposit-btn" title="Deposit"><i class="fa-regular fa-arrow-up"></i></a>
                </div>
                <div class="middle-side">
                    <div class="icon-wrapper">
                    <i class="fa-regular fa-coins"></i>
                </div>
                    <div>
                        <h5 class="mb-0">Total Outcome</h5>
                        <small>(return + stake)</small>
                    </div>
                </div>
                <div class="right-side">
                    <h5 class="mb-0">0</h5>
                    <small>$0</small>
                </div>
            </div>
            <div class="wallet-item">
                <div class="left-side">
                    <a href="javascript:void(0)" class="deposit-btn" title="Deposit"><i class="fa-regular fa-arrow-up"></i></a>
                </div>
                <div class="middle-side">
                    <div class="icon-wrapper">
                    <i class="fa-regular fa-coins"></i>
                </div>
                    <div>
                        <h5 class="mb-0">Referral</h5>
                        <small>Referral Bonus</small>
                    </div>
                </div>
                <div class="right-side">
                    <h5 class="mb-0">0</h5>
                    <small>$0</small>
                </div>
            </div>
            <div class="wallet-item">
                <div class="left-side">
                    <a href="javascript:void(0)" class="deposit-btn" title="Deposit"><i class="fa-regular fa-arrow-up"></i></a>
                </div>
                <div class="middle-side">
                    <div class="icon-wrapper">
                    <i class="fa-regular fa-coins"></i>
                </div>
                    <div>
                        <h5 class="mb-0">Referral</h5>
                        <small>Total Referrals</small>
                    </div>
                </div>
                <div class="right-side">
                    <h5 class="mb-0">0</h5>
                    <small>$0</small>
                </div>
            </div>
            </div>
    </div>

            <button type="button" class="custom-toggle-sidebar-btn" id="showAssetsBtn">
            <i class="fa-regular fa-wallet"></i>
        </button>
    </div>


	<script>
    'use strict';
    window.onload = function () {
        var totalUsdValue = 0;
        var route = "https://coinectra.bugfinder.app/user/deposit";

        async function fetchData() {
            try {
                const response = await axios.get('https://coinectra.bugfinder.app/get/assets/balance');
                if (response.data.status === 'success') {
                    let wallets = response.data.wallets;
                    let html = "";

                    wallets.forEach(wallet => {
                        let walletRoute = route + '/' + wallet.crypto_currency.code;
                        html += `<div class="wallet-item">
                <div class="left-side">
                    <a href="${walletRoute}" class="deposit-btn" title="Deposit"
                    ><i class="fa-regular fa-arrow-up"></i></a>
                </div>
                <div class="middle-side">
                    <div class="img-box">
                        <img src="${wallet.crypto_currency.image_path}" alt="..."/>
                    </div>
                    <div>
                        <h5 class="mb-0">${wallet.crypto_currency.code}</h5>
                        <small>${wallet.crypto_currency.currency_name}</small>
                    </div>
                </div>
                <div class="right-side">
                    <h5 class="mb-0">${wallet.balance}</h5>
                    <small>$${dollarEquivalent(wallet.balance, wallet.crypto_currency.usd_rate)}</small>
                </div>
            </div>`;
                    });

                    $('#showAssetsBalance').html(html);
                    totalUsdCount();
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        function dollarEquivalent(amount, rate) {
            if (!amount || !rate) return "0";
            let usdValue = (parseFloat(amount) * parseFloat(rate)).toFixed(2);
            totalUsdValue += parseFloat(usdValue);

            return usdValue;
        }

        function totalUsdCount() {
            $('#totalUsdValue').text(`$${(totalUsdValue).toFixed(2)}`)
        }

        fetchData();
    };
</script>

<!-- sidebar wallet ends -->
