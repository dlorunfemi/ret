const selectedCoin = document.getElementById('selectedCoin');
const coinDropdown = document.getElementById('coinDropdown');
const searchCoin = document.getElementById('searchCoin');
const coinItems = document.querySelectorAll('.coin-item');
const networkContainer = document.getElementById('networkContainer');
const networkLoader = document.getElementById('networkLoader');
const assetCoin = document.getElementById('assetCoin');
const assetCoin2 = document.getElementById('assetCoin2');
const walletsContainer = document.getElementById('walletsContainer');
const walletLoader = document.getElementById('walletLoader');

// Attention panel fields
const minDepositEl = document.getElementById('minDeposit');
const depositConfEl = document.getElementById('depositConf');
const withdrawalConfEl = document.getElementById('withdrawalConf');

let assetsCache = [];
async function fetchAssets() {
    if (assetsCache.length) return assetsCache;
    const res = await fetch('/api/assets', { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('Failed to load assets');
    const json = await res.json();
    assetsCache = json.data || [];
    return assetsCache;
}

// Replace wallets section with input + button
function updateWallets(network) {
    walletsContainer.innerHTML = '';

    const inputDiv = document.createElement('div');
    inputDiv.classList.add('wallet-input-container');

    const amountInput = document.createElement('input');
    amountInput.type = 'number';
    amountInput.step = 'any';
    amountInput.min = '0';
    amountInput.classList.add('form-control');
    amountInput.id = 'withdrawAmount';
    amountInput.placeholder = 'Enter amount to withdraw';

    const walletInput = document.createElement('input');
    walletInput.type = 'text';
    walletInput.classList.add('form-control');
    walletInput.id = 'manualWallet';
    walletInput.placeholder = 'Enter wallet address';

    const submitBtn = document.createElement('button');
    submitBtn.classList.add('btn', 'btn-primary');
    submitBtn.id = 'submitWallet';
    submitBtn.textContent = 'Submit Withdrawal';

    const statusDiv = document.createElement('div');
    statusDiv.id = 'withdrawStatus';
    statusDiv.classList.add('small', 'mt-2');

    inputDiv.appendChild(amountInput);
    inputDiv.appendChild(walletInput);
    inputDiv.appendChild(submitBtn);
    inputDiv.appendChild(statusDiv);

    walletsContainer.appendChild(inputDiv);

    submitBtn.addEventListener('click', async () => {
        const address = walletInput.value.trim();
        const amount = amountInput.value.trim();
        if (!amount || Number(amount) <= 0) { alert('Please enter a valid amount'); return; }
        if (!address) { alert('Please enter a wallet address'); return; }
        const selected = document.querySelector('.network-btn.active');
        const assetSymbol = document.getElementById('assetCoin').textContent.trim();
        if (!selected || !assetSymbol) { alert('Select coin and network'); return; }
        try {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing... (about 20s)';
            statusDiv.textContent = 'Submitting withdrawal...';
            statusDiv.classList.remove('text-danger');
            statusDiv.classList.add('text-muted');
            const list = await fetchAssets();
            const asset = list.find(a => a.symbol === assetSymbol);
            const net = asset?.networks?.find(n => n.name === selected.textContent.trim());
            const body = new URLSearchParams();
            body.set('asset_id', asset?.id ?? '');
            if (net) body.set('asset_network_id', net.id);
            body.set('amount', amount);
            body.set('address', address);
            const res = await fetch('/api/withdrawals', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: body.toString(),
            });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                statusDiv.textContent = 'Withdrawal failed: ' + (err.message || 'Validation error');
                statusDiv.classList.remove('text-muted');
                statusDiv.classList.add('text-danger');
                return;
            }
            const data = await res.json().catch(() => ({}));
            const status = data?.data?.status;
            if (status === 'failed') {
                statusDiv.textContent = 'Processed: Withdrawal failed. Please check your email.';
                statusDiv.classList.remove('text-muted');
                statusDiv.classList.add('text-danger');
            } else {
                statusDiv.textContent = 'Withdrawal submitted.';
                statusDiv.classList.remove('text-danger');
                statusDiv.classList.add('text-muted');
            }
        } catch (e) {
            statusDiv.textContent = 'Request failed';
            statusDiv.classList.remove('text-muted');
            statusDiv.classList.add('text-danger');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Withdrawal';
        }
    });
}

