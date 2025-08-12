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

const networks = {
    USDT: ["BSC (BEP20)", "TRON (TRC20)", "Ethereum (ERC20)", "Solana"],
    BTC: ["Bitcoin Mainnet", "Lightning Network"],
    ETH: ["Ethereum Mainnet", "Polygon", "Arbitrum"],
    USDC: ["Ethereum Mainnet", "Polygon", "Arbitrum"]
};

const networkInfo = {
    "BSC (BEP20)": { min: "0.5 USDT", depositConf: "12 Confirmation(s)", withdrawalConf: "30 Confirmation(s)" },
    "TRON (TRC20)": { min: "1 USDT", depositConf: "10 Confirmation(s)", withdrawalConf: "25 Confirmation(s)" },
    "Ethereum (ERC20)": { min: "5 USDT", depositConf: "15 Confirmation(s)", withdrawalConf: "40 Confirmation(s)" },
    "Solana": { min: "0.2 USDT", depositConf: "8 Confirmation(s)", withdrawalConf: "20 Confirmation(s)" },
    "Bitcoin Mainnet": { min: "0.0005 BTC", depositConf: "2 Confirmation(s)", withdrawalConf: "6 Confirmation(s)" },
    "Lightning Network": { min: "0.0001 BTC", depositConf: "Instant", withdrawalConf: "Instant" },
    "Ethereum Mainnet": { min: "0.05 ETH", depositConf: "15 Confirmation(s)", withdrawalConf: "40 Confirmation(s)" },
    "Polygon": { min: "1 MATIC", depositConf: "12 Confirmation(s)", withdrawalConf: "25 Confirmation(s)" },
    "Arbitrum": { min: "0.05 ETH", depositConf: "15 Confirmation(s)", withdrawalConf: "35 Confirmation(s)" }
};

// Wallet addresses and QR code paths for each network
const walletData = {
    "BSC (BEP20)": [
        { name: "Main Wallet", address: "0x1234...BSC", qr: "./img/qr/qr-code.png" },
    ],
    "TRON (TRC20)": [
        { name: "Main Wallet", address: "TXYZ...TRON", qr: "./img/qr/qr-code.png" }
    ],
    "Ethereum (ERC20)": [
        { name: "Main Wallet", address: "0xABCD...ETH", qr: "./img/qr/qr-code.png" }
    ],
    "Solana": [
        { name: "Main Wallet", address: "So1aNaAdDr3ss", qr: "./img/qr/qr-code.png" }
    ],
    "Bitcoin Mainnet": [
        { name: "Main Wallet", address: "1A2b3C4d5E", qr: "./img/qr/qr-code.png" }
    ],
    "Lightning Network": [
        { name: "Main Wallet", address: "lnbc1...", qr: "./img/qr/qr-code.png" }
    ],
    "Ethereum Mainnet": [
        { name: "Main Wallet", address: "0xEFGH...ETH", qr: "./img/qr/qr-code.png" }
    ],
    "Polygon": [
        { name: "Main Wallet", address: "0xPolyGonAddr", qr: "./img/qr/qr-code.png" }
    ],
    "Arbitrum": [
        { name: "Main Wallet", address: "0xArbiTrUm", qr: "./img/qr/qr-code.png" }
    ]
};

function updateWallets(network) {
    walletsContainer.innerHTML = '';
    const wallets = walletData[network] || [];
    if (wallets.length === 0) {
        walletsContainer.innerHTML = '<p class="text-muted">No wallets available for this network.</p>';
        return;
    }

    wallets.forEach(w => {
        const walletDiv = document.createElement('div');
        walletDiv.classList.add('wallet-item');

        const qrImg = document.createElement('img');
        qrImg.src = w.qr;
        qrImg.alt = w.name + " QR Code";
        qrImg.style.width = '100px';
        qrImg.style.height = '100px';
        qrImg.style.objectFit = 'contain';

        const infoDiv = document.createElement('div');
        infoDiv.classList.add('wallet-info');
        infoDiv.innerHTML = `<strong>${w.name}</strong><br><span class="wallet-address">${w.address}</span>`;

        const copyBtn = document.createElement('button');
        copyBtn.classList.add('copy-btn');
        copyBtn.title = 'Copy wallet address';
        copyBtn.innerHTML = '<i class="ri-file-copy-line"></i>';

        const tooltip = document.createElement('span');
        tooltip.classList.add('copy-tooltip');
        tooltip.textContent = 'Copied!';

        copyBtn.appendChild(tooltip);

        copyBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(w.address).then(() => {
                tooltip.classList.add('show');
                setTimeout(() => {
                    tooltip.classList.remove('show');
                }, 1500);
            }).catch(() => {
                alert('Failed to copy address');
            });
        });

        walletDiv.appendChild(qrImg);
        walletDiv.appendChild(infoDiv);
        walletDiv.appendChild(copyBtn);
        walletsContainer.appendChild(walletDiv);
    });
}

// Show loader while fetching networks
function loadNetworks(coin) {
    networkContainer.innerHTML = '';
    networkLoader.style.display = 'block';

    setTimeout(() => {
        networkLoader.style.display = 'none';
        if (networks[coin]) {
            networks[coin].forEach(net => {
                const col = document.createElement('div');
                col.classList.add('col-6');
                const btn = document.createElement('div');
                btn.classList.add('network-btn');
                btn.textContent = net;
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.network-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const info = networkInfo[net];
                    if (info) {
                        minDepositEl.textContent = info.min;
                        depositConfEl.textContent = info.depositConf;
                        withdrawalConfEl.textContent = info.withdrawalConf;
                    }

                    walletsContainer.innerHTML = '';
                    walletLoader.style.display = 'block';

                    setTimeout(() => {
                        walletLoader.style.display = 'none';
                        updateWallets(net);
                    }, 800);
                });
                col.appendChild(btn);
                networkContainer.appendChild(col);
            });
            // Auto select first network after loading
            const firstNetworkBtn = networkContainer.querySelector('.network-btn');
            if (firstNetworkBtn) {
                firstNetworkBtn.click();
            }
        }
    }, 800);
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
});