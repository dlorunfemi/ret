const selectedCoin = document.getElementById('selectedCoin');
const coinDropdown = document.getElementById('coinDropdown');
const searchCoin = document.getElementById('searchCoin');
const coinItems = document.querySelectorAll('.coin-item');
const networkSection = document.getElementById('networkSection');
const networkContainer = document.getElementById('networkContainer');
const networkLoader = document.getElementById('networkLoader');
const assetCoin = document.getElementById('assetCoin');
const assetCoin2 = document.getElementById('assetCoin2');
const assetAvailableEl = document.getElementById('assetAvailable');
const assetFrozenEl = document.getElementById('assetFrozen');
const walletsContainer = document.getElementById('walletsContainer');
const walletLoader = document.getElementById('walletLoader');

// Attention panel fields
const minDepositEl = document.getElementById('minDeposit');
const depositConfEl = document.getElementById('depositConf');
const withdrawalConfEl = document.getElementById('withdrawalConf');

let assetsCache = [];
let balancesCache = [];

// Cache of recent transactions for the logged-in user
let transactionsCache = [];

async function fetchTransactions(force = false) {
    if (!force && transactionsCache.length) return transactionsCache;
    const res = await fetch('/api/transactions', { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('Failed to load transactions');
    const json = await res.json();
    transactionsCache = json.data || [];
    return transactionsCache;
}

function updateDepositButtonState() {
    const submitBtn = document.getElementById('submitDeposit');
    const buttonText = document.getElementById('buttonText');
    const buttonSpinner = document.getElementById('buttonSpinner');
    const successEl = document.getElementById('depositSuccess');
    const errorEl = document.getElementById('depositError');

    if (!submitBtn) return;

    const selected = document.querySelector('.network-btn.active');
    const assetSymbol = document.getElementById('assetCoin')?.textContent?.trim();

    // If selection is incomplete, ensure default enabled state
    if (!selected || !assetSymbol) {
        submitBtn.disabled = false;
        if (buttonText) buttonText.textContent = 'Submit Deposit';
        if (buttonSpinner) buttonSpinner.classList.add('d-none');
        if (successEl) successEl.classList.add('d-none');
        if (errorEl) errorEl.classList.add('d-none');
        return;
    }

    const selectedNetworkId = parseInt(selected.dataset.networkId || '', 10);
    const selectedNetworkName = selected.textContent.trim();

    fetchTransactions().then(list => {
        const hasPending = (list || []).some(t => {
            if (t.type !== 'deposit' || t.status !== 'pending') return false;
            if (t.asset_symbol !== assetSymbol) return false;
            if (selectedNetworkId) {
                return Number(t.asset_network_id) === selectedNetworkId;
            }
            // Fallback to name match if id is unavailable
            return (t.network_name || '') === selectedNetworkName;
        });

        if (hasPending) {
            submitBtn.disabled = true;
            if (buttonText) buttonText.textContent = 'Pending Transaction';
            if (buttonSpinner) buttonSpinner.classList.add('d-none');
            if (successEl) successEl.classList.remove('d-none');
            if (errorEl) errorEl.classList.add('d-none');
        } else {
            submitBtn.disabled = false;
            if (buttonText) buttonText.textContent = 'Submit Deposit';
            if (buttonSpinner) buttonSpinner.classList.add('d-none');
            if (successEl) successEl.classList.add('d-none');
        }
    }).catch(() => {
        // On error, leave button enabled to avoid blocking user
        submitBtn.disabled = false;
        if (buttonText) buttonText.textContent = 'Submit Deposit';
        if (buttonSpinner) buttonSpinner.classList.add('d-none');
    });
}

async function fetchAssets() {
    if (assetsCache.length) return assetsCache;
    const res = await fetch('/api/assets', { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('Failed to load assets');
    const json = await res.json();
    assetsCache = json.data || [];
    return assetsCache;
}

async function fetchBalances() {
    if (balancesCache.length) return balancesCache;
    const res = await fetch('/api/balances', { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('Failed to load balances');
    const json = await res.json();
    balancesCache = json.data || [];
    return balancesCache;
}

function formatNumber(n, decimals = 6) {
    const num = parseFloat(n || 0);
    return Number(num).toLocaleString(undefined, {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

function updatePanelForCoin(symbol) {
    if (!assetAvailableEl || !assetFrozenEl) return;
    const b = (balancesCache || []).find(x => x.asset === symbol);
    const available = b ? b.available : 0;
    const frozen = b ? b.frozen : 0;
    assetAvailableEl.textContent = formatNumber(available, 6);
    assetFrozenEl.textContent = formatNumber(frozen, 6);
}

function updateBalancesTable() {
    const priceMap = {};
    (assetsCache || []).forEach(a => { priceMap[a.symbol] = a.price_usd; });
    (balancesCache || []).forEach(b => {
        const amtEl = document.querySelector(`.amt[data-asset="${b.asset}"]`);
        const frzEl = document.querySelector(`.frozen[data-asset="${b.asset}"]`);
        const valEl = document.querySelector(`.val[data-asset="${b.asset}"]`);
        const available = parseFloat(b.available) || 0;
        const frozen = parseFloat(b.frozen) || 0;
        const price = priceMap[b.asset] || 0;
        const usd = available * price;
        if (amtEl) amtEl.textContent = formatNumber(available, 6);
        if (frzEl) frzEl.textContent = formatNumber(frozen, 6);
        if (valEl) valEl.textContent = `$${Number(usd).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    });
}

async function hydrateBalancesUI(symbol) {
    try {
        await Promise.all([fetchAssets(), fetchBalances()]);
        updateBalancesTable();
        updatePanelForCoin(symbol);
    } catch (e) {
        // silently ignore on this page
    }
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

        // walletDiv.appendChild(qrImg);
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
            btn.dataset.networkId = net.id;
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
                    // Sync button state for current coin/network
                    updateDepositButtonState();
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
        hydrateBalancesUI(coin);
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
        hydrateBalancesUI(coin);
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
    hydrateBalancesUI('USDT');
    updateDepositButtonState();
    // Periodically refresh transactions to reflect admin decisions
    setInterval(() => {
        fetchTransactions(true).then(() => updateDepositButtonState()).catch(() => { /* ignore */ });
    }, 30000);
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
                hydrateBalancesUI(a.symbol);
            });
            coinList.appendChild(item);
        });
    }).catch(() => { });

    // Deposit submission
    const submitBtn = document.getElementById('submitDeposit');
    const buttonText = document.getElementById('buttonText');
    const buttonSpinner = document.getElementById('buttonSpinner');
    const successEl = document.getElementById('depositSuccess');
    const errorEl = document.getElementById('depositError');

    if (submitBtn) {
        submitBtn.addEventListener('click', async () => {
            // Reset states
            errorEl.classList.add('d-none');
            successEl.classList.add('d-none');

            // Check if coin and network are selected
            const selected = document.querySelector('.network-btn.active');
            const assetSymbol = document.getElementById('assetCoin')?.textContent?.trim();
            if (!selected || !assetSymbol) {
                errorEl.textContent = 'Please select a coin and network first';
                errorEl.classList.remove('d-none');
                return;
            }

            // Set button to loading state
            submitBtn.disabled = true;
            buttonText.textContent = 'Waiting...';
            buttonSpinner.classList.remove('d-none');

            try {
                const list = await fetchAssets();
                const asset = list.find(a => a.symbol === assetSymbol);
                const net = asset?.networks?.find(n => n.name === selected.textContent.trim());

                const body = new URLSearchParams();
                body.set('asset_id', asset?.id ?? '');
                if (net) body.set('asset_network_id', net.id);
                body.set('amount', '1'); // Default amount since no input field

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
                    throw new Error(err.message || 'Validation error');
                }

                // Success state
                buttonText.textContent = 'Pending Transaction';
                buttonSpinner.classList.add('d-none');
                successEl.classList.remove('d-none');

                // Keep button disabled to show pending state
                try { await fetchTransactions(true); } catch (_) { }

            } catch (e) {
                // Error state - reset button
                submitBtn.disabled = false;
                buttonText.textContent = 'Submit Deposit';
                buttonSpinner.classList.add('d-none');
                errorEl.textContent = 'Failed to submit deposit: ' + e.message;
                errorEl.classList.remove('d-none');
            }
        });
    }
});