// Show loader while fetching networks
function loadNetworks(coin) {
    networkContainer.innerHTML = '';
    networkLoader.style.display = 'block';

    fetchAssets().then(list => {
        const asset = list.find(a => a.symbol === coin);
        networkLoader.style.display = 'none';
        if (!asset || !(asset.networks || []).length) {
            networkContainer.innerHTML = '<div class="text-muted">No networks for this asset.</div>';
            return;
        }
        asset.networks.forEach(net => {
            const col = document.createElement('div');
            col.classList.add('col-6');
            const btn = document.createElement('div');
            btn.classList.add('network-btn');
            btn.textContent = net.name;
            btn.addEventListener('click', () => {
                document.querySelectorAll('.network-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                minDepositEl.textContent = net.min_deposit ?? '';
                depositConfEl.textContent = (net.deposit_confirmations ?? '') + (net.deposit_confirmations ? ' Confirmation(s)' : '');
                withdrawalConfEl.textContent = (net.withdraw_confirmations ?? '') + (net.withdraw_confirmations ? ' Confirmation(s)' : '');

                walletsContainer.innerHTML = '';
                walletLoader.style.display = 'block';

                setTimeout(() => {
                    walletLoader.style.display = 'none';
                    updateWallets(net.name);
                }, 400);
            });
            col.appendChild(btn);
            networkContainer.appendChild(col);
        });
        const firstNetworkBtn = networkContainer.querySelector('.network-btn');
        if (firstNetworkBtn) firstNetworkBtn.click();
    }).catch(() => {
        networkLoader.style.display = 'none';
        networkContainer.innerHTML = '<div class="text-danger">Failed to load networks.</div>';
    });
}

selectedCoin.addEventListener('click', () => {
    coinDropdown.style.display = coinDropdown.style.display === 'block' ? 'none' : 'block';
});

coinItems.forEach(item => {
    item.addEventListener('click', () => {
        const coin = item.dataset.coin;
        selectedCoin.querySelector('span').textContent = coin;
        assetCoin.textContent = coin;
        assetCoin2.textContent = coin;
        coinDropdown.style.display = 'none';
        loadNetworks(coin);
    });
});

// Handle History and Popular Coins badges
document.querySelectorAll('.coin-badge').forEach(badge => {
    badge.addEventListener('click', () => {
        const coin = badge.textContent.trim();
        selectedCoin.querySelector('span').textContent = coin;
        assetCoin.textContent = coin;
        assetCoin2.textContent = coin;
        coinDropdown.style.display = 'none';
        loadNetworks(coin);
    });
});

// Search coins
searchCoin.addEventListener('input', () => {
    const filter = searchCoin.value.toLowerCase();
    document.querySelectorAll('#coinList .coin-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(filter) ? 'flex' : 'none';
    });
});

// Click outside dropdown closes it
document.addEventListener('click', e => {
    if (!selectedCoin.contains(e.target) && !coinDropdown.contains(e.target)) {
        coinDropdown.style.display = 'none';
    }
});

// On page load, default to USDT
document.addEventListener('DOMContentLoaded', () => {
    selectedCoin.querySelector('span').textContent = 'USDT';
    assetCoin.textContent = 'USDT';
    assetCoin2.textContent = 'USDT';
    loadNetworks('USDT');
    // Populate coin list from API
    fetchAssets().then(list => {
        const coinList = document.getElementById('coinList');
        if (!coinList) return;
        coinList.innerHTML = '';
        const iconMap = { USDT: 'tether', BTC: 'btc', ETH: 'eth', USDC: 'usdc' };
        list.forEach(a => {
            const item = document.createElement('div');
            item.classList.add('coin-item');
            item.dataset.coin = a.symbol;
            const img = document.createElement('img');
            const icon = iconMap[a.symbol];
            if (icon) img.src = `/img/icon/${icon}.png`;
            img.alt = a.symbol;
            item.appendChild(img);
            const text = document.createTextNode(` ${a.symbol} - ${a.name}`);
            item.appendChild(text);
            item.addEventListener('click', () => {
                selectedCoin.querySelector('span').textContent = a.symbol;
                assetCoin.textContent = a.symbol;
                assetCoin2.textContent = a.symbol;
                coinDropdown.style.display = 'none';
                loadNetworks(a.symbol);
            });
            coinList.appendChild(item);
        });
    }).catch(() => { });
});
