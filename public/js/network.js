const selectedCoin = document.getElementById('selectedCoin');
const coinDropdown = document.getElementById('coinDropdown');
const searchCoin = document.getElementById('searchCoin');
const coinItems = document.querySelectorAll('.coin-item');
const networkSection = document.getElementById('networkSection');
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

function updateWallets(network) {
    walletsContainer.innerHTML = '';
    // Find selected coin symbol
    const coin = document.getElementById('assetCoin')?.textContent?.trim();
    const selected = document.querySelector('.network-btn.active');
    const selectedNet = selected ? selected.textContent.trim() : network;
    fetchAssets().then(list => {
        const asset = list.find(a => a.symbol === coin);
        const net = (asset?.networks || []).find(n => n.name === selectedNet);
        if (!net) {
            walletsContainer.innerHTML = '<p class="text-muted">No wallets available for this network.</p>';
            return;
        }

        const walletDiv = document.createElement('div');
        walletDiv.classList.add('wallet-item');

        const qrImg = document.createElement('img');
        qrImg.src = net.qr_path || '/img/qr/qr-code.png';
        qrImg.alt = (net.name || 'Wallet') + ' QR Code';
        qrImg.style.width = '100px';
        qrImg.style.height = '100px';
        qrImg.style.objectFit = 'contain';

        const infoDiv = document.createElement('div');
        infoDiv.classList.add('wallet-info');
        infoDiv.innerHTML = `<strong>${net.name}</strong><br><span class="wallet-address">${net.deposit_address || ''}</span>`;

        const copyBtn = document.createElement('button');
        copyBtn.classList.add('copy-btn');
        copyBtn.title = 'Copy wallet address';
        copyBtn.innerHTML = '<i class="ri-file-copy-line"></i>';

        const tooltip = document.createElement('span');
        tooltip.classList.add('copy-tooltip');
        tooltip.textContent = 'Copied!';
        copyBtn.appendChild(tooltip);

        copyBtn.addEventListener('click', () => {
            const addr = net.deposit_address || '';
            if (!addr) { alert('No address'); return; }
            navigator.clipboard.writeText(addr).then(() => {
                tooltip.classList.add('show');
                setTimeout(() => { tooltip.classList.remove('show'); }, 1500);
            }).catch(() => { alert('Failed to copy address'); });
        });

        walletDiv.appendChild(qrImg);
        walletDiv.appendChild(infoDiv);
        walletDiv.appendChild(copyBtn);
        walletsContainer.appendChild(walletDiv);
    }).catch(() => {
        walletsContainer.innerHTML = '<p class="text-danger">Failed to load wallets.</p>';
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

    // Deposit submission
    const submitBtn = document.getElementById('submitDeposit');
    if (submitBtn) {
        submitBtn.addEventListener('click', async () => {
            const amountEl = document.getElementById('depositAmount');
            const addrEl = document.getElementById('depositAddress');
            const hashEl = document.getElementById('depositTxHash');
            const successEl = document.getElementById('depositSuccess');
            const errorEl = document.getElementById('depositError');
            errorEl.classList.add('d-none');
            successEl.classList.add('d-none');

            const amount = amountEl?.value?.trim();
            if (!amount || Number(amount) <= 0) {
                errorEl.textContent = 'Please enter a valid amount';
                errorEl.classList.remove('d-none');
                return;
            }
            const selected = document.querySelector('.network-btn.active');
            const assetSymbol = document.getElementById('assetCoin')?.textContent?.trim();
            if (!selected || !assetSymbol) {
                errorEl.textContent = 'Select coin and network';
                errorEl.classList.remove('d-none');
                return;
            }
            try {
                const list = await fetchAssets();
                const asset = list.find(a => a.symbol === assetSymbol);
                const net = asset?.networks?.find(n => n.name === selected.textContent.trim());
                const body = new URLSearchParams();
                body.set('asset_id', asset?.id ?? '');
                if (net) body.set('asset_network_id', net.id);
                body.set('amount', amount);
                if (addrEl?.value) body.set('address', addrEl.value.trim());
                if (hashEl?.value) body.set('tx_hash', hashEl.value.trim());
                const res = await fetch('/api/deposits', {
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
                    errorEl.textContent = 'Failed to submit deposit: ' + (err.message || 'Validation error');
                    errorEl.classList.remove('d-none');
                    return;
                }
                successEl.classList.remove('d-none');
                amountEl.value = '';
                if (addrEl) addrEl.value = '';
                if (hashEl) hashEl.value = '';
            } catch (e) {
                errorEl.textContent = 'Request failed';
                errorEl.classList.remove('d-none');
            }
        });
    }
});
