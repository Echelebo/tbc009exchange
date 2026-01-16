<!-- sidebar wallet starts -->

<div class="custom-sidebar">
    <div class="wallet-box">
        <h4 class="mb-20">Details</h4>
        <div class="d-flex justify-content-between gap-4 mb-30">
            <div>
                <h5 class="mb-0 accountLevel"></h5>
                <small>Account Level</small>
            </div>
            <div class="text-end">
                <h5 class="mb-0">$<span class="userBalance"></span></h5>
                <small>Total Balance</small>
            </div>
        </div>
        <div class="wallet-item-container" id="showAssetsBalance">
            <div class="wallet-item">
                <div class="middle-side">
                    <div class="icon-wrapper">
                    <i class="fa-regular fa-coins"></i>
                </div>

                    <div>
                        <h5 class="mb-0">Tot. Return</h5>
                    </div>
                </div>
                <div class="right-side">
                    <h5 class="mb-0">$<span class="totalReturned"></span></h5>
                </div>
            </div>
            <div class="wallet-item">

                <div class="middle-side">
                    <div class="icon-wrapper">
                    <i class="fa-regular fa-coins"></i>
                </div>
                    <div>
                        <h5 class="mb-0">PNL</h5>
                        <small>(return + stake)</small>
                    </div>
                </div>
                <div class="right-side">
                    <div class="right-side">
                    <h5 class="mb-0">$<span class="totalOutcome"></span></h5>
                </div>
                </div>
            </div>
            <div class="wallet-item">
                <div class="middle-side">
                    <div class="icon-wrapper">
                    <i class="fa-regular fa-coins"></i>
                </div>
                    <div>
                        <h5 class="mb-0">Ref. Bonus</h5>
                    </div>
                </div>
                <div class="right-side">
                    <h5 class="mb-0">$<span class="totalBonus"></span></h5>
                </div>
            </div>
            <div class="wallet-item">
                <div class="middle-side">
                    <div class="icon-wrapper">
                    <i class="fa-regular fa-coins"></i>
                </div>
                    <div>
                        <h5 class="mb-0">Referrals</h5>
                    </div>
                </div>
                <div class="right-side">
                    <h5 class="mb-0"><span class="totalReferrals"></span></h5>
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

        axios.get("{{route('user.getWallets')}}")
            .then(function (res) {
                $('.accountLevel').text(res.data.accountLevel);
                $('.userBalance').text(res.data.userBalance);
                $('.totalReturned').text(res.data.totalReturned);
                $('.totalOutcome').text(res.data.totalOutcome);
                $('.totalBonus').text(res.data.totalBonus);
                $('.totalReferrals').text(res.data.totalReferrals);
            })
    };
</script>
<!-- sidebar wallet ends -->
